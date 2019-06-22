<?php

namespace HomeBundle\Controller;

use HomeBundle\Entity\Activity;
use HomeBundle\Entity\Category;
use HomeBundle\Entity\Commentaire;
use HomeBundle\Entity\Concert;
use HomeBundle\Entity\Contact;
use HomeBundle\Entity\Image;
use HomeBundle\Entity\Influencer;
use HomeBundle\Entity\NewsLetter;
use HomeBundle\Entity\Photo;
use HomeBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $photos = $em->getRepository(Photo::class)->findBy([], [], 5, 0);
        return $this->render('HomeBundle:Home:index.html.twig', array(
            "photos" => $photos
        ));
    }
    public function influencerAction()
    {
        $em = $this->getDoctrine()->getManager();
        $influencers = $em->getRepository(Influencer::class)->findBy([], [], 4, 0);
        $photos = array();
        foreach ($influencers as $influencer){
            $photo = $em->getRepository(Photo::class)->findOneByInfluencer($influencer->getId());
            if(!$photo)
                continue;
            array_push($photos, $photo);
        }
        return $this->render('HomeBundle:Home:influencer.html.twig', array(
            "influencers" => $influencers,
            "photos" => $photos
        ));
    }

    public function aboutAction(){
        $em = $this->getDoctrine()->getManager();
        $photos = $em->getRepository(Photo::class)->findBy([], [], 5, 0);
        return $this->render("HomeBundle:Home:about.html.twig", array(
            "photos" => $photos
        ));
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
                return new JsonResponse(['status'=>0, 'mes'=>'Message envoyé avec succès']);
            }else{
                return new JsonResponse(['status'=>1, 'mes'=>'Remplissez les champs avec conformité']);
            }
        }
        $em = $this->getDoctrine()->getManager();
        $photos = $em->getRepository(Photo::class)->findBy([], [], 5, 0);
        return $this->render("HomeBundle:Home:contact.html.twig", array(
            "photos" => $photos
        ));
    }

}
