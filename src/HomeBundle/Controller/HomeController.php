<?php

namespace HomeBundle\Controller;

use Facebook\Facebook;
use HomeBundle\Entity\Category;
use HomeBundle\Entity\Contact;
use HomeBundle\Entity\Image;
use HomeBundle\Entity\NewsLetter;
use HomeBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $slides = $em->getRepository("HomeBundle\Entity\Slide")->findAll();
        $products = $em->getRepository("HomeBundle\Entity\Product")->findBy([], ['createdAt'=>'desc'], 25, 0);
        $categories = $em->getRepository(Category::class)->findBy([], ['id'=>'desc'], 3, 0);
        return $this->render('HomeBundle:Home:index.html.twig', array(
            "slides" => $slides,
            "products" => $products,
            "categories" => $categories
        ));
    }

    public function loginAction(Request $request){
        if($request->getSession()->get("user")){
            return $this->redirectToRoute("home_homepage");
        }
        $f = new Facebook([
            'app_id' => '366830307424113',
            'app_secret' => '3faed04e7d58085d56c8e7b1977440de',
            'default_graph_version' => 'v3.2',
        ]);
        $url = $f->getRedirectLoginHelper()->getLoginUrl($this->generateUrl("home_cart"), ['email', 'public_profile']);
        return $this->render("HomeBundle:Login:login.html.twig", array(
            'url' => $url
        ));
    }

    public function registerAction(Request $request){
        if($request->getSession()->get("user")){
            return $this->redirectToRoute("home_product");
        }
        return $this->render("HomeBundle:Login:register.html.twig");
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

    public function highLightAction(){}

    public function productAction(Request $request){
        $limit = 0;
        $nbPerPage = 16;
        //var_dump($request->getPathInfo());
        //var_dump(mb_split("#?.+#", $this->generateUrl("home_product")));
        //die();
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository("HomeBundle\Entity\Category")->findAll();
        if( $search = $request->query->get("search")){
            $products = $em->getRepository("HomeBundle\Entity\Product")
                ->getSearchProducts($limit, $nbPerPage, $search);
            foreach ($products as $index=>$product){
                $products[$index]['images'] = $em->getRepository(Image::class)->findByProduct($product['id']);
            }
            return $this->render('HomeBundle:Home:product.html.twig', array(
                "products" => $products,
                "categories" => $categories,
                "limit"=>$limit+$nbPerPage,
            ));
        }else{
            $products = $em->getRepository(Product::class)
                ->getProducts($limit, $limit + $nbPerPage);
            return $this->render('HomeBundle:Home:product.html.twig', array(
                "products"=>$products,
                "categories" => $categories,
                "limit"=>$limit+$nbPerPage,
            ));
        }
    }

    public function aboutAction(){
        return $this->render("HomeBundle:Home:about.html.twig");
    }

    public function contactAction(Request $request){
        if($request->isMethod('POST')){
            $email = $request->request->get('email');
            $message = $request->request->get('message');
            if($message && $email && preg_match("#[a-zA-Z0-9]{2,}@[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}#", $email)){
                // insertion dans la table contact
                $em = $this->getDoctrine()->getManager();
                $c = new Contact();
                $c->setEmail($email);
                $c->setMessage($message);
                $em->persist($c);
                $em->flush();
                return new JsonResponse(['status'=>1, 'message'=>'Message envoyé avec succès']);
            }else{
                return new JsonResponse(['status'=>0, 'message'=>'Remplir les champs avec conformité']);
            }
        }
        return $this->render("HomeBundle:Home:contact.html.twig");
    }

    public function subscribeAction(Request $request){
        $email = $request->request->get('email');
        if($email && preg_match("#[a-zA-Z0-9]{2,}@[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}#", $email)){
            // insertion dans la table contact
            $em = $this->getDoctrine()->getManager();

            // verification de l'existence de l'email
            $isEmail = $em->getRepository("HomeBundle\Entity\NewsLetter")->findBy(['email'=>$email]);

            if(!$isEmail){
                $n = new NewsLetter();
                $n->setEmail($email);
                $em->persist($n);
                $em->flush();
                return new JsonResponse(['status'=>1, 'message'=>'Souscription effectuée']);
            }else{
                return new JsonResponse(['status'=>1, 'message'=>'Cette email existe déjà']);
            }
        }else{
            return new JsonResponse(['status'=>0, 'message'=>'Email invalide']);
        }
    }

    public function userDashBoardAction(Request $request){
        if(!$request->getSession()->get("user")){
            return $this->redirectToRoute("home_login");
        }
        return $this->render("HomeBundle:Home:user_dashboard.html.twig");
    }

}
