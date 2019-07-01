<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/8/2018
 * Time: 6:52 PM
 */

namespace AdminBundle\Controller;

use AdminBundle\Entity\Product;
use AdminBundle\Entity\Stock;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class StockController extends Controller
{

    public function indexAction(Request $request){

        $page = ( $request->query->get("page") )?$request->query->get("page") : 1;
        $nbPerPage = 10;
        $em = $this->getDoctrine()->getManager();
        $nbStock = $em->getRepository(Stock::class)
            ->countByDateAndName($page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"), $request->query->get("search"));
        $stocks = $em->getRepository(Stock::class)
            ->getByDateAndName($page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"), $request->query->get("search"));
        $products = $em->getRepository(Product::class)->findAll();
        $nbPage = ceil($nbStock / $nbPerPage);
        return $this->render('AdminBundle:Product:stock.html.twig', array(
            "stocks" => $stocks,
            "products" => $products,
            "page" => $page,
            "nbPage" => $nbPage,
            "nbStock" => $nbStock,
        ));
    }

    public function addAction(Request $request){
        $productId = $request->get("product");
        $quantity = $request->get("quantity");
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        if(!$quantity && $quantity == ''){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Renseignez une quantité"
            ));
        }
        $product = $em->getRepository(Product::class)->find($productId);
        if(!$product){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Une erreur est survenue"
            ));
        }
        if($id && $id != '' && is_numeric($id) && $id >0){
            $stock = $em->getRepository(Stock::class)->find($id);
            if($stock){
                $stock->setProduct($product);
                $stock->setQuantity($quantity);
                $em->persist($stock);
                $em->flush();
                return new JsonResponse(array(
                    "status" => 0,
                    "mes" => "Le stock du produit " . $product->getName() . " a été modifiée avec succès."
                ));
            }else{
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Une erreur est survenue."
                ));
            }
        }else {
            $stock = new Stock();
            $stock->setProduct($product);
            $stock->setQuantity($quantity);
            $em->persist($stock);
            $em->flush();
            return new JsonResponse(array(
                "status" => 0,
                "mes" => "Le stock du produit '" . $product->getName() . "' a été ajoutée avec succès."
            ));
        }
    }

    public function deleteAction(Request $request){
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        if($id && is_numeric($id) && $id > 0){
            $stock = $em->getRepository(Stock::class)->find($id);
            if($stock){
                $em->remove($stock);
                $em->flush();
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"Le stock du produit ".$stock->getProduct()->getName()." a été retiré avec succès."
                ));
            }else{
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Une erreur est survenue"
                ));
            }
        }else{
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Une erreur est survenue"
            ));
        }
    }

    private function getUniqueFileName(){
        return md5(uniqid());
    }

}