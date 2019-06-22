<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/8/2018
 * Time: 6:52 PM
 */

namespace AdminBundle\Controller;

use HomeBundle\Entity\Concerne;
use HomeBundle\Entity\Influencer;
use HomeBundle\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class InfluencerController extends Controller
{

    public function indexAction(Request $request){

        $page = ( $request->query->get("page") )?$request->query->get("page") : 1;
        $nbPerPage = 10;
        $em = $this->getDoctrine()->getManager();
        $nbInfluencer = $em->getRepository(Influencer::class)
            ->countByDateAndName($page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"), $request->query->get("search"));
        $influencers = $em->getRepository(Influencer::class)
            ->getByDateAndName($page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"), $request->query->get("search"));

        $nbPage = ceil($nbInfluencer / $nbPerPage);
        return $this->render('AdminBundle:Influencer:index.html.twig', array(
            "influencers" => $influencers,
            "page" => $page,
            "nbPage" => $nbPage,
            "nbInfluencer" => $nbInfluencer,
        ));
    }

    public function deleteAction(Request $request){
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        if($id && is_numeric($id) && $id > 0){
            $influencer = $em->getRepository(Influencer::class)->find($id);
            if($influencer){
                $em->remove($influencer);
                $em->flush();
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"L'utilisateur ".$influencer->getName()." a supprimé du catalogue des influenceurs"
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

    public function seeAction(Influencer $influencer){
        $em = $this->getDoctrine();
        $photos = $em->getRepository(Photo::class)->findByInfluencer($influencer->getId());
        $concernes = $em->getRepository(Concerne::class)->findByInfluencer($influencer->getId());
        return $this->render('AdminBundle:Influencer:single_influencer.html.twig', array(
            "influencer" => $influencer,
            "concernes" => $concernes,
            "photos" => $photos,
        ));
    }

    private function getUniqueFileName(){
        return md5(uniqid());
    }

}