<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/19/2018
 * Time: 4:08 PM
 */

namespace AdminBundle\Controller;


use HomeBundle\Entity\Admin;
use HomeBundle\Entity\Client;
use HomeBundle\Entity\Influencer;
use HomeBundle\Entity\Message;
use HomeBundle\Entity\NewsLetter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NewsLetterController extends Controller
{

    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository(Message::class)->findAll();
        $nbMessage = count($messages);
        return $this->render("AdminBundle:Influencer:newsletter.html.twig", array(
            "messages" => $messages,
            "nbMessage" => $nbMessage,
        ));
    }

    public function sentAction(Request $request){
        $post = $request->request;
        $message = $post->get("message");
        if( !empty( trim($message) ) )
        {
            $em = $this->getDoctrine()->getManager();
            $influencers = $em->getRepository(Influencer::class)->findAll();
            if($influencers){
                foreach ($influencers as $influencer){
                    if($influencer->getEmail()){
                        $mes = (new \Swift_Message($message))
                            ->setFrom('contact@concert.com')
                            ->setTo($influencer->getEmail())
                            ->setBody(
                                $this->renderView(
                                    'Email/diffusion.html.twig',
                                    array('message' => $message, 'nom'=>$influencer->getName())
                                ),
                                "text/html"
                            );
                        $this->get('mailer')->send($mes);
                    }
                }
                $em = $this->getDoctrine()->getManager();
                $message_n = new Message();
                $message_n->setMessage($message);
                $message_n->setAdmin($em->getRepository(Admin::class)->find($request->getSession()->get('admin')->getId()) );
                $em->persist($message_n);
                $em->flush();
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"Message envoyé avec succès"
                ));
            }else{
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Aucun influenceur enregistré."
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