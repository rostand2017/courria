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

class AccountController extends Controller
{
    public static $languages= ["français", "anglais", "espanol", "allemand", "chinois"];
    public static $payments = ['Orange Money', 'Mobile Money', 'Autres'];
    public static $extensions = ['jpg', 'jpeg', 'png'];
    public static $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");

    public function loginAction(Request $request){

        if($request->getSession()->get("user")){
            return $this->redirectToRoute("home_homepage");
        }

        if($request->isMethod('POST')){
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            if( !empty(trim($email)) && !empty($password) ){
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository(Influencer::class)->findOneBy(["email"=>$email]);
                if($user && password_verify($password, $user->getPassword())){
                    $request->getSession()->set("user", $user);
                    return new JsonResponse(['status'=>0, 'mes'=>'Bienvenue '.$user->getName()]);
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
            $photos = $request->files->get("photos");
            $em = $this->getDoctrine()->getManager();
            if(!$name || $name=='')
                return new JsonResponse(['status'=>1, 'mes'=>'Renseignez votre nom']);
            if(!preg_match("#[a-zA-Z0-9]{2,}@[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}#", $email) )
                return new JsonResponse(['status'=>1, 'mes'=>'Renseignez une adresse email valide']);
            if( $em->getRepository(Influencer::class)->findOneByEmail($email) )
                return new JsonResponse(['status'=>1, 'mes'=>'Cette email existe déjà']);
            if(!$tel || !preg_match("#[0-9]{8,}#", $tel))
                return new JsonResponse(['status'=>1, 'mes'=>'Renseignez un numéro de téléphone valide']);
            if(!$password || !preg_match("#.{8,}#", $password))
                return new JsonResponse(['status'=>1, 'mes'=>'Votre mot de passe doit contenir au moins 08 caractères']);
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

            if(!$photos && !empty($photos))
                return new JsonResponse(['status'=>1, 'mes'=>'Ajoutez quelques photos de vous.']);
            foreach ($photos as $photo){
                if(!in_array($photo->guessExtension(), AccountController::$extensions))
                    return new JsonResponse(['status'=>1, 'mes'=>'Veuillez renseignez une image valide']);
            }

            $influencer = new Influencer();
            $influencer->setEmail($email);
            $influencer->setName($name);
            $influencer->setTel($tel);
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
            try{

                $em->persist($influencer);
                //$em->flush();
                //$influencer = $em->getRepository(Influencer::class)->findOneByEmail($email);
                foreach ($activities as $activity) {
                    $concerne = new Concerne();
                    $activity_n = $em->getRepository(Activity::class)->find($activity);
                    $concerne->setInfluencer($influencer);
                    $concerne->setActivity($activity_n);
                    $em->persist($concerne);
                }

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
                $em->flush();
                $request->getSession()->set("user", $influencer);
                return new JsonResponse(['status'=>0, 'mes'=>'Inscription réussie!']);
            }catch (\Exception $e){
                /*foreach ($photos as $photo){
                    $imageDirectory = $this->getParameter("image_directory");
                    $thumbnailDirectory = $this->getParameter("thumbnail_directory");
                    $filename = $this->getUniqueFileName() . "." . $photo->guessExtension();
                    //$photo->move($imageDirectory, $filename);
                    if(file_exists($imageDirectory."/".$filename))
                        unlink($imageDirectory."/".$filename);
                    if(file_exists($thumbnailDirectory."/".$filename))
                        unlink($thumbnailDirectory."/".$filename);
                }*/
                return new JsonResponse(['status'=>1, 'mes'=>'Une erreur est survenue']);
            }
        }
        $em = $this->getDoctrine()->getManager();
        $activities = $em->getRepository(Activity::class)->findAll();
        $partnerShipTypes = $em->getRepository(Partnershiptype::class)->findAll();
        return $this->render("HomeBundle:Login:register.html.twig", array(
            "activities" => $activities,
            "partnerShipTypes" => $partnerShipTypes,
            "countries" => AccountController::$countries,
        ));
    }

    public function changePasswordAction(Request $request){
        $user = $request->getSession()->get("user");
        if(!$user){
            return $this->redirectToRoute("home_login");
        }
        return $this->render("HomeBundle:Login:change_password.html.twig");
    }

    public function logoutAction(Request $request){
        $request->getSession()->remove("user");
        return $this->redirectToRoute("home_homepage");
    }

    public function forgotPasswordAction(Request $request){
        $user = $request->getSession()->get("user");
        if($user){
            return $this->redirectToRoute("home_feature");
        }
        return $this->render("HomeBundle:Login:forgot_password.html.twig");
    }

    public function getUniqueFileName(){
        return md5(uniqid());
    }

}
