<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/8/2018
 * Time: 6:52 PM
 */

namespace AdminBundle\Controller;

use AdminBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{

    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        $nbProduct = count($em->getRepository(Product::class)->findAll());
        return $this->render('AdminBundle:Product:dashboard.html.twig', array(
            "nbProduct" => $nbProduct,
        ));
    }

    private function getUniqueFileName(){
        return md5(uniqid());
    }

}