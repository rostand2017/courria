<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/8/2018
 * Time: 6:52 PM
 */

namespace AdminBundle\Controller;

use AdminBundle\Entity\Product;
use AdminBundle\Entity\Produit;
use AdminBundle\Entity\Stock;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{

    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        $nbProduct = $em->getRepository(Produit::class)->getTodayProduct();
        $nbStock = $em->getRepository(Stock::class)->getTodayStock();
        $stocks = $em->getRepository(Stock::class)->getStockProduct();
        return $this->render('AdminBundle:Produit:dashboard.html.twig', array(
            "nbProduct" => $nbProduct,
            "nbStock" => $nbStock,
            "stocks" => $stocks,
        ));
    }

    private function getUniqueFileName(){
        return md5(uniqid());
    }

}