<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/8/2018
 * Time: 6:52 PM
 */

namespace AdminBundle\Controller;


use AdminBundle\Classes\Thumbnails;
use HomeBundle\Entity\Category;
use HomeBundle\Entity\Image;
use HomeBundle\Entity\InfoProduct;
use HomeBundle\Entity\Product;
use HomeBundle\Entity\Slide;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SlideController extends Controller
{


    public function indexAction(){
        $slides = $this->getDoctrine()->getRepository("HomeBundle\Entity\Slide")->findAll();
        $nbSlide = count($slides);
        return $this->render("AdminBundle:Product:slides.html.twig", array(
            "slides" => $slides,
            "nbSlide" => $nbSlide
        ));
    }

    public function editAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $post = $request->request;

        $imageAccepted = array("jpg", "png", "jpeg");
        $text = $post->get("text");
        $title = $post->get("title");
        $id = $post->get('id');

        $imageDirectory = $this->getParameter("image_slide_directory");
        if( !empty($id) && (int)$id > 0 ){

            if( !empty( trim($text) ) && !empty( trim($title) ) )
            {
                $slide = $em->getRepository("HomeBundle\Entity\Slide")->find($id);
                $slide->setText($text);
                $slide->setTitle($title);
                $image = $request->files->get("image");
                if($image){
                    if( in_array($image->guessExtension(), $imageAccepted)){
                        $fileName = $this->getUniqueFileName().'.'.$image->guessExtension();
                        $oldImagePath = $imageDirectory."/".$slide->getImage();
                        $slide->setImage($fileName);
                        $image->move($imageDirectory, $fileName);
                    }
                }
                $em->persist($slide);
                $em->flush();
                // on supprime l'ancienne image, si la modification s'est effectuée avec succès
                (isset($oldImagePath))?unlink($oldImagePath):false;
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"Slide enregistré avec succès"
                ));
            }else{
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Renseignez les champs requis avec conformité"
                ));
            }

        }else{
            $image = $request->files->get("image");
            if(!empty( trim($text) ) && !empty( trim($title) ) && !empty($image)
                && in_array($image->guessExtension(), $imageAccepted)){
                $slides = $this->getDoctrine()->getRepository("HomeBundle\Entity\Slide")->findAll();
                if( count($slides) <= 2 ){
                    $slide = new Slide();
                    $slide->setText($text);
                    $slide->setTitle($title);
                    $fileName = $this->getUniqueFileName().'.'.$image->guessExtension();
                    $slide->setImage($fileName);
                    $image->move($imageDirectory, $fileName);
                    $em->persist($slide);
                    $em->flush();
                    return new JsonResponse(array(
                        "status"=>0,
                        "mes"=>"Slide modifié avec succès"
                    ));
                }else{
                    return new JsonResponse(array(
                        "status"=>1,
                        "mes"=>"Le nombre maximale de slide est atteint"
                    ));
                }
            }else{
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Renseignez le champs catégorie"
                ));
            }
        }
    }

    private function getUniqueFileName(){
        return md5(uniqid());
    }

}