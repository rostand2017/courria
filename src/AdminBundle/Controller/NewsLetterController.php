<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/19/2018
 * Time: 4:08 PM
 */

namespace AdminBundle\Controller;


use HomeBundle\Entity\Client;
use HomeBundle\Entity\NewsLetter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NewsLetterController extends Controller
{

    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        $newsletter = $em->getRepository(NewsLetter::class)->findAll();
        $nbNewsletter = count($newsletter);
        return $this->render("AdminBundle:Client:newsletter.html.twig", array(
            "newsletter" => $newsletter,
            "nbNewsletter" => $nbNewsletter,
        ));
    }

    public function sentAction(Request $request){
        $post = $request->request;
        $message = $post->get("message");
        if( !empty( trim($message) ) )
        {
            $em = $this->getDoctrine()->getManager();
            $emails = $em->getRepository(NewsLetter::class)->findAll();
            $clients = $em->getRepository(Client::class)->findAll();
            if($emails || $clients){
                foreach ($emails as $email){
                    $salutation = 'Salut '.$email->getNom();
                    $mes = (new \Swift_Message($message))
                            ->setFrom('contact@concert.com')
                            ->setTo($email->getEmail())
                            ->setBody(
                                $this->renderView(
                                    'Email/diffusion.html.twig',
                                    array('message' => $message, 'salutation'=>$salutation)
                                ),
                                "text/html"
                            );
                    $this->get('mailer')->send($mes);
                }
                foreach ($clients as $client){
                    if($client->getEmail()){
                        $salutation = 'Salut '.$client->getNom();
                        $mes = (new \Swift_Message($message))
                            ->setFrom('contact@concert.com')
                            ->setTo($client->getEmail())
                            ->setBody(
                                $this->renderView(
                                    'Email/diffusion.html.twig',
                                    array('message' => $message, 'salutation' => $salutation)
                                ),
                                "text/html"
                            );
                        $this->get('mailer')->send($mes);
                    }
                }
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"Message envoyé avec succès"
                ));
            }else{
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Aucune souscription aux newsletters !"
                ));
            }
        }
        return new JsonResponse(array(
            "status"=>1,
            "mes"=>"Renseignez le champs message"
        ));
    }

    public function deleteAction(NewsLetter $newsLetter){
        $em = $this->getDoctrine()->getManager();
        $em->remove($newsLetter);
        $em->flush();
        $this->redirectToRoute("admin_newsletter");
    }
}