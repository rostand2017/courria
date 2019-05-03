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
use HomeBundle\Entity\Concert;
use HomeBundle\Entity\Image;
use HomeBundle\Entity\InfoProduct;
use HomeBundle\Entity\Product;
use HomeBundle\Entity\Salle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ConcertController extends Controller
{

    public function indexAction(Request $request){

        $page = ( $request->query->get("page") )?$request->query->get("page") : 1;
        $nbPerPage = 2;
        $em = $this->getDoctrine()->getManager();
        $nbConcert = $em->getRepository(Concert::class)
                        ->countDateConcert($page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"));
        $concerts = $em->getRepository(Concert::class)
                        ->getSearchDateConcert($page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"));
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

        $name = $post->get("name");
        $description = $post->get("description");
        $price = $post->get("price");
        $color = $post->get("color");
        $reduction = (int) $post->get("reduction");

        $category = $post->get("category");

        // pour chaque taille, on définit une qte
        $number1 = (int) $post->get("number1");
        $number2 = (int) $post->get("number2");
        $number3 = (int) $post->get("number3");


        $cat = $em->getRepository("HomeBundle\Entity\Category")->findOneBy(['category'=>$category]);

        // on modifie un produit pour une taille donnée
        if( !empty($post->get('id')) && (int)$post->get('id') > 0 ){

            $id = $post->get("id");
            $product = $em->getRepository("HomeBundle\Entity\Product")->find($id);
            $imageAccepted = array("jpg", "png", "jpeg");
            if($product && !empty($name) && !empty($description) && !empty($price)
                && $cat && $number1 >=0 && $number2 >=0 && $number3 >=0 && (int) $reduction >= 0
            )
            {
                //$planDirectory = $this->getParameter("plan_directory");
                $imageDirectory = $this->getParameter("image_directory");
                $product->setName($name);
                $product->setDescription($description);
                $product->setPrice($price);
                $product->setColor($color);
                $product->setReduction($reduction);

                $infoProducts = $product->getInfoProduct();
                foreach ($infoProducts as $index=>$infoProduct){
                    switch ($infoProduct->getSize()){
                        case self::PRODUCT_L:
                            $infoProducts[$index]->setNumber($number1);
                        break;
                        case self::PRODUCT_XL:
                            $infoProducts[$index]->setNumber($number2);
                        break;
                        case self::PRODUCT_XXL:
                            $infoProducts[$index]->setNumber($number3);
                        break;
                    }
                }

                $images = $product->getImages();
                if($file->get("image1") && in_array($file->get("image1")->guessExtension(), $imageAccepted) ){

                    $image1 = $file->get("image1");
                    $firstImage = $images[0];
                    $oldImagePath = $imageDirectory.'/'.$firstImage->getImage();
                    $fileName = $this->getUniqueFileName().'.'.$image1->guessExtension();
                    $image1->move($imageDirectory, $fileName);
                    if(file_exists($oldImagePath))
                        unlink($oldImagePath);
                    $oldThumbnailPath = $this->getParameter("thumbnail_directory").'/'.$firstImage->getThumbnail();
                    $thumb = new Thumbnails();
                    $thumb->createThumbnail($imageDirectory."/".$fileName, $this->getParameter("thumbnail_directory")."/".$fileName,400);
                    $firstImage->setThumbnail($fileName);
                    if(file_exists($oldThumbnailPath))
                        unlink($oldThumbnailPath);
                    $firstImage->setImage($fileName);
                }

                if($file->get("image2") && in_array($file->get("image2")->guessExtension(), $imageAccepted)){

                    $image2 = $file->get("image2");
                    $secondImage = $images[1];
                    $oldImagePath = $imageDirectory.'/'.$secondImage->getImage();
                    $fileName = $this->getUniqueFileName().'.'.$image2->guessExtension();
                    $image2->move($imageDirectory, $fileName);
                    if(file_exists($oldImagePath))
                        unlink($oldImagePath);
                    $secondImage->setImage($fileName);
                }

                if($file->get("image3") && in_array($file->get("image3")->guessExtension(), $imageAccepted)){

                    $image3 = $file->get("image3");
                    $thirdImage = $images[2];
                    $oldImagePath = $imageDirectory.'/'.$thirdImage->getImage();
                    $fileName = $this->getUniqueFileName().'.'.$image3->guessExtension();
                    $image3->move($imageDirectory, $fileName);
                    if(file_exists($oldImagePath))
                        unlink($oldImagePath);
                    $thirdImage->setImage($fileName);
                }

                $product->setCategory($cat);
                $em->persist($product);
                foreach ($infoProducts as $infoProduct){
                    $em->persist($infoProduct);
                }
                (isset($firstImage))?$em->persist($firstImage):false;
                (isset($secondImage))?$em->persist($secondImage):false;
                (isset($thirdImage))?$em->persist($thirdImage):false;
                $em->flush();
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"Vêtement enregistrée avec succès"
                ));
            }else{
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Renseignez tous les champs requis"
                ));
            }

        }else{

            $product = new Product();

            $imageAccepted = array("jpg", "png", "jpeg");
            if(
                !empty($name) && !empty($description) && !empty($price) && $reduction >=0 && !empty($category)
                && $cat && $number1 >=0 && $number2 >=0 && $number3 >=0 && !empty($file->get("image1"))
                && !empty($file->get("image2")) && !empty($file->get("image3"))
                && in_array($file->get('image1')->guessExtension(), $imageAccepted)
                && in_array($file->get('image2')->guessExtension(), $imageAccepted)
                && in_array($file->get('image3')->guessExtension(), $imageAccepted)
            )
            {
                $imageDirectory = $this->getParameter("image_directory");
                $product->setName($name);
                $product->setDescription($description);
                $product->setPrice($price);
                $product->setReduction($reduction);

                (isset($color))?$product->setColor($color):false;

                $infoProduct1 = new InfoProduct($number1, self::PRODUCT_L, $product);
                $infoProduct2 = new InfoProduct($number2, self::PRODUCT_XL, $product);
                $infoProduct3 = new InfoProduct($number3, self::PRODUCT_XXL, $product);

                $images = array("image1"=>$file->get("image1"), "image2"=>$file->get("image2"), "image3"=>$file->get("image3"));
                foreach ($images as $value=>$image){
                    $fileName = $this->getUniqueFileName().'.'.$image->guessExtension();
                    $image->move($imageDirectory, $fileName);
                    $thumb = new Thumbnails();
                    $thumb->createThumbnail($imageDirectory."/".$fileName, $this->getParameter("thumbnail_directory")."/".$fileName,400);

                    $image1 = new Image($fileName, $fileName);
                    $image1->setProduct($product);
                    $product->addImage($image1);
                }

                $product->setCategory($cat);
                $product->addInfoProduct($infoProduct1);
                $product->addInfoProduct($infoProduct2);
                $product->addInfoProduct($infoProduct3);
                $em->persist($product);
                $em->flush();
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"Produit enregistré avec succès"
                ));
            }else{
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Renseignez tous les champs requis!"
                ));
            }
        }
    }

    public function deleteAction(Concert $concert){

        $em = $this->getDoctrine()->getManager();
        $image = $concert->getAffiche();
        $em->remove($concert);
        $em->flush();

        unlink($this->getParameter("image_directory")."/".$image);
        unlink($this->getParameter("thumbnail_directory")."/".$image );
        return new JsonResponse(array(
            "status"=>0,
            "mes"=>"Concert supprimé avec succès"
        ));
    }

    public function changeAfficheAction(Request $request){
        $id = $request->request->get('id');
        $affiche = $request->files->get('affiche');
        $em = $this->getDoctrine()->getManager();
        if($id && $affiche && is_numeric($id)){
            $concert = $em->getRepository(Concert::class)->find($id);
            unlink($this->getParameter("image_directory")."/".$concert->getAffiche());
            unlink($this->getParameter("thumbnail_directory")."/".$concert->getAffiche());
            $imageDirectory = $this->getParameter("image_directory");
            $fileName = $this->getUniqueFileName();
            $affiche->move($imageDirectory, $fileName);
            $concert->setAffiche($fileName);
            $em->persist($concert);
            $em->flush();
        }else{
            return new JsonResponse(["status"=>0, "json"=>"Ajoutez une image dans la zone correspondante"]);
        }
    }

    private function getUniqueFileName(){
        return md5(uniqid());
    }

}