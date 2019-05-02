<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/19/2018
 * Time: 4:08 PM
 */

namespace AdminBundle\Controller;


use HomeBundle\Entity\NewsLetter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NewsLetterController extends Controller
{

    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        $newsletter = $em->getRepository(NewsLetter::class)->findAll();
        return $this->render("AdminBundle:Product:newsletter.html.twig", array(
            "newsletter" => $newsletter,
        ));
    }

    public function sentAction(Request $request){
        $post = $request->request;
        $message = $post->get("message");
        if( !empty( trim($message) ) )
        {
            $em = $this->getDoctrine()->getManager();
            $emails = $em->getRepository(NewsLetter::class)->findAll();
            if(!empty($emails)){
                foreach ($emails as $email){
                    // envoie des email
                    $mes = (new \Swift_Message($message))
                            ->setFrom('contact@nopermission.com')
                            ->setTo($email->getEmail())
                            ->setBody(
                                $this->renderView(
                                    'Email/diffusion.html.twig',
                                    array('message' => $message)
                                ),
                                "text/html"
                            );
                    $this->get('mailer')->send($mes);
                }
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"Message envoyé avec succès"
                ));
            }
        }
        return new JsonResponse(array(
            "status"=>1,
            "mes"=>"Renseignez le champs message"
        ));
    }
}