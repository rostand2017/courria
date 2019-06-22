<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/8/2018
 * Time: 6:52 PM
 */

namespace AdminBundle\Controller;

use AdminBundle\Classes\Thumbnails;
use HomeBundle\Entity\Artiste;
use HomeBundle\Entity\Client;
use HomeBundle\Entity\Concert;
use HomeBundle\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class InfluencerController extends Controller
{

    public function indexAction(Request $request){

        $page = ( $request->query->get("page") )?$request->query->get("page") : 1;
        $nbPerPage = 10;
        $em = $this->getDoctrine()->getManager();
        $nbClient = $em->getRepository(Client::class)
            ->countByDateAndName($page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"), $request->query->get("search"));
        $clients = $em->getRepository(Client::class)
            ->getByDateAndName($page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"), $request->query->get("search"));

        $nbPage = ceil($nbClient / $nbPerPage);
        return $this->render('AdminBundle:Client:index.html.twig', array(
            "clients" => $clients,
            "page" => $page,
            "nbPage" => $nbPage,
            "nbClient" => $nbClient,
        ));
    }


    public function editAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $post = $request->request;
        $action = $post->get("action");
        $id = $post->get("id");
        $nom = $post->get("nom");
        $prenom = $post->get("prenom");

        if($action && $action == "edit"){
            if( is_numeric($id) && $id >0 && $nom != '' && $prenom != '' )
            {
                $client = $em->getRepository(Client::class)->find($id);
                if($client){
                    $client->setNom($nom);
                    $client->setPrenom($prenom);
                    $em->persist($client);
                    $em->flush();
                    return new JsonResponse(array(
                        "status"=>0,
                        "mes"=>"Les informations de".$nom." ont été modifiée avec succès"
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
            if( $nom != '' && $prenom != '')
            {
                $client = new Client();
                $client->setNom($nom);
                $client->setPrenom($prenom);
                $em->persist($client);
                $em->flush();
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=> "Le client".$nom." a été ajouté avec succès"
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
            $client = $em->getRepository(Client::class)->find($id);
            if($client){
                $em->remove($client);
                $em->flush();
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"L'utilisateur ".$client->getNom()." a supprimé de la liste des utilisateurs"
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

    public function reservationAction(Client $client){
        $em = $this->getDoctrine();
        $reservations = $em->getRepository(Reservation::class)->findBy(['cli'=>$client], ['date'=>'desc']);
        // temporaire: rechercher les concerts disponibles
        $concerts = $em->getRepository(Concert::class)->findAll();
        $nbReservations = count($reservations);
        return $this->render("AdminBundle:Client:reservation.html.twig", array(
            "reservations" => $reservations,
            "nbReservations" => $nbReservations,
            "client" => $client,
            "concerts" => $concerts,
        ));
    }

    private function getUniqueFileName(){
        return md5(uniqid());
    }

}