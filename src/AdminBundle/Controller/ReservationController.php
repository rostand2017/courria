<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/8/2018
 * Time: 6:52 PM
 */

namespace AdminBundle\Controller;

use AdminBundle\Classes\CodeGenerator;
use AdminBundle\Classes\Thumbnails;
use HomeBundle\Entity\Artiste;
use HomeBundle\Entity\Client;
use HomeBundle\Entity\Concert;
use HomeBundle\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ReservationController extends Controller
{

    public function indexAction(Concert $concert, Request $request){

        $page = ( $request->query->get("page") )?$request->query->get("page") : 1;
        $nbPerPage = 2;
        $em = $this->getDoctrine()->getManager();
        $concerts = $em->getRepository(Concert::class)->findAll();
        $nbReservation = $em->getRepository(Reservation::class)
            ->countByDateAndName($concert->getId(), $page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"), $request->query->get("search"));
        $clients = $em->getRepository(Reservation::class)
            ->getByDateAndName($concert->getId(), $page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"), $request->query->get("search"));

        $nbPage = ceil($nbReservation / $nbPerPage);
        return $this->render('AdminBundle:Reservation:index.html.twig', array(
            "clients" => $clients,
            "page" => $page,
            "concert" => $concert,
            "concerts" => $concerts,
            "nbPage" => $nbPage,
            "nbReservation" => $nbReservation,
        ));
    }


    public function editAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $post = $request->request;
        $action = $post->get("action");
        $id = $post->get("id");
        $idClient = $post->get("idclient");
        $nom = $post->get("nom");
        $concertId = $post->get("concert");
        $nbPlace = $post->get("nbplace");
        $prenom = $post->get("prenom");

        if($action && $action == "edit"){
            if($idClient && is_numeric($idClient) && $id && is_numeric($id) && is_numeric($concertId) && $concertId > 0 && is_numeric($nbPlace) && $nbPlace > 0 && $nbPlace <= 5){
                $client = $em->getRepository(Client::class)->find($idClient);
                $concert = $em->getRepository(Concert::class)->find($concertId);
                $reservation = $em->getRepository(Reservation::class)->find($id);
                if($client && $concert && $reservation){
                    $reservation->setCon($concert);
                    $reservation->setNbplace($nbPlace);
                    $em->persist($reservation);
                    $em->flush();
                    return new JsonResponse(array(
                        "status"=>0,
                        "mes"=>"La reservation de".$client->getNom()." a été modifiée avec succès"
                    ));
                }else{
                    return new JsonResponse(array(
                        "status"=>1,
                        "mes"=>"Une erreur est survenue"
                    ));
                }
            }else if( is_numeric($id) && $id >0 && $nom != '' && $prenom != '' && is_numeric($concertId) && $concertId > 0 && is_numeric($nbPlace) && $nbPlace > 0 && $nbPlace <= 5 )
            {
                $reservation = $em->getRepository(Reservation::class)->find($id);
                $concert = $em->getRepository(Concert::class)->find($concertId);
                if($reservation && $concert){
                    $reservation->getCli()->setNom($nom);
                    $reservation->getCli()->setPrenom($prenom);
                    $reservation->setCon($concert);
                    $reservation->setNbplace($nbPlace);
                    $em->persist($reservation);
                    $em->flush();
                    return new JsonResponse(array(
                        "status"=>0,
                        "mes"=>"La reservation de".$nom." a été modifiée avec succès"
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

            if($idClient && is_numeric($idClient) && is_numeric($concertId) && $concertId > 0 && is_numeric($nbPlace) && $nbPlace > 0 && $nbPlace <= 5){
                $client = $em->getRepository(Client::class)->find($idClient);
                $concert = $em->getRepository(Concert::class)->find($concertId);
                $reserv = $em->getRepository(Reservation::class)->findBy([], ['date'=>'desc'], 1,0);
                $lastId= ($reserv)?$reserv[0]->getId():1;
                if($client && $concert){
                    $reservation = new Reservation();
                    $reservation->setCon($concert);
                    $reservation->setCli($client);
                    $reservation->setNbplace($nbPlace);
                    $reservation->setCode(CodeGenerator::generateUniqToken($lastId+1));
                    $em->persist($reservation);
                    $em->flush();
                    return new JsonResponse(array(
                        "status"=>0,
                        "mes"=>"La reservation de".$client->getNom()." a été créée avec succès."
                    ));
                }else{
                    return new JsonResponse(array(
                        "status"=>1,
                        "mes"=>"Une erreur est survenue"
                    ));
                }
            }else if( $nom != '' && $prenom != '' && is_numeric($concertId) && $concertId > 0 && is_numeric($nbPlace) && $nbPlace > 0 && $nbPlace <= 5 )
            {
                $concert = $em->getRepository(Concert::class)->find($concertId);
                $reserv = $em->getRepository(Reservation::class)->findBy([], ['date'=>'desc'], 1,0);
                $lastId= ($reserv)?$reserv[0]->getId():1;
                if($concert){
                    $reservation = new Reservation();
                    $client = new Client();
                    $client->setNom($nom);
                    $client->setPrenom($prenom);
                    $reservation->setNbplace($nbPlace);
                    $reservation->setCon($concert);
                    $reservation->setCli($client);
                    $reservation->setCode(CodeGenerator::generateUniqToken($lastId+1));
                    $em->persist($reservation);
                    $em->flush();
                    return new JsonResponse(array(
                        "status"=>0,
                        "mes"=> "Reservation de ".$nom." créé avec succès"
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
        }
    }

    public function deleteAction(Request $request){
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        if($id && is_numeric($id) && $id > 0){
            $reservation = $em->getRepository(Reservation::class)->find($id);
            if($reservation){
                $em->remove($reservation);
                $em->flush();
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"La réservation de ".$reservation->getCli()->getNom()." a été annulée avec succès."
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