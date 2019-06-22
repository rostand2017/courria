<?php

namespace HomeBundle\Controller;

use AdminBundle\Classes\Thumbnails;
use HomeBundle\Entity\Activity;
use HomeBundle\Entity\Concerne;
use HomeBundle\Entity\Influencer;
use HomeBundle\Entity\Partnershiptype;
use HomeBundle\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DashBoardController extends Controller
{
    public function profileAction(Request $request)
    {
        $user = $request->getSession()->get('user');
        if($user){
            $em = $this->getDoctrine()->getManager();
            $photos = $em->getRepository(Photo::class)->findBy(['influencer'=>$user->getId()]);
            $concerne = $em->getRepository(Concerne::class)->findBy(['influencer'=>$user->getId()]);
            return $this->render('HomeBundle:Influencer:index.html.twig', array(
                "user" => $user,
                "photos" => $photos,
                "concerne" => $concerne
            ));
        }else{
            return $this->redirectToRoute('home_homepage');
        }
    }
    public function modifyAction(Request $request)
    {
        $user = $request->getSession()->get('user');
        if($user){
            $em = $this->getDoctrine()->getManager();
            $concerne = $em->getRepository(Concerne::class)->findOneBy(['influencer'=>$user->getId()]);
            $partnerShipTypes = $em->getRepository(Partnershiptype::class)->findAll();
            $activities = $em->getRepository(Activity::class)->findAll();
            return $this->render('HomeBundle:Influencer:modify.html.twig', array(
                "user" => $user,
                "concerne" => $concerne,
                "activities" => $activities,
                "partnerShipTypes" => $partnerShipTypes,
                "countries" => AccountController::$countries
            ));
        }else{
            return $this->redirectToRoute('home_homepage');
        }
    }

    public function modifyProfileAction(Request $request){
        $user = $request->getSession()->get("user");
        if(!$user){
            return new JsonResponse(['status'=>1, 'mes'=>'Attention!']);
        }
        $em = $this->getDoctrine()->getManager();

        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $tel = $request->request->get('tel');
        $password = $request->request->get("password");
        $old = $request->request->get("old");
        $sex = $request->request->get("gender");
        $language = $request->request->get("language");
        $country = $request->request->get("country");
        $instagram = $request->request->get("instagram");
        $facebook = $request->request->get("facebook");
        $snapchat = $request->request->get("snapchat");
        $twitter = $request->request->get("twitter");
        $activities = $request->request->get("activities");
        $partner = $request->request->get("partner");
        $payment = $request->request->get("payment");
        $em = $this->getDoctrine()->getManager();
        if(!$name || $name=='')
            return new JsonResponse(['status'=>1, 'mes'=>'Renseignez votre nom']);
        if(!preg_match("#[a-zA-Z0-9]{2,}@[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}#", $email) )
            return new JsonResponse(['status'=>1, 'mes'=>'Renseignez une adresse email valide']);
        $entityByEmail = $em->getRepository(Influencer::class)->findOneByEmail($email);
        if( $entityByEmail && $entityByEmail->getEmail()  != $user->getEmail() )
            return new JsonResponse(['status'=>1, 'mes'=>'Cette email existe déjà']);
        if(!$tel || !preg_match("#[0-9]{8,}#", $tel))
            return new JsonResponse(['status'=>1, 'mes'=>'Renseignez un numéro de téléphone valide']);
        if(!$old || !($old > 9 && $old < 67) )
            return new JsonResponse(['status'=>1, 'mes'=>'Votre âge doit être compris entre 10 et 66 ans']);
        if(!$sex || ($sex!='masculin' && $sex!='feminin'))
            return new JsonResponse(['status'=>1, 'mes'=>'Renseignez votre sexe']);
        if(!$language || !in_array($language, AccountController::$languages))
            return new JsonResponse(['status'=>1, 'mes'=>'Renseignez une langue valide']);
        if(!in_array($country, AccountController::$countries))
            return new JsonResponse(['status'=>1, 'mes'=>'Renseignez le champs pays']);
        if($instagram == '' && $facebook == '' && $snapchat == '' && $twitter == '')
            return new JsonResponse(['status'=>1, 'mes'=>'Vous devez appartenir au moins à un réseau social']);

        if( $password!='' && !preg_match("#.{8,}#", $password))
            return new JsonResponse(['status'=>1, 'mes'=>'Votre mot de passe doit contenir au moins 08 caractères']);

        if(!$activities || empty($activities))
            return new JsonResponse(['status'=>1, 'mes'=>'Choississez votre secteur d\'activité']);
        foreach ($activities as $activity){
            if(!$em->getRepository(Activity::class)->find($activity))
                return new JsonResponse(['status'=>1, 'mes'=>'Activité non specifiée']);
        }

        if(!$em->getRepository(Partnershiptype::class)->find($partner))
            return new JsonResponse(['status'=>1, 'mes'=>'Type de partenariat non specifié']);

        if(!$payment || !in_array($payment, AccountController::$payments))
            return new JsonResponse(['status'=>1, 'mes'=>'Moyen de paiement non specifié']);

        $influencer =$em->getRepository(Influencer::class)->find($user->getId());
        $influencer->setEmail($email);
        $influencer->setName($name);
        $influencer->setTel($tel);
        if(preg_match("#.{8,}#", $password))
            $influencer->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $influencer->setCountry($country);
        $influencer->setGender($sex);
        $influencer->setLanguage($language);
        $influencer->setOld($old);
        $influencer->setPaymenttype($payment);
        $influencer->setPartnership($em->getRepository(Partnershiptype::class)->find($partner));
        $influencer->setInstagramlink($instagram);
        $influencer->setFacebooklink($facebook);
        $influencer->setSnapchatlink($snapchat);
        $influencer->setTwitterlink($twitter);

        $concernesRemoves = $em->getRepository(Concerne::class)->findByInfluencer($user->getId());
        foreach ($concernesRemoves as $concerneRemove){
            $em->remove($concerneRemove);
        }
        foreach ($activities as $activity) {
            $concerne = new Concerne();
            $activity_n = $em->getRepository(Activity::class)->find($activity);
            $concerne->setInfluencer($influencer);
            $concerne->setActivity($activity_n);
            $em->persist($concerne);
        }
        try{
            $em->flush();
            $request->getSession()->set("user", $influencer);
            return new JsonResponse(['status'=>0, 'mes'=>'Modification apportée avec succès!']);
        }catch (\Exception $e){
            return new JsonResponse(['status'=>1, 'mes'=>'Une erreur est survenue']);
        }
    }

    public function addPhotoAction(Influencer $influencer, Request $request){
        if(!$request->getSession()->get('user')){
            return new JsonResponse(['status'=>1, 'mes'=>'Attention!']);
        }
        $photos = $request->files->get("photos");
        foreach ($photos as $photo){
            if(!in_array($photo->guessExtension(), AccountController::$extensions))
                return new JsonResponse(['status'=>1, 'mes'=>'Veuillez renseignez une image valide']);
        }
        $em = $this->getDoctrine()->getManager();
        foreach ($photos as $photo){
            $imageDirectory = $this->getParameter("image_directory");
            $thumbnailDirectory = $this->getParameter("thumbnail_directory");
            $filename = $this->getUniqueFileName() . "." . $photo->guessExtension();
            $photo->move($imageDirectory, $filename);
            $thumb = new Thumbnails();
            $thumb->createThumbnail($imageDirectory . "/" . $filename, $thumbnailDirectory . "/" . $filename, 300);
            $photo_n = new Photo();
            $photo_n->setLink($filename);
            $photo_n->setInfluencer($influencer);
            $em->persist($photo_n);
        }
        try {
            $em->flush();
            return new JsonResponse(['status'=>0, 'mes'=>'Photos ajoutées avec succès']);
        }catch (\Exception $e){
            return new JsonResponse(['status'=>1, 'mes'=>'Une erreur est survenue']);
        }
    }

    public function removePhotoAction(Photo $photo, Request $request){
        if(!$request->getSession()->get('user')){
            return new JsonResponse(['status'=>1, 'mes'=>'Attention!']);
        }

        $imageDirectory = $this->getParameter("image_directory");
        $thumbnailDirectory = $this->getParameter("thumbnail_directory");
        $em = $this->getDoctrine()->getManager();
        $photos = $em->getRepository(Photo::class)->findByInfluencer($photo->getInfluencer()->getId());
        if(count($photos) == 1)
            return new JsonResponse(['status'=>1, 'mes'=>'Vous devriez avoir au moins une photo.']);
        try {
            $em->remove($photo);
            $em->flush();
        }catch (\Exception $e){
            return new JsonResponse(['status'=>1, 'mes'=>'Une erreur est survenue']);
        }
        if(file_exists($imageDirectory."/".$photo->getLink()))
            unlink($imageDirectory."/".$photo->getLink());
        if(file_exists($thumbnailDirectory."/".$photo->getLink()))
            unlink($thumbnailDirectory."/".$photo->getLink());
        return new JsonResponse(['status'=>0, 'mes'=>'Photo supprimée avec succès']);
    }

    public function getUniqueFileName(){
        return md5(uniqid());
    }

}
