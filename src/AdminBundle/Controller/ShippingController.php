<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/8/2018
 * Time: 6:52 PM
 */

namespace AdminBundle\Controller;



use AdminBundle\Entity\Shipping;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ShippingController extends Controller
{
    public function indexAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $shippings = $em->getRepository(Shipping::class)->findAll();
        return $this->render("AdminBundle:Product:shipping.html.twig", array(
            "shippings" => $shippings,
        ));
    }

    public function editAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $post = $request->request;

        $country = $post->get("country");
        $region = $post->get("region");
        $town = $post->get('town');
        $price = $post->get('price');
        $id = $post->get('id');

        if( !empty($id) && (int)$id > 0 ){

            if( !empty( trim($country) ) && !empty( trim($region) ) && !empty( trim($town) ) && !empty( trim($price) ) )
            {
                $shipping = $em->getRepository(Shipping::class)->find($id);
                $shipping->setCountry($country);
                $shipping->setRegion($region);
                $shipping->setTown($town);
                $shipping->setPrice($price);
                $em->flush();
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"Lieu ajoutée avec succès enregistré avec succès"
                ));
            }else{
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Renseignez les champs requis avec conformité"
                ));
            }

        }else{
            if(!empty( trim($country) ) && !empty( trim($region) ) && !empty( trim($town) ) && !empty( trim($price) ) && is_numeric($price) ){
                    $shipping = new Shipping();
                    $shipping->setCountry($country);
                    $shipping->setRegion($region);
                    $shipping->setTown($town);
                    $shipping->setPrice($price);
                    $em->persist($shipping);
                    $em->flush();
                    return new JsonResponse(array(
                        "status"=>0,
                        "mes"=>"Lieu modifié avec succès"
                    ));
                }else{
                    return new JsonResponse(array(
                        "status"=>1,
                        "mes"=>"Renseignez les champs requis avec conformité"
                    ));
                }
            }
    }
}