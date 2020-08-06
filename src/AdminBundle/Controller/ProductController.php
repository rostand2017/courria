<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/8/2018
 * Time: 6:52 PM
 */

namespace AdminBundle\Controller;

use AdminBundle\Entity\Produit;
use AdminBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    public static $PRODUCT_TYPES = ["PC", "Equipements réseaux", "Câbles", "Connecteurs", "Routeurs", "Autres"];
    public static $ACCEPTED_FILES = ["png", "jpg", "jpeg"];

    public function indexAction(Request $request){

        $page = ( $request->query->get("page") )?$request->query->get("page") : 1;
        $nbPerPage = 10;
        $em = $this->getDoctrine()->getManager();
        $nbProduit = $em->getRepository(Produit::class)
            ->countByDateAndName($page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"), $request->query->get("search"));
        $produits = $em->getRepository(Produit::class)
            ->getByDateAndName($page, $nbPerPage, $request->query->get("begin"), $request->query->get("end"), $request->query->get("search"));

        $nbPage = ceil($nbProduit / $nbPerPage);
        return $this->render('AdminBundle:Produit:index.html.twig', array(
            "produits" => $produits,
            "page" => $page,
            "nbPage" => $nbPage,
            "nbProduit" => $nbProduit,
        ));
    }

    public function addAction(Request $request){
        $user = $request->getSession()->get("user");
        if($user->getFonction() == AccountController::$USER_TYPE["SECRETAIRE_TYPE"] || $user->isBloque())
            return new JsonResponse(array("status"=>1, "mes"=>"Vous ne pouvez pas effectuer cette opération"), 403);

        $nom = $request->request->get("nom");
        $modele = $request->request->get("modele");
        $description = $request->request->get("description");
        $type = $request->request->get("type");
        $fabricant = $request->request->get("fabricant");
        $prix = $request->request->get("prix");
        $id = $request->request->get('id');
        $image = $request->files->get("image");

        $em = $this->getDoctrine()->getManager();

        if($modele == ''){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Renseignez le modèle"
            ));
        }

        if(!$nom && $nom == ''){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Renseignez le nom du produit"
            ));
        }

        if($prix < 0){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Renseignez le prix du produit"
            ));
        }

        if(!in_array($type, self::$PRODUCT_TYPES)){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Renseignez le type du produit"
            ));
        }

        if($id > 0){
            $produit = $em->getRepository(Produit::class)->find($id);
            if($produit){
                $produit->setModel($modele);
                $produit->setNom($nom);
                $produit->setPrix($prix);
                $produit->setType($type);
                $produit->setFabricant($fabricant);
                $produit->setDescription($description);

                if($image){
                    if(!$produit->getImage() || $this->deleteFile($produit->getImage())){
                        try{
                            $produit->setImage($this->saveImage($image));
                        }catch (\Exception $e){
                            return new JsonResponse(array("status" => 1, "mes" => $e->getMessage()));
                        }
                    }else{
                        return new JsonResponse(array("status" => 1, "mes" => "Une erreur est survenue, veuillez reessayer"));
                    }
                }
                $em->persist($produit);
                $em->flush();
                return new JsonResponse(array(
                    "status" => 0,
                    "mes" => "Le produit " . $produit->getNom() . " a été modifié avec succès."
                ));
            }else{
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Une erreur est survenue."
                ));
            }
        }else {
            $user = $em->getRepository(Utilisateur::class)->find($user->getId());
            $produit = new Produit();
            $produit->setNom($nom);
            $produit->setModel($modele);
            $produit->setPrix($prix);
            $produit->setType($type);
            $produit->setFabricant($fabricant);
            $produit->setDescription($description);
            $produit->setUtilisateur($user);
            if($image){
                try{
                    $produit->setImage($this->saveImage($image));
                }catch (\Exception $e){
                    return new JsonResponse(array("status" => 1, "mes" => $e->getMessage()));
                }
            }
            $em->persist($produit);
            $em->flush();
            return new JsonResponse(array(
                "status" => 0,
                "mes" => "Le produit " . $produit->getNom() . " a été ajouté avec succès."
            ));
        }
    }

    public function deleteAction(Request $request, Produit $produit){
        $user = $request->getSession()->get("user");
        if($user->getFonction() == AccountController::$USER_TYPE["SECRETAIRE_TYPE"] || $user->isBloque())
            return new JsonResponse(array("status"=>1, "mes"=>"Vous ne pouvez pas effectuer cette opération"), 403);

        $em = $this->getDoctrine()->getManager();
        if($produit){
            $em->remove($produit);
            try{
                $em->flush();
                if($produit->getImage())
                    $this->deleteFile($produit->getImage());
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"Le produit ".$produit->getNom()." a été supprimée"
                ));
            }catch (\Exception $e){
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

    /**
     *
     * @throws \Exception
     */
    private function saveImage(UploadedFile $uploadedFile){
        if( !in_array($uploadedFile->guessExtension(), self::$ACCEPTED_FILES ) )
            throw new \Exception("Format d'image invalide.", 111);

        $directory = $this->getParameter("image_product");
        $filename = $this->getUniqueFileName().".".$uploadedFile->guessExtension();
        $uploadedFile->move($directory, $filename);
        return $filename;
    }

    private function deleteFile($fileName){
        return unlink($this->getParameter("image_product").$fileName);
    }

}