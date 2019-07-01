<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/19/2018
 * Time: 4:08 PM
 */

namespace AdminBundle\Controller;


use AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccountController extends Controller
{
    private $user;

    public function __construct()
    {
        $request = new Request();
        if($request->getSession()){
            if(!$this->user = $request->getSession()->get("admin")){
                throw new AccessDeniedException("Accès non autorisé");
            }else{
                $this->user = ($this->user);
            }
        }
    }


    public function loginAction(Request $request){

        if($request->getSession()->get("admin")){
            return $this->redirectToRoute("admin_homepage");
        }
        if($request->isMethod('POST')){
            $username = $request->get('username');
            $password = $request->get("password");
            if($username && $password){
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository(User::class)->findOneBy(["username"=>$username]);
                if( $user && password_verify($password, $user->getPassword()) ){
                    $request->getSession()->set("admin", $user);
                    return new JsonResponse(["status"=>1, "mes"=>"Good", "url"=>$this->generateUrl("admin_homepage")]);
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
        $request->getSession()->remove("admin");
        return $this->redirectToRoute("admin_login");
    }

    // create default account

    /*
    public function createAction(){
        $admin = new User();
        $admin->setPassword(password_hash("admin", PASSWORD_BCRYPT));
        $admin->setUsername("admin");
        $em = $this->getDoctrine()->getManager();
        $em->persist($admin);
        $em->flush();
    }
    */


    public function changePasswordAction(Request $request){
        if($request->isMethod('POST')){
            $user = $request->getSession()->get("admin");
            $password = $request->request->get("password");
            if($password && password_verify($password, $user->getPassword()) && $newPassword = $request->request->get("newPassword") ){
                $em = $this->getDoctrine()->getManager();
                $user2 = $em->getRepository(User::class)->find($user->getId());
                $user2->setPassword(password_hash($newPassword, PASSWORD_BCRYPT));
                $em->persist($user2);
                $em->flush();
                $request->getSession()->set("admin", $user2 );
                return new JsonResponse( ["status"=>0, "mes"=>"Mot de passe modifié avec succès"] );
            }else{
                return new JsonResponse( ["status"=>1, "mes"=>"Mot de passe incorrect"] );
            }
        }

        return $this->render("AdminBundle:Account:change_password.html.twig");
    }
}