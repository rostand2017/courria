<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/19/2018
 * Time: 4:08 PM
 */

namespace AuthenticationBundle\Controller;


use AuthenticationBundle\Classes\Thumbnails;
use AuthenticationBundle\Entity\User;
use Facebook\Facebook;
use HomeBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends Controller
{

    public function __construct()
    {
    }

    public function loginAction(Request $request){

        if($request->getSession()->get("user")){
            return new JsonResponse(['status'=>1, 'mes'=>'Attention!']);
        }
        if($request->isMethod('POST')){
            $email = $request->request->get('email');
            $password = $request->request->get("password");
            if( !empty(trim($email)) && !empty($password) ){
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository(Client::class)->findOneBy(["email"=>$email]);
                if($user && password_verify($password, $user->getMdp())){
                    $request->getSession()->set("user", $user);
                    return new JsonResponse(['status'=>0, 'mes'=>'Bienvenue '.$user->getNom()]);
                }else{
                    return new JsonResponse(['status'=>1, 'mes'=>'Email ou mot de passe incorrect.']);
                }
            }else{
                return new JsonResponse(['status'=>1, 'mes'=>'Email ou mot de passe incorrect']);
            }
        }
    }

    public function registerAction(Request $request){
        $imageAccepted = array("jpg", "png", "jpeg");

        if($request->getSession()->get("user")){
            return new JsonResponse(['status'=>1, 'mes'=>'Attention!']);
        }
        if($request->isMethod('POST')){
            $nom = $request->get('nom');
            $prenom = $request->get('prenom');
            $age = $request->get('age');
            $sexe = $request->get('sexe');
            $email = $request->get('email');
            $password = $request->get("password");
            $cpassword = $request->get("confirm_password");
            $sexeArray = ['M', 'F'];

            $photo = $request->files->get('photo');
            if($nom && $nom != '' && $prenom && $prenom != '' && $age && $age!='' && in_array($sexe, $sexeArray)
                && $password && $cpassword && $email ){
                if(!preg_match('#.{8,}#', $password)){
                    return new JsonResponse(["status"=>1, "mes"=>"Le mot de passe doit comprendre au moins 8 caratères"]);
                }
                if($password != $cpassword){
                    return new JsonResponse(["status"=>1, "mes"=>"Les mots de passe ne correspondent pas !"]);
                }
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository(Client::class)->findOneBy(["email"=>$email]);
                if(!preg_match("#[a-zA-Z0-9]{2,}@[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}#", $email)){
                    return new JsonResponse(["status"=>1, "mes"=>"Entrez une adresse email valide"]);
                }
                if($user != null){
                    return new JsonResponse(["status"=>1, "mes"=>"Cette adresse a déjà été utilisée"]);
                }
                $u = new Client();
                $u->setNom($nom);
                $u->setPrenom($prenom);
                $u->setEmail($email);
                $u->setAge($age);
                $u->setSexe($sexe);
                $u->setMdp(password_hash($password, PASSWORD_BCRYPT));

                if($photo && in_array($photo->guessExtension(), $imageAccepted)) {
                    $imageDirectory = $this->getParameter("image_directory");
                    $thumbnailDirectory = $this->getParameter("thumbnail_directory");
                    $filename = $this->getUniqueFileName() . "." . $photo->guessExtension();
                    $photo->move($imageDirectory, $filename);
                    $thumb = new Thumbnails();
                    $thumb->createThumbnail($imageDirectory . "/" . $filename, $thumbnailDirectory . "/" . $filename, 200);
                    $u->setPhoto($filename);
                }

                $em->persist($u);
                $em->flush();
                $request->getSession()->set("user", $u);
                return new JsonResponse(['status'=>0, 'mes'=>"Inscription réussite"]);
            }else{
                return new JsonResponse(['status'=>1, 'mes'=>'Vérifiez la conformité des différents champs']);
            }
        }
    }

    public function logoutAction(Request $request){
        $request->getSession()->remove("user");
        return $this->redirectToRoute("home_homepage");
    }

    public function changePasswordAction(Request $request){
        if($request->isMethod('POST')){
            $user = $request->getSession()->get("user");
            $password = $request->request->get("password");
            $newPassword = $request->request->get("newPassword");
            if(!$user->getPassword()){
                return new JsonResponse( ["status"=>1, "mes"=>"Désolé, impossible de changer votre mot de passe puisque vous vous êtes connecté avec votre compte \'facebook\'"] );
            }
            if($password && password_verify($password, $user->getPassword()) ){

                if($newPassword && !empty(trim($newPassword))){
                    $em = $this->getDoctrine()->getManager();
                    $user2 = $em->getRepository("AuthenticationBundle\Entity\User")->find($user->getId());
                    $user2->setPassword(password_hash($newPassword, PASSWORD_BCRYPT));
                    $em->persist($user2);
                    $em->flush();
                    $request->getSession()->set("user", $user2 );
                    return new JsonResponse( ["status"=>0, "mes"=>"Mot de passe modifié avec succès"] );
                }else{
                    return new JsonResponse( ["status"=>1, "mes"=>"Définir un nouveau mot de passe dans le champs prévu à cet effet."] );
                }
            }else{
                return new JsonResponse( ["status"=>1, "mes"=>"Mot de passe incorrect"] );
            }
        }
        return new JsonResponse( ["status"=>1, "mes"=>"Une erreur est survenue"] );
    }

    public function forgotPasswordAction(Request $request){
        if($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $email = $request->request->get("email");
            $user = $em->getRepository(Client::class)->findOneBy(['email'=>$email]);
            if (!$user) {
                return new JsonResponse(["status" => 1, "mes" => "Cette adresse email n'existe pas"]);
            }else{
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $p = substr(str_shuffle($characters), 0, 8);
                $user->setMdp(password_hash($p, PASSWORD_BCRYPT));
                $em->persist($user);
                $em->flush();
                $mes = (new \Swift_Message("Nouveau mot de passe"))
                    ->setFrom('contact@nopermission.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'Email/new_password.html.twig',
                            array('message' => "Votre nouveau mot de passe est: ".$p, 'nom'=>$user->getNom())
                        ),
                        "text/html"
                    );
                $this->get('mailer')->send($mes);
                return new JsonResponse( ["status"=>0, "mes"=>"Consultez vos mails pour voir votre nouveau mot de passe"] );
            }
        }
        return new JsonResponse( ["status"=>1, "mes"=>"Une erreur est survenue"] );
    }

    public function getUniqueFileName(){
        return md5(uniqid());
    }
}