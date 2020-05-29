<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/8/2018
 * Time: 6:52 PM
 */

namespace AdminBundle\Controller;

use AdminBundle\Entity\Produit;
use AdminBundle\Entity\Stock;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class StockController extends Controller
{

    public function indexAction(Produit $produit){

        $produit->setStocks(array_reverse(iterator_to_array($produit->getStocks())));
        return $this->render('AdminBundle:Produit:stock.html.twig', array(
            "produit" => $produit
        ));
    }

    public function addAction(Request $request, Produit $produit){
        $user = $request->getSession()->get("user");
        if($user->getFonction() == AccountController::$USER_TYPE["SECRETAIRE_TYPE"] || $user->isBloque())
            return new JsonResponse(array("status"=>1, "mes"=>"Vous ne pouvez pas effectuer cette opération"), 403);

        $quantite = $request->get("quantite");
        $em = $this->getDoctrine()->getManager();

        if(!$quantite || $quantite < 0){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Renseignez une quantité supérieure à 0"
            ));
        }
        if(!$produit){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Une erreur est survenue"
            ));
        }
        $stock = new Stock();
        $stock->setProduit($produit);
        $stock->setQuantite($quantite);
        $em->persist($stock);
        $em->flush();
        return new JsonResponse(array(
            "status" => 0,
            "mes" => "Nouveau stock ajouté avec succès."
        ));
    }

    public function modifyAction(Request $request, Stock $stock){
        $user = $request->getSession()->get("user");
        if($user->getFonction() == AccountController::$USER_TYPE["SECRETAIRE_TYPE"] || $user->isBloque())
            return new JsonResponse(array("status"=>1, "mes"=>"Vous ne pouvez pas effectuer cette opération"), 403);

        $quantite = $request->get("quantite");
        $em = $this->getDoctrine()->getManager();

        if(!$quantite || $quantite < 0){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Renseignez une quantité supérieure à 0"
            ));
        }

        if($stock->getQuantite() < 0){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Vous ne pouvez pas modifier ce stock"
            ));
        }

        if($stock){
            $stock->setQuantite($quantite);
            $em->persist($stock);
            $em->flush();
            return new JsonResponse(array(
                "status" => 0,
                "mes" => "Le stock a été modifié avec succès."
            ));
        }else{
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Une erreur est survenue."
            ));
        }
    }

    public function deleteAction(Request $request, Stock $stock){
        $user = $request->getSession()->get("user");
        if($user->getFonction() == AccountController::$USER_TYPE["SECRETAIRE_TYPE"] || $user->isBloque())
            return new JsonResponse(array("status"=>1, "mes"=>"Vous ne pouvez pas effectuer cette opération"), 403);

        if($stock->getQuantite() < 0){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Vous ne pouvez pas supprimer ce stock"
            ));
        }

        $em = $this->getDoctrine()->getManager();
        if($stock){
            $em->remove($stock);
            $em->flush();
            return new JsonResponse(array(
                "status"=>0,
                "mes"=>"Le stock du produit a été supprimé avec succès."
            ));
        }else{
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Une erreur est survenue"
            ));
        }
    }
}