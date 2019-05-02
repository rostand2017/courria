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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AuthController extends Controller
{
    private $user;

    public function __construct()
    {
        $request = new Request();
        if($request->getSession()){
            if( !($request->getSession()->get("user")) ){
                throw new AccessDeniedException("Accès non autorisé");
            }else{
                $this->user = $request->getSession()->get("user");
            }
        }else{
            throw new AccessDeniedException("Accès non autorisé");
        }
    }

    public function changePasswordAction(Request $request){
        //var_dump(unserialize($request->getSession()->get("user"))->getPassword());
        if($request->isMethod('POST')){
            //$user = unserialize($request->getSession()->get("user"));
            $user = $this->user;
            $password = $request->request->get("password");
            if($password && $password == $user->getPassword() && $newPassword = $request->request->get("newPassword") ){
                $em = $this->getDoctrine()->getManager();
                $user2 = $em->getRepository("AuthenticationBundle\Entity\User")->find($user->getId());
                $user2->setPassword($newPassword);
                $em->persist($user2);
                $em->flush();
                $request->getSession()->set("user", $user2 );
                return new JsonResponse( ["status"=>1, "mes"=>"Mot de passe modifié avec succès"] );
            }else{
                return new JsonResponse( ["status"=>0, "mes"=>"Mot de passe incorrect"] );
            }
        }
        return $this->render("AuthenticationBundle:Account:change_password.html.twig");
    }

}