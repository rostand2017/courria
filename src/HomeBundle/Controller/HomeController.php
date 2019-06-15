<?php

namespace HomeBundle\Controller;

use Facebook\Facebook;
use HomeBundle\Entity\Activity;
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
        //$em = $this->getDoctrine()->getManager();
        return $this->render('HomeBundle:Home:index.html.twig', array(

        ));
    }
    public function influencerAction()
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('HomeBundle:Home:influencer.html.twig', array(

        ));
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

    public function loginAction(Request $request){
        if($request->getSession()->get("user")){
            return $this->redirectToRoute("home_homepage");
        }
        return $this->render("HomeBundle:Login:login.html.twig", array());
    }

}
