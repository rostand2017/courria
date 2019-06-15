<?php

namespace HomeBundle\Controller;

use Facebook\Facebook;
use HomeBundle\Entity\Activity;
use HomeBundle\Entity\Category;
use HomeBundle\Entity\Commentaire;
use HomeBundle\Entity\Concerne;
use HomeBundle\Entity\Concert;
use HomeBundle\Entity\Contact;
use HomeBundle\Entity\Image;
use HomeBundle\Entity\Influencer;
use HomeBundle\Entity\NewsLetter;
use HomeBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends Controller
{
    public static $languages= ["français", "anglais", "espanol", "allemand", "chinois"];
    public static $payments = ['Orange Money', 'Mobile Money', 'Autres'];
    public static $extensions = ['jpg', 'jpeg', 'png'];

    public function loginAction(Request $request){

        if($request->getSession()->get("user")){
            return $this->redirectToRoute("home_homepage");
        }

        if($request->isMethod('POST')){
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            if( !empty(trim($email)) && !empty($password) ){
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository(Client::class)->findOneBy(["email"=>$email]);
                if($user && password_verify($password, $user->getMdp())){
                    $request->getSession()->set("user", $user);
                    return new JsonResponse(['status'=>0, 'mes'=>'Bienvenue '.$user->getNom()]);
                }else{
                    return new JsonResponse(['status'=>1, 'mes'=>'Email ou mot de passe incorrect.']);
                }
            }else{
                return new JsonResponse(['status'=>1, 'mes'=>'Email ou mot de passe incorrect']);
            }
        }
        return $this->render("HomeBundle:Login:login.html.twig", array());
    }

    public function registerAction(Request $request){
        if($request->getSession()->get("user")){
            return $this->redirectToRoute("home_homepage");
        }
        if($request->isMethod("POST")){
            $name = $request->request->get('name');
            $email = $request->request->get('email');
            $tel = $request->request->get('tel');
            $password = $request->request->get("password");
            $old = $request->request->get("old");
            $sex = $request->request->get("sex");
            $language = $request->request->get("language");
            $instagram = $request->request->get("instagram");
            $facebook = $request->request->get("facebook");
            $snapchat = $request->request->get("snapchat");
            $twitter = $request->request->get("twitter");
            $activities = $request->request->get("activities");
            $payment = $request->request->get("payment");
            $photos = $request->request->get("photos");
            $em = $this->getDoctrine()->getRepository(Activity::class);
            if(!$name || $name='')
                return new JsonResponse(['status'=>1, 'mes'=>'Renseignez votre nom']);
            if(!preg_match("#[a-zA-Z0-9]{2,}@[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}#", $email))
                return new JsonResponse(['status'=>1, 'mes'=>'Renseignez une adresse email valide']);
            if(!$tel || !preg_match("#[0-9]{8,}#", $email))
                return new JsonResponse(['status'=>1, 'mes'=>'Renseignez un numéro de téléphone valide']);
            if(!$password && !preg_match("#.{8,}#", $password))
                return new JsonResponse(['status'=>1, 'mes'=>'Votre mot de passe doit contenir au moins 08 caractères']);
            if(!$old || !($old > 9 && $old < 67) )
                return new JsonResponse(['status'=>1, 'mes'=>'Votre âge doit être compris entre 10 et 66 ans']);
            if(!$sex || ($sex!='masculin' && $sex!='feminin'))
                return new JsonResponse(['status'=>1, 'mes'=>'Renseignez votre sexe']);
            if(!$language || !in_array($language, AccountController::$languages))
                return new JsonResponse(['status'=>1, 'mes'=>'Renseignez une langue valide']);
            if($instagram == '' && $facebook == '' && $snapchat == '' && $twitter == '')
                return new JsonResponse(['status'=>1, 'mes'=>'Vous devez appartenir au moins à un réseau social']);

            if(!$activities || empty($activities))
                return new JsonResponse(['status'=>1, 'mes'=>'Choississez votre secteur d\'activité']);
            foreach ($activities as $activity){
                if(!$em->find($activity))
                    return new JsonResponse(['status'=>1, 'mes'=>'Activité non specifiée']);
            }

            if(!$payment || !in_array($payment, AccountController::$payments))
                return new JsonResponse(['status'=>1, 'mes'=>'Moyen de paiement non specifié']);

            if(!$photos && !empty($photos))
                return new JsonResponse(['status'=>1, 'mes'=>'Ajoutez quelques photos de vous.']);
            foreach ($photos as $photo){
                if(!in_array($photo->guessExtension(), AccountController::$extensions))
                    return new JsonResponse(['status'=>1, 'mes'=>'Veuillez renseignez une image valide']);
            }

            $concerne = new Concerne();
            $influencer = new Influencer();
        }
        $em = $this->getDoctrine()->getManager();
        $activities = $em->getRepository(Activity::class)->findAll();
        return $this->render("HomeBundle:Login:register.html.twig", array(
            "activities" => $activities
        ));
    }

    public function changePasswordAction(Request $request){
        $user = $request->getSession()->get("user");
        if(!$user){
            return $this->redirectToRoute("home_login");
        }
        return $this->render("HomeBundle:Login:change_password.html.twig");
    }

    public function forgotPasswordAction(Request $request){
        $user = $request->getSession()->get("user");
        if($user){
            return $this->redirectToRoute("home_feature");
        }
        return $this->render("HomeBundle:Login:forgot_password.html.twig");
    }

}
