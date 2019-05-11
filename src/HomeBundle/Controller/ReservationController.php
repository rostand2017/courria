<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/8/2018
 * Time: 6:52 PM
 */

namespace HomeBundle\Controller;

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

    public function indexAction(Request $request){
        if($request->getSession()->get("user")){
            return $this->redirectToRoute('home_reservation_normale');
        }
        return $this->render('HomeBundle:Reservation:index.html.twig', array());
    }

    public function reservationAction(Request $request){
        if(!$request->getSession()->get("user")){
            return $this->redirectToRoute('home_reservation');
        }
        $em = $this->getDoctrine()->getManager();
        $concert = $em->getRepository(Concert::class)->findBy([], ['date'=>'desc'], 1, 0);
        return $this->render('HomeBundle:Reservation:reservation.html.twig',
            array("concert"=>$concert)
        );
    }

    public function reservationDirecteAction(){
        $em = $this->getDoctrine()->getManager();
        $concert = $em->getRepository(Concert::class)->findBy([], ['date'=>'desc'], 1, 0);
        return $this->render('HomeBundle:Reservation:reservation_directe.html.twig',
            array("concert"=>$concert)
        );
    }

    public function editAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $post = $request->request;
        $nom = $post->get("nom");
        $email = $post->get("email");
        $nbPlace = $post->get("nbplace");
        if($request->getSession()->get('user')){
            if( $nbPlace > 0 && $nbPlace <= 5){
                $concert = $em->getRepository(Concert::class)->findBy([], ['date'=>'desc'], 1, 0);
                $reserv = $em->getRepository(Reservation::class)->findBy([], ['date'=>'desc'], 1,0);
                $lastId= ($reserv)?$reserv[0]->getId():1;
                if($concert){
                    $reservation = new Reservation();
                    $reservation->setCon($concert[0]);
                    $reservation->setCli($request->getSession()->get('user'));
                    $reservation->setNbplace($nbPlace);
                    $reservation->setCode(CodeGenerator::generateUniqToken($lastId+1));
                    $em->persist($reservation);
                    $em->flush();
                    $this->sentMail($email, $nom, $reservation->getCode());
                    return new JsonResponse(array(
                        "status"=>0,
                        "mes"=>"Votre réservation a été effectuée avec succès. Un code vous a été envoyé par mail"
                    ));
                }else{
                    return new JsonResponse(array(
                        "status"=>1,
                        "mes"=>"Aucun concert disponible"
                    ));
                }
            }else{
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Le nombre de place doit être compris entre 1 et 5"
                ));
            }
        }else{
            if( $nbPlace > 0 && $nbPlace <= 5 && $nom && $nom != '' && $email && $email!=''){
                $concert = $em->getRepository(Concert::class)->findBy([], ['date'=>'desc'], 1, 0);
                if(!$concert){
                    return new JsonResponse(['status'=>1, 'mes'=>'Aucun concert en cours']);
                }
                $reserv = $em->getRepository(Reservation::class)->findBy([], ['date'=>'desc'], 1,0);
                $lastId= ($reserv)?$reserv[0]->getId():1;
                if($concert){
                    $reservation = new Reservation();
                    $client = new Client();
                    $client->setNom($nom);
                    $client->setEmail($email);
                    $reservation->setCon($concert[0]);
                    $reservation->setCli($client);
                    $reservation->setNbplace($nbPlace);
                    $reservation->setCode(CodeGenerator::generateUniqToken($lastId+1));
                    $em->persist($reservation);
                    $em->flush();
                    $this->sentMail($email, $nom, $reservation->getCode());
                    return new JsonResponse(array(
                        "status"=>0,
                        "mes"=>"Votre réservation a été effectuée avec succès.  Un code vous a été envoyé par mail"
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

    private function sentMail($email, $nom, $code){
        $mes = (new \Swift_Message("Code Réservation"))
            ->setFrom('contact@concert.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'Email/code_reservation.html.twig',
                    array('code' => $code, 'nom'=>$nom)
                ),
                "text/html"
            );
        $this->get('mailer')->send($mes);
    }
}