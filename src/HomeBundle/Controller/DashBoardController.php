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
use HomeBundle\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DashBoardController extends Controller
{
    public function userDashBoardAction(Request $request)
    {
        $user = $request->getSession()->get('user');
        if($user){
            $em = $this->getDoctrine()->getManager();
            $reservations = $em->getRepository(Reservation::class)->findByCli($user->getId());
            return $this->render('HomeBundle:Home:user_dashboard.html.twig', array("reservations" => $reservations));
        }else{
            return $this->redirectToRoute('home_homepage');
        }
    }

}
