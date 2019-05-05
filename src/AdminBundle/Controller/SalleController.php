<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/8/2018
 * Time: 6:52 PM
 */

namespace AdminBundle\Controller;


use AdminBundle\Classes\Thumbnails;
use HomeBundle\Entity\Admin;
use HomeBundle\Entity\Category;
use HomeBundle\Entity\Image;
use HomeBundle\Entity\InfoProduct;
use HomeBundle\Entity\Product;
use HomeBundle\Entity\Salle;
use HomeBundle\Entity\Slide;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SalleController extends Controller
{


    public function indexAction(){
        $salles = $this->getDoctrine()->getRepository(Salle::class)->findAll();
        $nbSalle = count($salles);
        return $this->render("AdminBundle:Concert:salle.html.twig", array(
            "salles" => $salles,
            "nbSalle" => $nbSalle
        ));
    }

    public function editAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $post = $request->request;
        $action = $post->get("action");
        $id = $post->get("id");
        $nom = $post->get("nom");
        $capacite = $post->get("capacite");
        $lieu = $post->get("lieu");

        if($action && $action == "edit"){
            if( is_numeric($id) && $id >0 && $nom != '' && $capacite != '' && is_numeric($capacite) && $capacite > 0 && $lieu!='' )
            {
                $salle = $em->getRepository(Salle::class)->find($id);
                if($salle){
                    $salle->setNom($nom);
                    $salle->setCapacite($capacite);
                    $salle->setLieu($lieu);
                    $em->persist($salle);
                    $em->flush();
                    return new JsonResponse(array(
                        "status"=>0,
                        "mes"=>"Les informations de cette salle ont été modifiée avec succès"
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
                    "mes"=>"Renseignez les champs requis avec conformité"
                ));
            }
        }else{
            if($nom != '' && $capacite != '' && is_numeric($capacite) && $capacite > 0 && $lieu!='' )
            {
                $salle = new Salle();
                $salle->setNom($nom);
                $salle->setCapacite($capacite);
                $salle->setLieu($lieu);
                $idAdmin = $request->getSession()->get("admin")->getId();
                $salle->setAdm($em->getRepository(Admin::class)->find($idAdmin));
                $em->persist($salle);
                $em->flush();
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"Salle ajoutée avec succès"
                ));
            }else{
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Renseignez les champs requis avec conformité"
                ));
            }
        }
    }

    public function deleteAction(Request $request){
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        if($id && is_numeric($id) && $id > 0){
            $salle = $em->getRepository(Salle::class)->find($id);
            if($salle){
                $em->remove($salle);
                $em->flush();
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"Salle ".$salle->getNom()." supprimée avec succès"
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