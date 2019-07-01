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

class ProductController extends Controller
{

    public function indexAction(Request $request){

        $page = ( $request->query->get("page") )?$request->query->get("page") : 1;
        $nbPerPage = 10;
        $em = $this->getDoctrine()->getManager();
        $nbProduct = $em->getRepository(Product::class)
            ->countByDateAndName($page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"), $request->query->get("search"));
        $products = $em->getRepository(Product::class)
            ->getByDateAndName($page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"), $request->query->get("search"));

        $nbPage = ceil($nbProduct / $nbPerPage);
        return $this->render('AdminBundle:Product:index.html.twig', array(
            "products" => $products,
            "page" => $page,
            "nbPage" => $nbPage,
            "nbProduct" => $nbProduct,
        ));
    }

    public function addAction(Request $request){
        $name = $request->get("name");
        $description = $request->get("description");
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        if(!$name && $name == ''){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Renseignez le nom du produit"
            ));
        }
        if($id && $id != '' && is_numeric($id) && $id >0){
            $product = $em->getRepository(Product::class)->find($id);
            if($product){
                $product->setName($name);
                $product->setDescription($description);
                $em->persist($product);
                $em->flush();
                return new JsonResponse(array(
                    "status" => 0,
                    "mes" => "Le produit " . $product->getName() . " a été modifiée avec succès."
                ));
            }else{
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Une erreur est survenue."
                ));
            }
        }else {
            $product = new Product();
            $product->setName($name);
            $product->setDescription($description);
            $em->persist($product);
            $em->flush();
            return new JsonResponse(array(
                "status" => 0,
                "mes" => "Le produit " . $product->getName() . " a été ajoutée avec succès."
            ));
        }
    }

    public function deleteAction(Request $request){
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        if($id && is_numeric($id) && $id > 0){
            $product = $em->getRepository(Product::class)->find($id);
            if($product){
                $product->setIsdeleted(true);
                $em->persist($product);
                $em->flush();
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"Le produit ".$product->getName()." a été supprimée"
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

    public function seeAction(Product $product){
        $em = $this->getDoctrine();
        $photos = $em->getRepository(Photo::class)->findByProduct($product->getId());
        $concernes = $em->getRepository(Concerne::class)->findByProduct($product->getId());
        return $this->render('AdminBundle:Product:single_product.html.twig', array(
            "product" => $product,
            "concernes" => $concernes,
            "photos" => $photos,
        ));
    }

    private function getUniqueFileName(){
        return md5(uniqid());
    }

}