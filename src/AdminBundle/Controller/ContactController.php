<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/8/2018
 * Time: 6:52 PM
 */
namespace AdminBundle\Controller;

use HomeBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{

    public function indexAction(Request $request){

        $page = ( $request->query->get("page") )?$request->query->get("page") : 1;
        $nbPerPage = 10;
        $em = $this->getDoctrine()->getManager();
        $nbContact = $em->getRepository(Contact::class)
            ->countByDateAndName($page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"), $request->query->get("search"));
        $contacts = $em->getRepository(Contact::class)
            ->getByDateAndName($page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"), $request->query->get("search"));

        $nbPage = ceil($nbContact / $nbPerPage);
        return $this->render('AdminBundle:Commentaire:index.html.twig', array(
            "contacts" => $contacts,
            "page" => $page,
            "nbPage" => $nbPage,
            "nbContact" => $nbContact,
        ));
    }

    public function deleteAction(Request $request){
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        if($id && is_numeric($id) && $id > 0){
            $contact = $em->getRepository(Contact::class)->find($id);
            if($contact){
                $em->remove($contact);
                $em->flush();
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"Le commentaire ".$contact->getMessage()." a été supprimé succès."
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