<?php

namespace HomeBundle\Controller;

use AdminBundle\Entity\Shipping;
use AuthenticationBundle\Entity\User;
use Facebook\Facebook;
use HomeBundle\Entity\Category;
use HomeBundle\Entity\Command;
use HomeBundle\Entity\Contact;
use HomeBundle\Entity\Image;
use HomeBundle\Entity\InfoProduct;
use HomeBundle\Entity\NewsLetter;
use HomeBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CommandController extends Controller
{
    const PRODUCT_L = "L";
    const PRODUCT_XL = "XL";
    const PRODUCT_XXL = "XXL";
    // show shopping cart
    public function indexAction(Request $request){
        $user = $request->getSession()->get("user");
        if(!$user){
            return $this->redirectToRoute("home_login");
        }
        $em = $this->getDoctrine()->getManager();
        $commands = $em->getRepository("HomeBundle\Entity\Command")->findBy(['isPayed' => 0, 'user' => $user], ['createdAt'=>'desc']);
        $shippings = $em->getRepository(Shipping::class)->findAll();
        return $this->render("HomeBundle:Home:feature.html.twig", array(
            'commands' => $commands,
            'shippings' => $shippings
        ));
    }

    // show cart to the shopping cart panel

    public function showCartAction(Request $request){
        $user = $request->getSession()->get("user");
        if($user){
            $em = $this->getDoctrine()->getManager();
            $commands = $em->getRepository(Command::class)->findBy(['isPayed' => 0, 'user' => $user], ['createdAt'=>'desc'], 3, 0);

            if(!$commands){
                return new JsonResponse([
                    "status" => 1,
                    "mes" => "<span>Panier vide <i class='fa fa-shopping-cart'></i></span>"
                ]);
            }
            $commandView = '';
            foreach ($commands as $command){
                $product = $command->getProduct();
                $commandView.= '
                    <li class="header-cart-item flex-w flex-t m-b-12">
                        <div class="header-cart-item-img">
                            <img  style="max-width: 80px" src="/uploads/thumbnails/'.$product->getImages()[0]->getThumbnail().'" alt="'.$product->getName().'">
                        </div>

                        <div class="header-cart-item-txt p-t-8">
                            <a href="'.$this->generateUrl('home_cart').'#" class="header-cart-item-name m-b-18 hov-cl1 trans-04">
                                '.$product->getName().'
                            </a>

                            <span class="header-cart-item-info">
                                    '.$product->getPrice().'
                            </span>
                        </div>
                    </li>
                ';
            }
            $total = $em->getRepository(Command::class)->getTotalWithoutShipping($user->getId());
            return new JsonResponse([
                "status"=>0,
                "mes"=> $commandView,
                "total"=> $total
            ]);
        }

        return new JsonResponse([
            "status" => 1,
            "mes" => "Connectez vous avant de pouvoir acceder au panier."
        ]);
    }

    // show all command
    public function allCommandAction(Request $request){
        $user = $request->getSession()->get("user");
        if(!$user){
            return $this->redirectToRoute("home_login");
        }
        $em = $this->getDoctrine()->getManager();
        $commands = $em->getRepository("HomeBundle\Entity\Command")->findBy(['isPayed' => 2, 'user' => $user], ['createdAt'=>'desc'], 25, 0);

        return $this->render("HomeBundle:Home:command.html.twig", array(
            'commands' => $commands,
        ));
    }

    public function addToCartAction(Product $product, Request $request){
        $user = $request->getSession()->get("user");
        if(!$user){
            return new JsonResponse(array(
               "status" => 1,
                "mes" => "Vous devrez d'abord vous connecter avant de continuer cette opération"
            ));
        }
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($user->getId());
        if($product){
                $number = $request->request->get("number");
                $size = $request->request->get("size");
                if( $number && $size && !empty(trim($number))
                    && in_array($size, array(self::PRODUCT_L, self::PRODUCT_XL, self::PRODUCT_XXL))
                ){
                    // vérifions si le produit existe déjà dans le panier
                    $command_2 = $em->getRepository(Command::class)->findOneBy(['product'=>$product, 'user'=> $user, 'size'=>$size,'status'=>0]);
                    if($command_2){
                        return new JsonResponse(array(
                            "status" => 1,
                            "mes" => "Ce vêtement est déjà dans votre panier"
                        ));
                    }
                    $numberAvailable =  $em->getRepository(InfoProduct::class)->numberAvailableForSize($size, $product);
                    if($numberAvailable && $numberAvailable->getNumber() < $number){
                        return new JsonResponse(array(
                            "status" => 1,
                            "mes" => "Il ne reste que ".$numberAvailable->getNumber()." exemplaire(s) disponible(s) pour la taille ".$size

                        ));
                    }
                    $command = new Command();
                    $command->setUser($user);
                    $command->setProduct($product);
                    $command->setNumber($number);
                    $command->setStatus(false);
                    $command->setSize($size);
                    $em->persist($command);
                    $em->flush();
                    return new JsonResponse(array(
                        "status" => 0,
                        "mes" => "Ajouté au panier"
                    ));
                }
        }
        return new JsonResponse(array(
            "status" => 1,
            "mes" => "Une erreur est survenue"
        ));
    }

    // update number of each product in the cart user
    public function updateAction(Request $request){
        $user = $request->getSession()->get("user");
        if(!$user){
            return new JsonResponse(array(
                "status" => 1,
                "mes" => "Attention!!!"
            ));
        }
        $em = $this->getDoctrine()->getManager();
        $number = $request->request->get("number");
        $id = $request->request->get("id");
        if($number && $id && is_numeric($id) && is_numeric($number) && $number>0){
            $command = $em->getRepository(Command::class)->find($id);
            if($command){
                $numberAvailable =  $em->getRepository(InfoProduct::class)->numberAvailableForSize($command->getSize(), $command->getProduct());
                if($numberAvailable && $numberAvailable->getNumber() < $number){
                    return new JsonResponse(array(
                        "status" => 1,
                        "mes" => "Il ne reste que ".$numberAvailable->getNumber()." exemplaire(s) disponible(s) pour la taille ".$command->getSize()
                    ));
                }
                $command->setNumber($number);
                $em->persist($command);
                $em->flush();
                return new JsonResponse(array(
                    "status" => 0,
                    "mes" => "Commande mise à jour"
                ));
            }else{
                return new JsonResponse(array(
                    "status" => 1,
                    "mes" => "Attention!!!"
                ));
            }
        }else{
            return new JsonResponse(array(
                "status" => 1,
                "mes" => "Attention!!! merde ".$number."kk ".$id
            ));
        }
    }

    /*
     * when user want to buy all his shopping cart
     * on vérifie si  l'utilisateur le shippingId existe bien,
     * ensuite on vérifie pour chaque commande si la quantité demandée
     * est toujours disponible. Si oui, on totalise sa commande et on additionne
     * avec les frais d'expédition puis on diminu le quantité de produit pour
     * chaque commande dans la table infoProduct correspondant au produit en question
     * et on passe le isPayed à "1" tout cela dans une transaction(ces opérations sont
     * effectuées une fois que le client aura payé
     * )
     * Sinon, on renvoi une information comme quoi le produit n'est pas suffisant
     */


    public function verifyAction(Request $request){
        $user = $request->getSession()->get("user");
        if(!$user){
            return new JsonResponse(array(
                "status" => 1,
                "mes" => "Attention!!!"
            ));
        }
        $shippingId = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        if(!$shippingId){
            return new JsonResponse(array(
                "status" => 1,
                "mes" => "Définir le lieu d'expédition"

            ));
        }else{
            $shipping = $em->getRepository(Shipping::class)->find($shippingId);
            if(!$shipping){
                return new JsonResponse(array(
                    "status" => 1,
                    "mes" => "Définir un lieu d'expédition correct"

                ));
            }
        }

        $commands = $em->getRepository(Command::class)->findBy(['isPayed' => 0, 'user' => $user], ['createdAt'=>'desc']);
        foreach ($commands as $command){
            $nb =  $em->getRepository(InfoProduct::class)->numberAvailableForSize($command->getSize(), $command->getProduct());
            if($nb->getNumber() < $command->getNumber()){
                return new JsonResponse(array(
                    "status" => 1,
                    "mes" => "Il ne reste que ".$nb." exemplaire(s) de".$command->getProduct()->getName()." disponible(s) pour la taille ".$nb->getSize()

                ));
            }
        }
        // si tout est bon, on défini le lieu d'expédition
        foreach ($commands as $command){
            $command->setShipping($shipping);
            $em->persist($command);
        }
        $em->flush();
        return new JsonResponse(array(
            "status" => 0,
            "mes" => $this->generateUrl("home_command_payment")

        ));
    }

    public function paymentAction(){
        return $this->render("HomeBundle:Home:payment_mode.html.twig");
    }

    public function validAction(Request $request){
        $user = $request->getSession()->get("user");
        if(!$user){
            return new JsonResponse(array(
                "status" => 1,
                "mes" => "Attention!!!"
            ));
        }
        $isWarning = false;
        $em = $this->getDoctrine()->getManager();
        $shippingId = $request->get("shipping");
        if($shippingId && is_numeric($shippingId)){
            $shipping = $em->getRepository(Shipping::class)->find($shippingId);
            if($shipping){
                $commands = $em->getRepository(Command::class)->findBy(['isPayed' => 0, 'user' => $user], ['createdAt'=>'desc']);
                foreach ($commands as $command){
                    $nb =  $em->getRepository(InfoProduct::class)->numberAvailableForSize($command->getSize(), $command->getProduct());
                    if($nb->getNumber() < $command->getNumber()){
                        return new JsonResponse(array(
                            "status" => 1,
                            "mes" => "Il ne reste que ".$nb." exemplaire(s) de".$command->getProduct()->getName()." disponible(s) pour la taille ".$nb->getSize()

                        ));
                    }
                }

                // on diminu la quantité pour chaque produit
                // si tous les produits sont disponibles, on passe le isPayed à 1
                foreach ($commands as $command){
                    $infoProduct = $command->getProduct()->getInfoProduct();
                    $infoProduct->setNumber($infoProduct->getNumber() - $command->getNumber());

                    $command->setIsPayed(1);
                    $em->persist($infoProduct);
                    $em->persist($command);
                }
                $em->flush();
                return new JsonResponse(array(
                    "status" => 1,
                    "mes" => "Commandes effectuées avec succès. Vous serez livré dans les 24 prochaines heures"
                ));
            }else{
                $isWarning = true;
            }
        }

        if($isWarning)
            return new JsonResponse(["status"=>1, "mes"=>"Indiquez le lieu où vous souhaiterez vous faire expédié votre commande"]);

        return new JsonResponse(["status"=>1, "mes"=>"Une erreur est survenue"]);
    }

    public function removeToCartAction(Command $command, Request $request){
        $user = $request->getSession()->get("user");
        if(!$user){
            return new JsonResponse(array(
               "status" => 1,
                "mes" => "Vous devrez d'abord vous connecter avant de continuer cette opération"
            ));
        }
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($user->getId());
        if($command){
            $em->remove($command);
            $em->flush();
            return new JsonResponse(array(
                "status" => 0,
                "mes" => "Produit retiré avec succès"
            ));
        }
        return new JsonResponse(array(
            "status" => 1,
            "mes" => "Une erreur est survenue"
        ));
    }

}
