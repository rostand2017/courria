<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/19/2018
 * Time: 4:08 PM
 */

namespace AdminBundle\Controller;


use AdminBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends Controller
{
    public static $USER_TYPE = ["CHEF_TYPE"=>"CHEF_TYPE", "CONTROLLER_TYPE"=>"CONTROLLER_TYPE", "SECRETAIRE_TYPE"=>"SECRETAIRE_TYPE"];
    public static $SEXE = ["F"=>"Femme", "M"=>"Homme"];

    public function loginAction(Request $request){

        if($request->getSession()->get("user")){
            return $this->redirectToRoute("admin_homepage");
        }
        if($request->isMethod('POST')){
            $email = $request->get('email');
            $mdp = $request->get("mdp");
            if($email && $mdp){
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository(Utilisateur::class)->findOneBy(["email"=>$email]);
                if( $user && password_verify($mdp, $user->getMdp()) ){
                    $request->getSession()->set("user", $user);
                    return new JsonResponse(["status"=>1, "mes"=>"Good", "url"=>$this->generateUrl("admin_dashboard")]);
                }else{
                    return new JsonResponse(['status'=>0, 'mes'=>'Email ou mot de passe incorrect.']);
                }
            }else{
                return new JsonResponse(['status'=>0, 'mes'=>'Email ou mot de passe incorrect']);
            }
        }

        return $this->render("AdminBundle:Account:login.html.twig");
    }

    public function logoutAction(Request $request){
        $request->getSession()->remove("user");
        return $this->redirectToRoute("admin_login");
    }

    // create default account

    public function createAction(){
        $admin = new Utilisateur();
        $admin->setMdp(password_hash("admin", PASSWORD_BCRYPT));
        $admin->setNom("admin");
        $admin->setEmail("admin@admin.com");
        $admin->setFonction(self::$USER_TYPE["SECRETAIRE_TYPE"]);
        $admin->setService("secretariat");
        $em = $this->getDoctrine()->getManager();
        $em->persist($admin);
        $em->flush();
    }


    public function createUserAction(Request $request){

        if($request->isMethod('POST')){
            $me = $request->getSession()->get("user");
            if($me->getFonction() != self::$USER_TYPE["SECRETAIRE_TYPE"])
                return new JsonResponse( ["status"=>1, "mes"=>"Vous n'avez pas le droit d'effectuer cette opération"] );

            $nom = $request->request->get("nom");
            $mdp = $request->request->get("mdp");
            $fonction = $request->request->get("fonction");
            $email = $request->request->get("email");
            $service = $request->request->get("service");

            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository(Utilisateur::class)->findOneBy(["email"=>$email]);
            if($u)
                return new JsonResponse( ["status"=>1, "mes"=>"Cette adresse email est déjà utilisée"] );
            if(strlen($mdp)<8)
                return new JsonResponse( ["status"=>1, "mes"=>"Le mot de passe doit contenir au moins 8 caractères"] );
            if(strlen($nom) > 2 && in_array($fonction, self::$USER_TYPE) && $service && $email){
                $user = new Utilisateur();
                $user->setService($service);
                $user->setFonction($fonction);
                $user->setNom($nom);
                $user->setEmail($email);
                $user->setMdp(password_hash($mdp, PASSWORD_BCRYPT));
                $em->persist($user);
                $em->flush();
                return new JsonResponse( ["status"=>0, "mes"=>"Utilisateur ajouté avec succès"] );
            }else{
                return new JsonResponse( ["status"=>1, "mes"=>"Renseignez correctement les champs"] );
            }
        }

        $me = $request->getSession()->get("user");
        if($me->getFonction() != self::$USER_TYPE["SECRETAIRE_TYPE"])
            return $this->redirectToRoute("admin_homepage");

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(Utilisateur::class)->findBy([], ["dateajout"=>"desc"]);
        return $this->render("AdminBundle:Account:index.html.twig", ["users"=>$users]);
    }

    public function modifyUserAction(Request $request, Utilisateur $user){

        $me = $request->getSession()->get("user");
        if($me->getFonction() != self::$USER_TYPE["SECRETAIRE_TYPE"])
            return new JsonResponse( ["status"=>1, "mes"=>"Vous n'avez pas le droit d'effectuer cette opération"] );

        $nom = $request->request->get("nom");
        $mdp = $request->request->get("mdp");
        $fonction = $request->request->get("fonction");
        $email = $request->request->get("email");
        $service = $request->request->get("service");

        $em = $this->getDoctrine()->getManager();
        $u = $em->getRepository(Utilisateur::class)->findOneBy(["email"=>$email]);
        if($u && $user->getId() != $u->getId())
            return new JsonResponse( ["status"=>1, "mes"=>"Cette adresse email est déjà utilisé"] );
        if($mdp && strlen($mdp)<8)
            return new JsonResponse( ["status"=>1, "mes"=>"Le mot de passe doit contenir au moins 8 caractères"] );
        if(strlen($nom) > 2 && in_array($fonction, self::$USER_TYPE) && $service && $email){
            $user->setService($service);
            $user->setFonction($fonction);
            $user->setNom($nom);
            $user->setEmail($email);
            $user->setMdp(password_hash($mdp, PASSWORD_BCRYPT));
            if($mdp)
                $user->setMdp(password_hash($mdp, PASSWORD_BCRYPT));
            $em->persist($user);
            $em->flush();
            return new JsonResponse( ["status"=>0, "mes"=>"Utilisateur modifié avec succès"] );
        }else{
            return new JsonResponse( ["status"=>1, "mes"=>"Renseignez correctement les champs"] );
        }
    }

    public function deleteUserAction(Request $request, Utilisateur $user){
        $me = $request->getSession()->get("user");
        if($me->getFonction() != self::$USER_TYPE["SECRETAIRE_TYPE"])
            return new JsonResponse( ["status"=>1, "mes"=>"Vous n'avez pas le droit d'effectuer cette opération"] );
        if($me->getId() == $user->getId())
            return new JsonResponse( ["status"=>1, "mes"=>"Vous ne pouvez pas supprimer votre propre compte"] );

        $em = $this->getDoctrine()->getManager();
        if($user){
            $em->remove($user);
            $em->flush();
            return new JsonResponse(array(
                "status"=>0,
                "mes"=>"L'utilisateur ".$user->getNom()." a été supprimé avec succès"
            ));
        }else{
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Une erreur est survenue"
            ));
        }
    }

    public function changePasswordAction(Request $request){
        if($request->isMethod('POST')){
            $user = $request->getSession()->get("user");
            $mdp = $request->request->get("mdp");
            if($mdp && password_verify($mdp, $user->getMdp()) && $newPassword = $request->request->get("newPassword") ){
                $em = $this->getDoctrine()->getManager();
                $user2 = $em->getRepository(Utilisateur::class)->find($user->getId());
                $user2->setMdp(password_hash($newPassword, PASSWORD_BCRYPT));
                $em->persist($user2);
                $em->flush();
                $request->getSession()->set("user", $user2 );
                return new JsonResponse( ["status"=>0, "mes"=>"Mot de passe modifié avec succès"] );
            }else{
                return new JsonResponse( ["status"=>1, "mes"=>"Mot de passe incorrect"] );
            }
        }

        return $this->render("AdminBundle:Account:change_password.html.twig");
    }
}