<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/19/2018
 * Time: 4:08 PM
 */

namespace AdminBundle\Controller;


use AuthenticationBundle\Entity\User;
use HomeBundle\Entity\Command;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CommandController extends Controller
{

    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->getPayedUser();
        return $this->render("AdminBundle:Product:command.html.twig", array(
            "users" => $users,
        ));
    }

    public function userCommandAction(User $user, $name){
        $em = $this->getDoctrine()->getManager();
        $commands = $em->getRepository(Command::class)->findBy(["user"=>$user->getId(), "isPayed"=>1]);
        return $this->render("AdminBundle:Product:command_user.html.twig", array(
            "commands" => $commands,
            "user"=>$user,
        ));
    }

    public function validCommandAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $idUser = $request->query->get("id_user");
        $id = $request->query->get("id_command");
        if($id && is_numeric($id) && $idUser && is_numeric($idUser)){
            $user = $em->getRepository(User::class)->find($idUser);
            $command = $em->getRepository(Command::class)->findOneBy(['id'=>$id, 'isPayed'=>1]);
            if($user && $command){
                $command->setIsPayed(2);
                $em->persist($command);
                $em->flush();
                return new JsonResponse([
                    "status" => 0,
                    "mes" => "Commande validée avec succès"
                ]);
            }
        }
        return new JsonResponse([
            "status" => 1,
            "mes" => "une erreur s'est produite."
        ]);
    }

    public function validAllCommandAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get("id_user");
        if($id && is_numeric($id)){
            $user = $em->getRepository(User::class)->find($id);
            if($user){
                $commands = $em->getRepository(Command::class)->findBy(["user"=>$user, "isPayed"=>1]);
                foreach ($commands as $command){
                    $command->setIsPayed(2);
                    $em->persist($command);
                }
                $em->flush();
                return new JsonResponse([
                    "status" => 0,
                    "mes" => "Commandes validées avec succès"
                ]);
            }
        }
        return new JsonResponse([
            "status" => 1,
            "mes" => "une erreur s'est produite."
        ]);
    }
}