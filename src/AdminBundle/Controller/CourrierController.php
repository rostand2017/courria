<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/8/2018
 * Time: 6:52 PM
 */

namespace AdminBundle\Controller;

use AdminBundle\Entity\Courrier;
use AdminBundle\Entity\Files;
use AdminBundle\Entity\Observation;
use AdminBundle\Entity\Utilisateur;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CourrierController extends Controller
{
    const SEC_CON = "controlleur_secretariat";
    const SEC = "secretariat";
    const CON = "controlleur";
    const SER_SOLDE = "solde_pension";
    const SER_VISA = "visa";
    const SER_BRIGADE = "brigade regionale";
    const SER_SUIVI = "suivi des operations de gestion des matières";
    const SER_ADMIN = "administratif et financier";
    const TRA = "traitement";

    public function indexAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $user = $request->getSession()->get("user");
        if($user->getFonction()== AccountController::$USER_TYPE['CONTROLLER_TYPE']){
            $courriers = $em->getRepository(Courrier::class)->findBy(["position"=>self::CON], ["dateexpedition"=>"desc"]);
        }else if($user->getFonction()== AccountController::$USER_TYPE['CHEF_TYPE']){
            $courriers = $em->getRepository(Courrier::class)->findBy(["position"=>$user->getService()], ["dateexpedition"=>"desc"]);
        }else{
            $courriers = $em->getRepository(Courrier::class)->findBy([], ["position"=>"asc", "dateexpedition"=>"desc"]);
        }

        return $this->render('AdminBundle:Courrier:index.html.twig', array(
            "courriers" => $courriers
        ));
    }

    public function nottreatAction(Request $request){
        $user = $request->getSession()->get("user");
        if($user->getFonction() != AccountController::$USER_TYPE["SECRETAIRE_TYPE"])
            return $this->redirectToRoute("admin_homepage");
        $em = $this->getDoctrine()->getManager();
        $courriers1 = $em->getRepository(Courrier::class)->findBy([], ["dateexpedition"=>"desc"]);
        $courriers = [];
        foreach ($courriers1 as $courrier){
            if($this->isNotTreat($courrier))
                array_push($courriers, $courrier);
        }
        return $this->render('AdminBundle:Courrier:nottreat.html.twig', array(
            "courriers" => $courriers
        ));
    }

    public function isNotTreat(Courrier $courrier){
        $observations = $courrier->getObservation();
        if($observations->isEmpty())
            return false;
        else{
            if($observations->get($observations->count()-1)->isTraite())
                return false;
            else
                return true;
        }
    }

    public function isArchive(Courrier $courrier){
        $observations = $courrier->getObservation();
        if($observations->isEmpty())
            return false;
        else{
            if($observations->get($observations->count()-1)->isTraite())
                return true;
            else
                return false;
        }
    }

    public function archivesAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $courriers1 = $em->getRepository(Courrier::class)->findBy([], ["dateexpedition"=>"desc"]);
        $courriers = [];
        foreach ($courriers1 as $courrier){
            if($this->isArchive($courrier))
                array_push($courriers, $courrier);
        }
        return $this->render('AdminBundle:Courrier:archives.html.twig', array(
            "courriers" => $courriers
        ));
    }

    public function detailsAction(Courrier $courrier){
        return $this->render('AdminBundle:Courrier:details.html.twig', array(
            "courrier" => $courrier
        ));
    }

    public function addAction(Request $request){
        $user = $request->getSession()->get("user");
        if($user->getFonction() != AccountController::$USER_TYPE["SECRETAIRE_TYPE"])
            return new JsonResponse(array("status"=>1, "mes"=>"Vous ne pouvez pas effectuer cette opération"), 403);

        $expeditieur = $request->request->get("expediteur");
        $objet = $request->request->get("objet");
        $id = $request->request->get("id");

        $em = $this->getDoctrine()->getManager();

        if($objet == ''){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Renseignez l'objet"
            ));
        }

        if(!$expeditieur && $expeditieur == ''){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Renseignez le nom de l'expeditieur"
            ));
        }

        if($id > 0){
            $courrier = $em->getRepository(Courrier::class)->find($id);
            if($courrier){
                $courrier->setObjet($objet);
                $courrier->setExpediteur($expeditieur);
                $courrier->setPosition(self::CON);
                $em->persist($courrier);
                $em->flush();

                return new JsonResponse(array(
                    "status" => 0,
                    "mes" => "Le courrier de " . $courrier->getExpediteur() . " a été modifié avec succès."
                ));
            }else{
                return new JsonResponse(array(
                    "status"=>1,
                    "mes"=>"Une erreur est survenue."
                ));
            }
        }else {
            $user = $em->getRepository(Utilisateur::class)->find($user->getId());
            $courrier = new Courrier();
            $courrier->setObjet($objet);
            $courrier->setExpediteur($expeditieur);
            $courrier->setUtilisateur($user);
            $courrier->setPosition(self::CON);
            if(!empty($request->files->get("files")))
                $courrier->setFiles($this->saveFiles($request->files->get("files"), $courrier));

            $em->persist($courrier);
            $em->flush();
            return new JsonResponse(array(
                "status" => 0,
                "mes" => "Le courrier de " . $courrier->getExpediteur() . " a été ajouté avec succès."
            ));
        }
    }

    /**
     * Saved files in the disk
     * @param $files
     * @param $courrier
     * @return ArrayCollection
     */
    public function saveFiles($files, $courrier){
        $filesDirectory = $this->getParameter("files_directory");
        $filesEnd = new ArrayCollection();
        foreach ($files as $file ){
            $filename = $this->getUniqueFileName().".".$file->guessExtension();
            $file->move($filesDirectory, $filename);
            $_file = new Files($filename);
            $_file->setCourrier($courrier);
            $filesEnd->add($_file);
        }
        return $filesEnd;
    }

    public function setServiceAction(Request $request, Courrier $courrier){
        $user = $request->getSession()->get("user");
        if($user->getFonction() != AccountController::$USER_TYPE["CONTROLLER_TYPE"])
            return new JsonResponse(array("status"=>1, "mes"=>"Vous ne pouvez pas effectuer cette opération"), 403);
        $em = $this->getDoctrine()->getManager();
        $service = $request->request->get("service");
        if($service == ''){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Renseignez le service chargé de traiter le dossier."
            ));
        }
        $courrier->setService($service);
        $courrier->setPosition(self::SEC_CON);
        $em->persist($courrier);
        $em->flush();
        return new JsonResponse(array(
            "status"=>0,
            "mes"=>"Courrier affecté avec succès au service $service"
        ));
    }

    public function transfertToServiceAction(Request $request, Courrier $courrier){
        $user = $request->getSession()->get("user");
        if($user->getFonction() == AccountController::$USER_TYPE["CONTROLLER_TYPE"])
            return new JsonResponse(array("status"=>1, "mes"=>"Vous ne pouvez pas effectuer cette opération"), 403);
        $em = $this->getDoctrine()->getManager();

        if($user->getFonction() == AccountController::$USER_TYPE["SECRETAIRE_TYPE"])
            $courrier->setPosition($courrier->getService());
        else
            $courrier->setPosition($courrier->getService()."_".self::SEC);

        $em->persist($courrier);
        $em->flush();
        return new JsonResponse(array(
            "status"=>0,
            "mes"=>"Le courrier a été transferé avec succès"
        ));
    }

    public function treatCourrierAction(Request $request, Courrier $courrier){
        $user = $request->getSession()->get("user");
        if($user->getFonction() == AccountController::$USER_TYPE["SECRETAIRE_TYPE"])
            return new JsonResponse(array("status"=>1, "mes"=>"Vous ne pouvez pas effectuer cette opération"), 403);

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Utilisateur::class)->find($user->getId());
        $libelle = $request->request->get("libelle");
        $observation = $request->request->get("observation");
        $traite = $request->request->get("traite");
        if($libelle == '' || $observation){
            return new JsonResponse(array(
                "status"=>1,
                "mes"=>"Renseignez le libellé et une observation"
            ));
        }
        $observation = new Observation();
        $observation->setService($user->getService());
        $observation->setUtilisateur($user);
        $observation->setLibelle($libelle);
        $observation->setObservation($observation);
        $observation->setTraite($traite);

        $courrier->setPosition(self::SEC."_".$user->getService());
        $observation->setCourrier($courrier);

        $em->persist($observation);
        $em->flush();
        return new JsonResponse(array(
            "status"=>0,
            "mes"=>"Courrier transféré au secretariat avec succès"
        ));
    }

    public function deleteAction(Request $request, Courrier $courrier){
        $user = $request->getSession()->get("user");
        if($user->getFonction() != AccountController::$USER_TYPE["SECRETAIRE_TYPE"])
            return new JsonResponse(array("status"=>1, "mes"=>"Vous ne pouvez pas effectuer cette opération"), 403);

        $em = $this->getDoctrine()->getManager();
        if($courrier){
            $this->removeFiles($courrier->getFiles());
            $em->remove($courrier);
            try{
                $em->flush();
                return new JsonResponse(array(
                    "status"=>0,
                    "mes"=>"Le courrier a été supprimé avec succès."
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

    /**
     * Delete a files in the disk
     * @param $files ArrayCollection
     */
    public function removeFiles($files){
        foreach ($files as $file){
            if(file_exists($this->getParameter("files_directory").$file->getPath()))
                unlink($this->getParameter("files_directory").$file->getPath());
        }
    }

    public function setObservationAction(Request $request, Courrier $courrier){
        $user = $request->getSession()->get('user');
        if($user->getFonction() != AccountController::$USER_TYPE["CHEF_TYPE"])
            return new JsonResponse(array("status"=>1, "mes"=>"Vous ne pouvez pas effectuer cette opération"), 403);
        $em = $this->getDoctrine()->getManager();
        $libelle = $request->request->get("libelle");
        $observations = $request->request->get("observation");
        $traite = $request->request->get("traite")=="on"?true:false;
        if(!$libelle || !$observations){
            return new JsonResponse(array("status"=>1, "mes"=>"Renseignez correctement les différents champs"), 403);
        }

        $user = $em->getRepository(Utilisateur::class)->find($user->getId());
        $observation = new Observation();
        $observation->setLibelle($libelle);
        $observation->setObservation($observations);
        $observation->setService($courrier->getService());
        $observation->setTraite($traite);
        $observation->setUtilisateur($user);
        $courrier->setPosition($courrier->getService().'_'.self::SEC);
        $observation->setCourrier($courrier);
        $em->persist($observation);
        $em->flush();
        return new JsonResponse(array("status"=>0, "mes"=>"Observation ajoutée avec succès"), 200);
    }

    private function getUniqueFileName(){
        return md5(uniqid());
    }

}