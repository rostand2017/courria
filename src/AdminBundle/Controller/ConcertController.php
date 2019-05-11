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
use HomeBundle\Entity\Category;
use HomeBundle\Entity\Concerne;
use HomeBundle\Entity\Concert;
use HomeBundle\Entity\Image;
use HomeBundle\Entity\InfoProduct;
use HomeBundle\Entity\Product;
use HomeBundle\Entity\Salle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\TwigFilter;

class ConcertController extends Controller
{

    public function indexAction(Request $request){

        $page = ( $request->query->get("page") )?$request->query->get("page") : 1;
        $nbPerPage = 2;
        $em = $this->getDoctrine()->getManager();
        $nbConcert = $em->getRepository(Concert::class)
                        ->countDateConcert($page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"));
        $concerts_temp = $em->getRepository(Concert::class)
                        ->getSearchDateConcert($page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"));
        $concerts = array();
        foreach ($concerts_temp as $concert){
            array_push($concerts, $em->getRepository(Concert::class)->find($concert['id']) );
        }
        $artistes = $em->getRepository(Artiste::class)->findAll();
        $salles =  $em->getRepository(Salle::class)->findAll();

        $nbPage = ceil($nbConcert / $nbPerPage);
        return $this->render('AdminBundle:Concert:index.html.twig', array(
            "concerts" => $concerts,
            "page" => $page,
            "nbPage" => $nbPage,
            "nbConcert" => $nbConcert,
            "salles" => $salles,
            "artistes" => $artistes,
        ));
    }

    public function editAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $post = $request->request;
        $file = $request->files;

        $intitule = $post->get("intitule");
        $description = $post->get("description");
        $prix = $post->get("prix");
        $date = $post->get("date");
        $time = $post->get("time");
        $nbPlace = $post->get("nbPlace");
        $salleId = $post->get("salle");
        $artistes = $post->get("artistes");
        $action = $post->get("action");
        $id = $post->get("id");
        $affiche = $file->get("affiche");

        if($action && $action == "edit" && $id && is_numeric($id) && $id > 0){
            if($intitule != '' && $description != '' && $prix!='' && is_numeric($prix) && $prix > 0
                && $date != '' && $time != '' && $nbPlace!='' && is_numeric($nbPlace) && $nbPlace >0 && $salleId != ''
                && is_numeric($salleId) && $salleId > 0 && $artistes && !empty($artistes)
            ){
                $concert = $em->getRepository(Concert::class)->find($id);
                $concert->setIntitule($intitule);
                $concert->setDescription($description);
                $concert->setPrix($prix);
                $concert->setDate(new \DateTime($date));
                $concert->setHeure(new \DateTime($time));
                $concert->setNbplace($nbPlace);
                $concert->setSal($em->getRepository(Salle::class)->find($salleId));
                foreach ($concert->getConcerne() as $concerne){
                    $em->remove($concerne);
                    $em->flush();
                }
                foreach ($artistes as $artiste){
                    $concerne = new Concerne();
                    $concerne->setConcert($concert);
                    $concerne->setArtiste($em->getRepository(Artiste::class)->find($artiste));
                    $concert->addConcerne($concerne);
                }
                $em->persist($concert);
                $em->flush();
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"Modifications apportées avec succès."
                ));
            }else{
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Renseignez tous les champs requis"
                ));
            }

        }else{
            $imageAccepted = array("jpg", "png", "jpeg");
            if($intitule != '' && $description != '' && $prix!='' && is_numeric($prix) && $prix > 0
                && $date != '' && $time != '' && $nbPlace!='' && is_numeric($nbPlace) && $nbPlace >0 && $salleId != ''
                && is_numeric($salleId) && $salleId > 0 && $affiche && $artistes && !empty($artistes)){
                if(in_array($affiche->guessExtension(), $imageAccepted)){
                    $imageDirectory = $this->getParameter("image_directory");
                    $thumbnailDirectory = $this->getParameter("thumbnail_directory");
                    $filename = $this->getUniqueFileName().".".$affiche->guessExtension();
                    $affiche->move($imageDirectory, $filename);
                    $thumb = new Thumbnails();
                    $thumb->createThumbnail($imageDirectory."/".$filename, $thumbnailDirectory."/".$filename, 200);
                    $concert = new Concert();
                    $concert->setIntitule($intitule);
                    $concert->setDescription($description);
                    $concert->setPrix($prix);
                    $concert->setDate(new \DateTime($date));
                    $concert->setHeure(new \DateTime($time));
                    $concert->setNbplace($nbPlace);
                    $concert->setAffiche($filename);
                    $concert->setSal($em->getRepository(Salle::class)->find($salleId));
                    foreach ($artistes as $artiste){
                        $concerne = new Concerne();
                        $concerne->setConcert($concert);
                        $concerne->setArtiste($em->getRepository(Artiste::class)->find($artiste));
                        $concert->addConcerne($concerne);
                    }
                    $em->persist($concert);
                    $em->flush();
                    return new JsonResponse(array(
                        "status"=>0,
                        "mes"=>"Concert ajouté avec succès."
                    ));
                }else{
                    return new JsonResponse(array(
                        "status"=>1,
                        "mes"=>"Format d'image incorrecte!"
                    ));
                }
            }else{
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Renseignez tous les champs requis"
                ));
            }
        }
    }

    public function deleteAction(Request $request){
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        if($id && is_numeric($id)){
            $concert = $em->getRepository(Concert::class)->find($id);
            $em->remove($concert);
            $em->flush();
            if(file_exists($this->getParameter("image_directory")."/".$concert->getAffiche()))
                unlink($this->getParameter("image_directory")."/".$concert->getAffiche());
            if(file_exists($this->getParameter("thumbnail_directory")."/".$concert->getAffiche()))
                unlink($this->getParameter("thumbnail_directory")."/".$concert->getAffiche() );
            return new JsonResponse(array(
                "status"=>0,
                "mes"=>"Concert supprimé avec succès"
            ));
        }
        return new JsonResponse(array(
            "status"=>1,
            "mes"=>"Une erreur est survenue"
        ));
    }

    public function changeAfficheAction(Request $request){
        $id = $request->request->get('id');
        $affiche = $request->files->get('affiche');
        $em = $this->getDoctrine()->getManager();
        if($id && $affiche && is_numeric($id)){
            $concert = $em->getRepository(Concert::class)->find($id);
            $imageDirectory = $this->getParameter("image_directory");
            $thumbnailDirectory = $this->getParameter("thumbnail_directory");

            if(file_exists($imageDirectory."/".$concert->getAffiche()))
                unlink($imageDirectory."/".$concert->getAffiche());
            if(file_exists($thumbnailDirectory."/".$concert->getAffiche()))
                unlink($thumbnailDirectory."/".$concert->getAffiche() );

            $fileName = $this->getUniqueFileName().".".$affiche->guessExtension();
            $affiche->move($imageDirectory, $fileName);
            $thumb = new Thumbnails();
            $thumb->createThumbnail($imageDirectory."/".$fileName, $thumbnailDirectory."/".$fileName, 200);
            $concert->setAffiche($fileName);
            $em->persist($concert);
            $em->flush();
            return new JsonResponse(["status"=>0, "mes"=>"Image modifiée avec succès."]);
        }else{
            return new JsonResponse(["status"=>1, "mes"=>"Ajoutez une image dans la zone correspondante"]);
        }
    }

    private function getUniqueFileName(){
        return md5(uniqid());
    }

}