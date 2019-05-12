<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/8/2018
 * Time: 6:52 PM
 */
namespace AdminBundle\Controller;

use HomeBundle\Entity\Commentaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CommentaireController extends Controller
{

    public function indexAction(Request $request){

        $page = ( $request->query->get("page") )?$request->query->get("page") : 1;
        $nbPerPage = 10;
        $em = $this->getDoctrine()->getManager();
        $nbCommentaire = $em->getRepository(Commentaire::class)
            ->countByDateAndName($page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"), $request->query->get("search"));
        $commentaires = $em->getRepository(Commentaire::class)
            ->getByDateAndName($page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"), $request->query->get("search"));

        $nbPage = ceil($nbCommentaire / $nbPerPage);
        return $this->render('AdminBundle:Commentaire:index.html.twig', array(
            "commentaires" => $commentaires,
            "page" => $page,
            "nbPage" => $nbPage,
            "nbCommentaire" => $nbCommentaire,
        ));
    }

    public function deleteAction(Request $request){
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        if($id && is_numeric($id) && $id > 0){
            $commentaire = $em->getRepository(Commentaire::class)->find($id);
            if($commentaire){
                $em->remove($commentaire);
                $em->flush();
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"Le commentaire ".$commentaire->getMessage()." a été supprimé succès."
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