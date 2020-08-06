<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/8/2018
 * Time: 6:52 PM
 */

namespace AdminBundle\Controller;

use AdminBundle\Entity\Facture;
use AdminBundle\Entity\Factureproduit;
use AdminBundle\Entity\Produit;
use AdminBundle\Entity\Stock;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FactureController extends Controller
{

    public function indexAction(Request $request){
        $page = ( $request->query->get("page") )?$request->query->get("page") : 0;
        $nbPerPage = 10;
        $em = $this->getDoctrine()->getManager();
        $factures = $em->getRepository(Facture::class)->findBy([], ["createdat"=>"desc"], $nbPerPage, $page*$nbPerPage);
        $produits = $em->getRepository(Produit::class)->findAll();
        return $this->render('AdminBundle:Produit:factures.html.twig', compact("factures", "produits"));
    }

    public function addAction(Request $request){
        $user = $request->getSession()->get("user");
        if($user->getFonction() == AccountController::$USER_TYPE["SECRETAIRE_TYPE"] || $user->isBloque())
            return new JsonResponse(array("status"=>1, "mes"=>"Vous ne pouvez pas effectuer cette opération"), 403);

        $quantite = $request->get("quantite");
        $nomClient = $request->get("nomClient");
        $produitId = $request->get("produit");
        $factureId = $request->get("id");
        $factureDate = $request->get("date");

        $em = $this->getDoctrine()->getManager();

        if($factureId != ''){
            $facture = $em->getRepository(Facture::class)->find($factureId);
            $facture->setCreatedat(new \DateTime($factureDate));
            $factureProduit = $facture->getFatureProduit()[0];
        }

        if(!$quantite || $quantite < 0){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Renseignez une quantité supérieure à 0"
            ));
        }

        $produit = $em->getRepository(Produit::class)->find($produitId);
        if(!$produit){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Renseignez correctement un produit"
            ));
        }

        $quantiteDispo = 0;
        $stocks = $em->getRepository(Stock::class)->findBy(['produit'=>$produitId]);
        foreach ($stocks as $stock){
            $quantiteDispo += $stock->getQuantite();
        }

        if($factureId != ''){
            if($produit->getId() != $factureProduit->getProduit()->getId() && $quantite > $quantiteDispo){
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"La quantité restante pour ce produit est ".($quantiteDispo)
                ));
            }
            if($quantite > $quantiteDispo + $factureProduit->getQuantite()){
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"La quantité restante pour ce produit est ".($quantiteDispo + $factureProduit->getQuantite())
                ));
            }
        }
        if($factureId == '' && $quantite > $quantiteDispo){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"La quantité restante pour ce produit est ".$quantiteDispo
            ));
        }

        if(!$nomClient || strlen($nomClient) < 3){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Renseignez correctement le nom du client"
            ));
        }
        if($factureId != ''){
            if($produit->getId() != $factureProduit->getProduit()->getId()){
                $oldStock = $em->getRepository(Stock::class)->findOneBy(["quantite"=>(-$factureProduit->getQuantite()), "produit"=>$factureProduit->getProduit()->getId()]);
                $stock = new Stock();
                $stock->setProduit($produit);
                $stock->setQuantite(-$quantite);
                $em->remove($oldStock);
            }else{
                //$stock = new Stock();
                $stock = $em->getRepository(Stock::class)->findOneBy(["quantite"=> (-$factureProduit->getQuantite()),
                    "produit"=>$produit->getId()]);
                $stock = $em->getRepository(Stock::class)->find($stock->getId());
                $stock->setProduit($produit);
                $stock->setQuantite(-$quantite);
                //$stock->setQuantite($quantite - $stock->getQuantite());
            }
        }else{
            $facture = new Facture();
            $factureProduit = new Factureproduit();
            $stock = new Stock();
            $stock->setProduit($produit);
            $stock->setQuantite(-$quantite);
        }

        $facture->setNomclient($nomClient);
        $factureProduit->setQuantite($quantite);
        $factureProduit->setFacture($facture);
        $factureProduit->setProduit($produit);

        $em->persist($factureProduit);
        $em->persist($stock);

        $message = $factureId == ''? "Facture ajoutée avec succès" : "Facture modifiée avec succès";
        try{
            $em->flush();
            return new JsonResponse(array(
                "status" => 0,
                "mes" => $message
            ));
        }catch (\Exception $e){
            return new JsonResponse(array(
                "status" => 1,
                "mes" => $e->getMessage()
            ));
        }
    }


    public function deleteAction(Request $request, Facture $facture){
        $user = $request->getSession()->get("user");
        if($user->getFonction() == AccountController::$USER_TYPE["SECRETAIRE_TYPE"] || $user->isBloque())
            return new JsonResponse(array("status"=>1, "mes"=>"Vous ne pouvez pas effectuer cette opération"), 403);

        if($facture){
            $em = $this->getDoctrine()->getManager();
            $stock = new Stock();
            $stock->setProduit($facture->getFatureProduit()[0]->getProduit());
            $stock->setQuantite($facture->getFatureProduit()[0]->getQuantite());
            $em->persist($stock);
            $em->remove($facture);
            $em->flush();
            return new JsonResponse(array(
                "status"=>0,
                "mes"=>"La facture a été supprimée avec succès."
            ));
        }else{
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Une erreur est survenue"
            ));
        }
    }
}