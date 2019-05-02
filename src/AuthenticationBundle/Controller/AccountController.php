<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/19/2018
 * Time: 4:08 PM
 */

namespace AuthenticationBundle\Controller;


use AuthenticationBundle\Entity\User;
use Facebook\Facebook;
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
            return $this->redirectToRoute("home_product");
        }
        if($request->isMethod('POST')){
            $email = $request->request->get('email');
            $password = $request->request->get("password");
            if( !empty(trim($email)) && !empty($password) ){
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository("AuthenticationBundle\Entity\User")->findOneBy(["email"=>$email]);
                if($user && password_verify($password, $user->getPassword())){
                    $request->getSession()->set("user", $user);
                    return new JsonResponse(['status'=>0, 'mes'=>'Bienvenue '.$user->getName()]);
                }else{
                    return new JsonResponse(['status'=>1, 'mes'=>'Email ou mot de passe incorrect.']);
                }
            }else{
                return new JsonResponse(['status'=>1, 'mes'=>'Email ou mot de passe incorrect']);
            }
        }
    }

    public function registerAction(Request $request){

        if($request->getSession()->get("user")){
            return $this->redirectToRoute("home_product");
        }
        if($request->isMethod('POST')){
            $username = $request->get('username');
            $email = $request->get('email');
            $password = $request->get("password");
            $cpassword = $request->get("confirm_password");
            if($username && $password && $cpassword && $email && $password == $cpassword){
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository("AuthenticationBundle\Entity\User")->findOneBy(["email"=>$email]);
                if($user != null || !preg_match("#[a-zA-Z0-9]{2,}@[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}#", $email)){
                    return new JsonResponse(["status"=>0, "mes"=>"Entrez une adresse email valide"]);
                }
                // Enregistremment de l'utilisateur si tout est bon
                $u = new User();
                $u->setEmail($email);
                $u->setName($username);
                $u->setPassword(password_hash($password, PASSWORD_BCRYPT));
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
            $user = $em->getRepository(User::class)->findOneBy(['email'=>$email]);
            if (!$user) {
                return new JsonResponse(["status" => 1, "mes" => "Cette adresse email n'existe pas"]);
            }else{
                if(!$user->getPassword()){
                    return new JsonResponse(["status" => 1, "mes" => "Désolé, impossible de réinitialiser votre mot de passe puisque vous vous êtes enregistré avec votre compte \'facebook\'"]);
                }else{
                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $p = substr(str_shuffle($characters), 0, 6);
                    $user->setPassword(password_hash($p, PASSWORD_BCRYPT));
                    $em->persist($user);
                    $em->flush();
                    $mes = (new \Swift_Message("Nouvrau mot de passe"))
                        ->setFrom('contact@nopermission.com')
                        ->setTo($user->getEmail())
                        ->setBody(
                            $this->renderView(
                                'Email/new_password.html.twig',
                                array('message' => "Votre nouveau mot de passe est: ".$p)
                            ),
                            "text/html"
                        );
                    $this->get('mailer')->send($mes);
                    return new JsonResponse( ["status"=>0, "mes"=>"Consultez vos mails pour voir votre nouveau mot de passe"] );
                }
            }
        }
        return new JsonResponse( ["status"=>1, "mes"=>"Une erreur est survenue"] );
    }

    public function loginFacebookAction(Request $request){
        $f = new Facebook([
            'app_id' => '366830307424113',
            'app_secret' => '3faed04e7d58085d56c8e7b1977440de',
            'default_graph_version' => 'v3.2'
        ]);

        if( !$request->getSession()->get("user") ) {
            $helper = $f->getRedirectLoginHelper();
            if($state = $request->query->get("state")){
                $helper->getPersistentDataHandler()->set("state", $state);
            }
            $accessToken = $helper->getAccessToken();
            /*Step 4: Get the graph user*/
            if(isset($accessToken)) {
                try {
                    $response = $f->get('/me?fields=email,id,name',$accessToken);
                    $fb_user = $response->getGraphUser();
                    $em = $this->getDoctrine()->getManager();
                    $user = $em->getRepository("AuthenticationBundle\Entity\User")->findOneByFacebookId($fb_user->getId());

                    if($user && !empty($user)){
                        // si l'utilisateur existe deja dans la bd, on crée une fois sa session
                        $request->getSession()->set("user", $user);
                    }else{
                        // sinon, on l'enregistre avant de créer sa session
                        $user = new User();
                        $user->setName($fb_user->getName());
                        $user->setFacebookId($fb_user->getId());
                        ($fb_user->getEmail())?$user->setEmail($fb_user->getEmail()):"";
                        $em->persist($user);
                        $em->flush();
                        $request->getSession()->set("user", $user);
                    }
                } catch (\Facebook\Exceptions\FacebookResponseException $e) {
                    echo  'Graph returned an error: ' . $e->getMessage();
                } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                    // When validation fails or other local issues
                    echo 'Facebook SDK returned an error: ' . $e->getMessage();
                }
                // si tout est bon, on va vers feature
                return $this->redirectToRoute("home_feature");
            }
        }
        return $this->redirectToRoute("home_homepage");
    }
}