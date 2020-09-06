<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/8/2018
 * Time: 6:52 PM
 */

namespace AdminBundle\Controller;

use AdminBundle\Entity\Courrier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{

    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        $courriers = $em->getRepository(Courrier::class)->findBy([], ["position"=>"asc", "dateexpedition"=>"desc"]);

        $controller=0;
        $contSec=0;
        $serviceBrigade=0;
        $serviceSolde=0;
        $serviceAdmin=0;
        $serviceVisa=0;
        $finished=0;
        foreach ($courriers as $courrier){
            switch ($courrier->getPosition()){
                case "controlleur":
                    $controller++;
                    break;
                case CourrierController::SEC_CON:
                    $contSec++;
                    break;
                case CourrierController::SER_SOLDE :
                    $serviceSolde++;
                    break;
                case CourrierController::SER_VISA :
                    $serviceVisa++;
                    break;
                case CourrierController::SER_BRIGADE :
                    $serviceBrigade++;
                    break;
                case CourrierController::SER_ADMIN :
                    $serviceAdmin++;
                    break;
                default:
                    $finished ++;
            }
        }
        return $this->render('AdminBundle:Courrier:dashboard.html.twig', compact("controller", "contSec", "serviceSolde",
            "serviceBrigade", "serviceAdmin", "serviceVisa", "finished"));
    }

    private function getUniqueFileName(){
        return md5(uniqid());
    }

}