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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ActivityController extends Controller
{

    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        $activities = $em->getRepository(Activity::class)->findAll();
        return $this->render('AdminBundle:Activity:index.html.twig', array(
            "activities" => $activities,
            "nbActivity" => count($activities),
        ));
    }

    public function addAction(Request $request){
        $description = $request->get("description");
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        if(!$description && $description == ''){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Renseignez l'activité"
            ));
        }
        if($id && $id != '' && is_numeric($id) && $id >0){
            $activity = $em->getRepository(Activity::class)->find($id);
            if($activity){
                $activity->setDescription($description);
                $em->persist($activity);
                $em->flush();
                return new JsonResponse(array(
                    "status" => 0,
                    "mes" => "L'activité " . $activity->getDescription() . " a été modifiée avec succès."
                ));
            }else{
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Une erreur est survenue."
                ));
            }
        }else {
            $activity = new Activity();
            $activity->setDescription($description);
            $em->persist($activity);
            $em->flush();
            return new JsonResponse(array(
                "status" => 0,
                "mes" => "L'activité " . $activity->getDescription() . " a été ajoutée avec succès."
            ));
        }
    }

    public function deleteAction(Request $request){
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        if($id && is_numeric($id) && $id > 0){
            $activity = $em->getRepository(Activity::class)->find($id);
            if($activity){
                $concernes = $em->getRepository(Concerne::class)->findByActivity($id);
                foreach ($concernes as $concerne){
                    $em->remove($concerne);
                }
                $em->remove($activity);
                $em->flush();
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"L'activité ".$activity->getDescription()." a été supprimée avec succès."
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