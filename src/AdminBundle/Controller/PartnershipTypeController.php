<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/8/2018
 * Time: 6:52 PM
 */
namespace AdminBundle\Controller;

use HomeBundle\Entity\Activity;
use HomeBundle\Entity\Concerne;
use HomeBundle\Entity\Influencer;
use HomeBundle\Entity\Partnershiptype;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PartnershipTypeController extends Controller
{

    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        $partners = $em->getRepository(Partnershiptype::class)->findAll();
        return $this->render('AdminBundle:Partner:index.html.twig', array(
            "partners" => $partners,
            "nbPartner" => count($partners),
        ));
    }

    public function addAction(Request $request){
        $description = $request->get("description");
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        if(!$description && $description == ''){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Renseignez tous les champs"
            ));
        }
        if($id && $id != '' && is_numeric($id) && $id >0){
            $partner = $em->getRepository(Partnershiptype::class)->find($id);
            if($partner){
                $partner->setDescription($description);
                $em->persist($partner);
                $em->flush();
                return new JsonResponse(array(
                    "status" => 0,
                    "mes" => $partner->getDescription() . " a été modifiée avec succès."
                ));
            }else{
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Une erreur est survenue."
                ));
            }
        }else {
            $partner = new Partnershiptype();
            $partner->setDescription($description);
            $em->persist($partner);
            $em->flush();
            return new JsonResponse(array(
                "status" => 0,
                "mes" => "Partenariat " . $partner->getDescription() . " a été ajoutée avec succès."
            ));
        }
    }

    public function deleteAction(Request $request){
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        if($id && is_numeric($id) && $id > 0){
            $influencers = $em->getRepository(Influencer::class)->findByPartnership($id);
            if($influencers){
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Impossible de supprimer ce type de partenariat car il est déjà utilisé par certains influenceurs."
                ));
            }
            $partner = $em->getRepository(Partnershiptype::class)->find($id);
            if($partner){
                $em->remove($partner);
                $em->flush();
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>$partner->getDescription()." a été supprimé avec succès."
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
}