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
    public static $USER_TYPE = ["DG_TYPE"=>"DG_TYPE", "CHEF_TYPE"=>"CHEF_TYPE", "SECRETAIRE_TYPE"=>"SECRETAIRE_TYPE"];
    public static $SEXE = ["F"=>"Femme", "M"=>"Homme"];

    public function loginAction(Request $request){

        if($request->getSession()->get("user")){
            return $this->redirectToRoute("admin_homepage");
        }
        if($request->isMethod('POST')){
            $username = $request->get('username');
            $password = $request->get("password");
            if($username && $password){
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository(Utilisateur::class)->findOneBy(["username"=>$username]);
                if( $user && password_verify($password, $user->getMdp()) ){
                    if($user->isBloque())
                        return new JsonResponse(['status'=>0, 'mes'=>'Votre compte a été bloqué. Veuillez contacter l\'administrateur']);
                    $request->getSession()->set("user", $user);
                    return new JsonResponse(["status"=>1, "mes"=>"Good", "url"=>$this->generateUrl("admin_dashboard")]);
                }else{
                    return new JsonResponse(['status'=>0, 'mes'=>'Nom ou mot de passe incorrect.']);
                }
            }else{
                return new JsonResponse(['status'=>0, 'mes'=>'Nom ou mot de passe incorrect']);
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
        $admin->setUsername("admin");
        $admin->setNom("DG Name");
        $admin->setFonction(self::$USER_TYPE["DG_TYPE"]);
        $admin->setSexe("M");
        $em = $this->getDoctrine()->getManager();
        $em->persist($admin);
        $em->flush();
    }


    public function createUserAction(Request $request){

        if($request->isMethod('POST')){
            $me = $request->getSession()->get("user");
            if($me->getFonction() == self::$USER_TYPE["SECRETAIRE_TYPE"] || $me->isBloque())
                return new JsonResponse( ["status"=>1, "mes"=>"Vous n'avez pas le droit d'effectuer cette opération"] );

            $username = $request->request->get("username");
            $mdp = $request->request->get("mdp");
            $fonction = $request->request->get("fonction");
            $nom = $request->request->get("nom");
            $prenom = $request->request->get("prenom");
            $sexe = $request->request->get("sexe");

            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository(Utilisateur::class)->findOneBy(["username"=>$username]);
            if($u)
                return new JsonResponse( ["status"=>1, "mes"=>"Ce nom d'utilisateur est déjà utilisé"] );
            if(strlen($mdp)<8)
                return new JsonResponse( ["status"=>1, "mes"=>"Le mot de passe doit contenir au moins 8 caractères"] );
            if(strlen($nom) > 2 && in_array($sexe, self::$SEXE) && in_array($fonction, self::$USER_TYPE)){
                $user = new Utilisateur();
                $user->setSexe($sexe);
                $user->setFonction($fonction);
                $user->setNom($nom);
                $user->setPrenom($prenom);
                $user->setUsername($username);
                $user->setMdp(password_hash($mdp, PASSWORD_BCRYPT));
                $em->persist($user);
                $em->flush();
                return new JsonResponse( ["status"=>0, "mes"=>"Utilisateur ajouté avec succès"] );
            }else{
                return new JsonResponse( ["status"=>1, "mes"=>"Renseignez correctement les champs"] );
            }
        }

        $me = $request->getSession()->get("user");
        if($me->getFonction() == self::$USER_TYPE["SECRETAIRE_TYPE"])
            return $this->redirectToRoute("admin_homepage");

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(Utilisateur::class)->findBy([], ["bloque"=>"asc", "createdat"=>"desc"]);
        return $this->render("AdminBundle:Account:index.html.twig", ["users"=>$users]);
    }

    public function modifyUserAction(Request $request, Utilisateur $user){

        $me = $request->getSession()->get("user");
        if($me->getFonction() == self::$USER_TYPE["SECRETAIRE_TYPE"] || $me->isBloque())
            return new JsonResponse( ["status"=>1, "mes"=>"Vous n'avez pas le droit d'effectuer cette opération"] );

        $username = $request->request->get("username");
        $mdp = $request->request->get("mdp");
        $fonction = $request->request->get("fonction");
        $nom = $request->request->get("nom");
        $prenom = $request->request->get("prenom");
        $sexe = $request->request->get("sexe");

        $em = $this->getDoctrine()->getManager();
        $u = $em->getRepository(Utilisateur::class)->findOneBy(["username"=>$username]);
        if($u && $user->getId() != $u->getId())
            return new JsonResponse( ["status"=>1, "mes"=>"Ce nom d'utilisateur est déjà utilisé"] );
        if($mdp && strlen($mdp)<8)
            return new JsonResponse( ["status"=>1, "mes"=>"Le mot de passe doit contenir au moins 8 caractères"] );
        if(strlen($nom) > 2 && in_array($sexe, self::$SEXE) && in_array($fonction, self::$USER_TYPE)){
            $user->setSexe($sexe);
            $user->setFonction($fonction);
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setUsername($username);
            if($mdp)
                $user->setMdp(password_hash($mdp, PASSWORD_BCRYPT));
            $em->persist($user);
            $em->flush();
            return new JsonResponse( ["status"=>0, "mes"=>"Utilisateur modifié avec succès"] );
        }else{
            return new JsonResponse( ["status"=>1, "mes"=>"Renseignez correctement les champs"] );
        }
    }

    public function blockUserAction(Request $request, Utilisateur $user){
        $me = $request->getSession()->get("user");
        if($me->getFonction() == self::$USER_TYPE["SECRETAIRE_TYPE"] || $me->isBloque())
            return new JsonResponse( ["status"=>1, "mes"=>"Vous n'avez pas le droit d'effectuer cette opération"] );
        if($me->getId() == $user->getId())
            return new JsonResponse( ["status"=>1, "mes"=>"Vous ne pouvez pas vous bloquer vous même"] );

        $em = $this->getDoctrine()->getManager();
        if($user){
            $user->setBloque(true);
            $em->persist($user);
            $em->flush();
            return new JsonResponse(array(
                "status"=>0,
                "mes"=>"L'utilisateur ".$user->getUsername()." a été bloqué avec succès"
            ));
        }else{
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Une erreur est survenue"
            ));
        }
    }

    public function unblockUserAction(Request $request, Utilisateur $user){

        $me = $request->getSession()->get("user");
        if($me->getFonction() == self::$USER_TYPE["SECRETAIRE_TYPE"] || $me->isBloque())
            return new JsonResponse( ["status"=>1, "mes"=>"Vous n'avez pas le droit d'effectuer cette opération"] );

        $em = $this->getDoctrine()->getManager();
        if($user){
            $user->setBloque(false);
            $em->persist($user);
            $em->flush();
            return new JsonResponse(array(
                "status"=>0,
                "mes"=>"L'utilisateur ".$user->getUsername()." a été débloqué avec succès"
            ));
        }else{
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Une erreur est survenue"
            ));
        }
    }

    public function deleteUserAction(Request $request, Utilisateur $user){
        $me = $request->getSession()->get("user");
        if($me->getFonction() == self::$USER_TYPE["SECRETAIRE_TYPE"] || $me->isBloque())
            return new JsonResponse( ["status"=>1, "mes"=>"Vous n'avez pas le droit d'effectuer cette opération"] );
        if($me->getId() == $user->getId())
            return new JsonResponse( ["status"=>1, "mes"=>"Vous ne pouvez pas supprimer votre propre compte"] );

        $em = $this->getDoctrine()->getManager();
        if($user){
            $em->remove($user);
            $em->flush();
            return new JsonResponse(array(
                "status"=>0,
                "mes"=>"L'utilisateur ".$user->getUsername()." a été supprimé avec succès"
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
            $password = $request->request->get("password");
            if($password && password_verify($password, $user->getMdp()) && $newPassword = $request->request->get("newPassword") ){
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