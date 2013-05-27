<?php

namespace User\GuessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="home_guess")
     */
    public function indexAction()
    {

        $user = $this->get('security.context')->getToken()->getUser();
        $securityContext = $this->container->get('security.context');


        if($securityContext->isGranted('ROLE_PROFESIONAL')){

            return $this->redirect($this->generateUrl('profesional_consulta'));
        }elseif($securityContext->isGranted('ROLE_CLIENTE')){
            return $this->redirect($this->generateUrl('client_consulta'));
        }

        return $this->redirect($this->generateUrl('fos_user_security_login'));
    }
}
