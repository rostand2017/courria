<?php

namespace HomeBundle\Controller;

use Facebook\Facebook;
use HomeBundle\Entity\Category;
use HomeBundle\Entity\Commentaire;
use HomeBundle\Entity\Concert;
use HomeBundle\Entity\Contact;
use HomeBundle\Entity\Image;
use HomeBundle\Entity\NewsLetter;
use HomeBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $concerts = $em->getRepository(Concert::class)->findBy([], ['date'=>'desc'], 10, 0);
        return $this->render('HomeBundle:Home:index.html.twig', array(
            "concerts" => $concerts,
        ));
    }

    public function loginAction(Request $request){
        if($request->getSession()->get("user")){
            return $this->redirectToRoute("home_homepage");
        }
        return $this->render("HomeBundle:Login:login.html.twig", array());
    }

    public function registerAction(Request $request){
        if($request->getSession()->get("user")){
            return $this->redirectToRoute("home_homepage");
        }
        return $this->render("HomeBundle:Login:register.html.twig");
    }

    public function changePasswordAction(Request $request){
        $user = $request->getSession()->get("user");
        if(!$user){
            return $this->redirectToRoute("home_login");
        }
        return $this->render("HomeBundle:Login:change_password.html.twig");
    }

    public function forgotPasswordAction(Request $request){
        $user = $request->getSession()->get("user");
        if($user){
            return $this->redirectToRoute("home_feature");
        }
        return $this->render("HomeBundle:Login:forgot_password.html.twig");
    }

    public function aboutAction(){
        return $this->render("HomeBundle:Home:about.html.twig");
    }

    public function contactAction(Request $request){
        if($request->isMethod('POST')){
            $email = $request->request->get('email');
            $message = $request->request->get('message');
            if($message && $email && preg_match("#[a-zA-Z0-9]{2,}@[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}#", $email)){
                // insertion dans la table contact
                $em = $this->getDoctrine()->getManager();
                $c = new Contact();
                $c->setEmail($email);
                $c->setMessage($message);
                $em->persist($c);
                $em->flush();
                return new JsonResponse(['status'=>1, 'message'=>'Message envoyé avec succès']);
            }else{
                return new JsonResponse(['status'=>0, 'message'=>'Remplir les champs avec conformité']);
            }
        }
        return $this->render("HomeBundle:Home:contact.html.twig");
    }

    public function subscribeAction(Request $request){
        $email = $request->request->get('email');
        if($email && preg_match("#[a-zA-Z0-9]{2,}@[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}#", $email)){
            $em = $this->getDoctrine()->getManager();
            $isEmail = $em->getRepository(NewsLetter::class)->findBy(['email'=>$email]);
            if(!$isEmail){
                $n = new NewsLetter();
                $n->setEmail($email);
                $em->persist($n);
                $em->flush();
                return new JsonResponse(['status'=>0, 'message'=>'Souscription effectuée']);
            }else{
                return new JsonResponse(['status'=>1, 'message'=>'Une souscription a déjà été faite avec cette adresse']);
            }
        }else{
            return new JsonResponse(['status'=>1, 'message'=>'adresse invalide !']);
        }
    }

    public function userDashBoardAction(Request $request){
        if(!$request->getSession()->get("user")){
            return $this->redirectToRoute("home_login");
        }
        return $this->render("HomeBundle:Home:user_dashboard.html.twig");
    }

    public function commentAction(Request $request){
        $nom = $request->request->get('nom');
        $message = $request->request->get('message');
        if($nom && $message && $nom != '' && $message != ''){
            $em = $this->getDoctrine()->getManager();
            $commentaire = new Commentaire();
            $commentaire->setNom($nom);
            $commentaire->setMessage($message);
            $em->persist($commentaire);
            $em->flush();
            return new JsonResponse(['status'=>0, 'message'=>'Commentaire envoyé avec succès']);
        }else{
            return new JsonResponse(['status'=>1, 'message'=>'Remplissez les différents champs']);
        }
    }

}
