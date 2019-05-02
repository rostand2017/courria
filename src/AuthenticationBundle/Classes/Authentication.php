<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/19/2018
 * Time: 6:36 PM
 */

namespace AuthenticationBundle\Classes;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;

class Authentication
{
    private $router;
    private $request;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function isAuthenticate(GetResponseEvent $event){

        $this->request = $event->getRequest();
        $user = $this->request->getSession()->get("user");
        if( !$user && $this->isAdmin() ){
            $event->setResponse(new RedirectResponse( $this->router->generate("admin_login")));
        }
    }

    public function isAdmin(){
        $request = $this->request;
        $url = $request->getUri();
        if( preg_match("#/admin#", $url) && !preg_match("#/admin/Login#", $url) ){
            return true;
        }else
            return false;
    }

}