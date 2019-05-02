<?php

namespace HomeBundle\Controller;

use HomeBundle\Entity\Category;
use HomeBundle\Entity\Image;
use HomeBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ProductController extends Controller
{

    public function singleProductAction(Product $product, $name){
        if($product){
            $em = $this->getDoctrine()->getManager();
            $otherProducts = $em->getRepository(Product::class)->getOtherProduct(10, $product);
            return $this->render("HomeBundle:Home:single_product.html.twig", array(
                "product" => $product,
                "otherProducts" => $otherProducts,
            ));
        }else{
            throw new NotFoundHttpException("Ce produit n'existe pas ou a été retiré");
        }
    }

    public function showSingleProductAction(Product $product){
        if(!$product){
            return new JsonResponse( array(
                "status" => 1,
                "message" => "Une erreur est survenue !"
            ));
        }
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) { return $object->getId(); });
        $normalizers = array($normalizer);
        $serializers = new Serializer($normalizers, $encoders);

        $imageComponent = '
                        <div class="p-l-25 p-r-30 p-lr-0-lg">
                            <div class="wrap-slick3 flex-sb flex-w">

                                <div class="slick3 gallery-lb slick-initialized slick-slider slick-dotted">
                                    <div class="slick-list draggable">
                                        <div class="slick-track" style="opacity: 1; width: 1401px;">
                                            <div class="item-slick3 slick-slide slick-current slick-active" data-thumb="/uploads/images/'.$product->getImages()[0]->getImage().'" id="slick-slide20" data-slick-index="0" aria-hidden="false" style="width: 467px; position: relative; left: 0px; top: 0px; z-index: 999; opacity: 1;" tabindex="0" role="tabpanel" aria-describedby="slick-slide-control20">
                                                <div class="wrap-pic-w pos-relative">
                                                    <img src="/uploads/images/'.$product->getImages()[0]->getImage().'" alt="IMG-PRODUCT">
        
                                                    <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="/uploads/images/'.$product->getImages()[0]->getImage().'" tabindex="0">
                                                        <i class="fa fa-expand"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        ';
        $pathAddCart = $this->generateUrl("home_cart_add", ['product'=>$product->getId()]);
        return new JsonResponse( array(
            "status" => 0,
            "product" => $serializers->serialize($product, 'json'),
            "imageComponent" => $imageComponent,
            "pathAddCart" => $pathAddCart,
        ));
    }

    public function addProductsAction($limit, Request $request)
    {
        $nbPerPage = 16;
        $em = $this->getDoctrine()->getManager();

        if( $search = $request->query->get("search") ){
            $products = $em->getRepository(Product::class)
                ->getSearchProducts($limit, $nbPerPage, $search);
            $productsComponent = "";
            foreach ($products as $product){
                $productsComponent .= '
                <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item women" style="position: absolute; left: 0%; top: 0px;">
                    <!-- Block2 -->
                    <div class="block2">
                        <div class="block2-pic hov-img0">
                            <img src="/uploads/thumbnails/"'.$product.getImages()[0].' alt="Robe soirée" title="Robe soirée">

                            <a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
                                Quick View
                            </a>
                        </div>

                        <div class="block2-txt flex-w flex-t p-t-14">
                            <div class="block2-txt-child1 flex-col-l ">
                                <a href="" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                    '.$product.getName().'
                                </a>

                                <span class="stext-105 cl3">
									'.$product.getPrice().'
								</span>
                            </div>

                            <div class="block2-txt-child2 flex-r p-t-3">
                                <a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2 js-addedwish-b2">
                                    <img class="icon-heart1 dis-block trans-04" src="/bundles/home/images/icons/icon-heart-01.png" alt="ICON">
                                    <img class="icon-heart2 dis-block trans-04 ab-t-l" src="/bundles/home/images/icons/icon-heart-02.png" alt="ICON">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            ';
            }
            $hasOther = (count($products) < $nbPerPage)?false:true;
            return new JsonResponse(array(
                "products"=>$productsComponent,
                "limit"=>$limit+$nbPerPage,
                "hasOther"=>$hasOther,
                "search"=>$search
            ));
        }else{
            $products = $em->getRepository(Product::class)->getProducts($limit, $nbPerPage);

            $productsComponent = "";
            foreach ($products as $product){
                $productsComponent .= '
                <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item women" style="position: absolute; left: 0%; top: 0px;">
                    <!-- Block2 -->
                    <div class="block2">
                        <div class="block2-pic hov-img0">
                            <img src="/uploads/thumbnails/"'.$product.getImages()[0].' alt="Robe soirée" title="Robe soirée">

                            <a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
                                Quick View
                            </a>
                        </div>

                        <div class="block2-txt flex-w flex-t p-t-14">
                            <div class="block2-txt-child1 flex-col-l ">
                                <a href="" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                    '.$product.getName().'
                                </a>

                                <span class="stext-105 cl3">
									'.$product.getPrice().'
								</span>
                            </div>

                            <div class="block2-txt-child2 flex-r p-t-3">
                                <a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2 js-addedwish-b2">
                                    <img class="icon-heart1 dis-block trans-04" src="/bundles/home/images/icons/icon-heart-01.png" alt="ICON">
                                    <img class="icon-heart2 dis-block trans-04 ab-t-l" src="/bundles/home/images/icons/icon-heart-02.png" alt="ICON">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            ';
            }
            $hasOther = (count($products) < $nbPerPage)?false:true;
            return new JsonResponse(array(
                "products"=>$productsComponent,
                "limit"=>$limit+$nbPerPage,
                "hasOther"=>$hasOther,
            ));
        }
    }

    public function addCategoryProductsAction($limit, $category, Request $request)
    {
        $nbPerPage = 16;
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository("HomeBundle\Entity\Category")->findOneById($category);
        if($category){
            if( $search = $request->query->get("search") ){
                $products = $em->getRepository(Product::class)
                    ->getSearchCategoryProducts($limit, $nbPerPage, $search, $category->getId());
                $productsComponent = "";
                foreach ($products as $product){
                    $productsComponent .= '
                <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item women" style="position: absolute; left: 0%; top: 0px;">
                    <!-- Block2 -->
                    <div class="block2">
                        <div class="block2-pic hov-img0">
                            <img src="/uploads/thumbnails/"'.$product.getImages()[0].' alt="Robe soirée" title="Robe soirée">

                            <a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
                                Quick View
                            </a>
                        </div>

                        <div class="block2-txt flex-w flex-t p-t-14">
                            <div class="block2-txt-child1 flex-col-l ">
                                <a href="" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                    '.$product.getName().'
                                </a>

                                <span class="stext-105 cl3">
									'.$product.getPrice().'
								</span>
                            </div>

                            <div class="block2-txt-child2 flex-r p-t-3">
                                <a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2 js-addedwish-b2">
                                    <img class="icon-heart1 dis-block trans-04" src="/bundles/home/images/icons/icon-heart-01.png" alt="ICON">
                                    <img class="icon-heart2 dis-block trans-04 ab-t-l" src="/bundles/home/images/icons/icon-heart-02.png" alt="ICON">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            ';
                }
                $hasOther = (count($products) < $nbPerPage)?false:true;
                return new JsonResponse(array(
                    "products"=>$productsComponent,
                    "limit"=>$limit+$nbPerPage,
                    "hasOther"=>$hasOther,
                    "search"=>$search
                ));
            }else{
                $products = $em->getRepository(Product::class)->getCategoryProducts($limit, $nbPerPage, $category->getId());

                $productsComponent = "";
                foreach ($products as $product){
                    $productsComponent .= '
                <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item women" style="position: absolute; left: 0%; top: 0px;">
                    <!-- Block2 -->
                    <div class="block2">
                        <div class="block2-pic hov-img0">
                            <img src="/uploads/thumbnails/"'.$product.getImages()[0].' alt="Robe soirée" title="Robe soirée">

                            <a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
                                Quick View
                            </a>
                        </div>

                        <div class="block2-txt flex-w flex-t p-t-14">
                            <div class="block2-txt-child1 flex-col-l ">
                                <a href="" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                    '.$product.getName().'
                                </a>

                                <span class="stext-105 cl3">
									'.$product.getPrice().'
								</span>
                            </div>

                            <div class="block2-txt-child2 flex-r p-t-3">
                                <a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2 js-addedwish-b2">
                                    <img class="icon-heart1 dis-block trans-04" src="/bundles/home/images/icons/icon-heart-01.png" alt="ICON">
                                    <img class="icon-heart2 dis-block trans-04 ab-t-l" src="/bundles/home/images/icons/icon-heart-02.png" alt="ICON">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            ';
                }
                $hasOther = (count($products) < $nbPerPage)?false:true;
                return new JsonResponse(array(
                    "products"=>$productsComponent,
                    "limit"=>$limit+$nbPerPage,
                    "hasOther"=>$hasOther,
                ));
            }
        }else{
            $productsComponent = "<p>Une erreur est survenue</p>";
            return new JsonResponse(array(
                "products"=>$productsComponent,
                "mes"=>"une erreur est survenue",
                "hasOther"=> true,
            ));
        }
    }


    public function productCategoryAction($category, Request $request){
        $em = $this->getDoctrine()->getManager();
        $limit = 0;
        $nbPerPage = 16;
        $categories = $em->getRepository("HomeBundle\Entity\Category")->findAll();
        $category = $em->getRepository("HomeBundle\Entity\Category")->findOneByCategory($category);
        if( $search = $request->query->get("search")){
            $products = $em->getRepository(Product::class)
                ->getSearchCategoryProducts($limit, $nbPerPage, $search, $category->getId());
            foreach ($products as $index=>$product){
                $products[$index]['images'] = $em->getRepository(Image::class)->findByProduct($product['id']);
            }
            return $this->render('HomeBundle:Home:product.html.twig', array(
                "products" => $products,
                "limit"=>$limit+$nbPerPage,
                "categories" => $categories,
                "category"=> $category->getId(),
            ));
        }else{
            $products = $em->getRepository(Product::class)
                ->getCategoryProducts($limit, $limit + $nbPerPage, $category);
            return $this->render('HomeBundle:Home:product.html.twig', array(
                "products" => $products,
                "limit"=>$limit+$nbPerPage,
                "category"=> $category->getId(),
                "categories" => $categories,
            ));
        }
    }
}
