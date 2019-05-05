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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ArtisteController extends Controller
{


    public function indexAction(){
        $artistes = $this->getDoctrine()->getRepository(Artiste::class)->findAll();
        $nbArtiste = count($artistes);
        return $this->render("AdminBundle:Concert:artiste.html.twig", array(
            "artistes" => $artistes,
            "nbArtiste" => $nbArtiste
        ));
    }

    public function editAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $post = $request->request;
        $action = $post->get("action");
        $id = $post->get("id");
        $nom = $post->get("nom");
        $prenom = $post->get("prenom");
        $dateNaissance = $post->get("dateNaissance");
        $nationalite = $post->get("nationalite");
        $photo = $request->files->get("photo");

        if($action && $action == "edit"){
            if( is_numeric($id) && $id >0 && $nom != '' && $prenom != '' && $dateNaissance != ''  && $nationalite != '' )
            {
                $artiste = $em->getRepository(Artiste::class)->find($id);
                if($artiste){
                    $artiste->setNom($nom);
                    $artiste->setPrenom($prenom);
                    $artiste->setDatenaissance($dateNaissance);
                    $artiste->setNationalite($nationalite);
                    if($photo){
                        $imageAccepted = array("jpg", "png", "jpeg");
                        if(in_array($photo->guessExtension(), $imageAccepted)) {
                            $imageDirectory = $this->getParameter("image_directory");
                            $thumbnailDirectory = $this->getParameter("thumbnail_directory");
                            if(file_exists($imageDirectory."/".$artiste->getPhoto()))
                                unlink($imageDirectory."/".$artiste->getPhoto());
                            if(file_exists($thumbnailDirectory."/".$artiste->getPhoto()))
                                unlink($thumbnailDirectory."/".$artiste->getPhoto());
                            $filename = $this->getUniqueFileName() . "." . $photo->guessExtension();
                            $photo->move($imageDirectory, $filename);
                            $thumb = new Thumbnails();
                            $thumb->createThumbnail($imageDirectory."/".$filename, $thumbnailDirectory."/".$filename, 200);
                            $artiste->setPhoto($filename);
                        }else{
                            return new JsonResponse(array(
                                "status"=>1,
                                "mes"=>"Format d'image incorrecte"
                            ));
                        }
                    }
                    $em->persist($artiste);
                    $em->flush();
                    return new JsonResponse(array(
                        "status"=>0,
                        "mes"=>"Les informations de cette artiste ont été modifiée avec succès"
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
            if( $nom != '' && $prenom != '' && $dateNaissance != ''  && $nationalite != '' && $photo)
            {
                $imageAccepted = array("jpg", "png", "jpeg");
                if(in_array($photo->guessExtension(), $imageAccepted)){
                    $imageDirectory = $this->getParameter("image_directory");
                    $thumbnailDirectory = $this->getParameter("thumbnail_directory");
                    $filename = $this->getUniqueFileName().".".$photo->guessExtension();
                    $photo->move($imageDirectory, $filename);
                    $thumb = new Thumbnails();
                    $thumb->createThumbnail($imageDirectory."/".$filename, $thumbnailDirectory."/".$filename, 200);
                    $artiste = new artiste();
                    $artiste->setNom($nom);
                    $artiste->setPrenom($prenom);
                    $artiste->setDatenaissance($dateNaissance);
                    $artiste->setNationalite($nationalite);
                    $artiste->setPhoto($filename);
                    $em->persist($artiste);
                    $em->flush();
                    return new JsonResponse(array(
                        "status"=>0,
                        "mes"=>"artiste ajoutée avec succès"
                    ));
                }else{
                    return new JsonResponse(array(
                        "status"=>1,
                        "mes"=>"Format d'image incorrecte"
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
            $artiste = $em->getRepository(artiste::class)->find($id);
            if($artiste){
                $em->remove($artiste);
                $em->flush();
                if(file_exists($this->getParameter("image_directory")."/".$artiste->getPhoto()))
                    unlink($this->getParameter("image_directory")."/".$artiste->getPhoto());
                if(file_exists($this->getParameter("thumbnail_directory")."/".$artiste->getPhoto()))
                    unlink($this->getParameter("thumbnail_directory")."/".$artiste->getPhoto() );
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"L'artiste ".$artiste->getNom()." a supprimée de la liste des artistes"
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