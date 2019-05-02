<?php

namespace HomeBundle\Controller;

use AuthenticationBundle\Entity\User;
use HomeBundle\Entity\Category;
use HomeBundle\Entity\Image;
use HomeBundle\Entity\Product;
use HomeBundle\Entity\WishesList;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class WishesListController extends Controller
{
    private $em = null;

    public function indexAction(Request $request){
        $user = $request->getSession()->get("user");
        if($user){
            $em = $this->getDoctrine()->getManager();
            $wisheslist = $em->getRepository(WishesList::class)->findBy(['user'=>$user]);
            return $this->render("HomeBundle:Home:wishes_list.html.twig", array(
                "wisheslist" => $wisheslist
            ));
        }
        return $this->redirectToRoute("home_login");
    }

    public function addAction(Request $request, Product $product){
        $this->em = $this->getDoctrine()->getManager();

        $user = $request->getSession()->get("user");
        $user = $this->em->getRepository(User::class)->find($user->getId());
        if($user && $product){
            $product_2 = $this->em->getRepository(WishesList::class)->findOneBy(['product'=>$product, 'user'=>$user]);
            if(!$product_2){
                $wishesList = new WishesList();
                $wishesList->setProduct($product);
                $wishesList->setUser($user);
                $this->em->persist($wishesList);
                $this->em->flush();
                return new JsonResponse([
                    "status" => 0,
                    "mes" => "ajouté avec succès"
                ]);
            }else{
                $this->em->getRepository(WishesList::class)->remove($product, $user);
                $this->em->flush();
                return new JsonResponse([
                    "status" => 0,
                    "mes" => "retiré avec succès de vos favoris"
                ]);
            }
        }
        return new JsonResponse([
            "status" => 1,
            "mes" => "Veuillez vous connecter d'abord avant d'effectuer cette opération"
        ]);
    }
}
