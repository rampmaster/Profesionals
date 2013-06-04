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

        if($securityContext->isGranted('ROLE_ADMIN')){
            return $this->redirect($this->generateUrl('admin_professionals'));
        }

        if($securityContext->isGranted('ROLE_PROFESIONAL')){

            return $this->redirect($this->generateUrl('profesional_consulta'));
        }elseif($securityContext->isGranted('ROLE_CLIENTE')){
            return $this->redirect($this->generateUrl('client_consulta'));
        }

        $host = $this->get('session')->get('subdomain');
        $usermanager = $this->get('fos_user.user_manager');
        $professional = $usermanager->findUserByUsername($host);
        if($host){
            if($professional){
                return $this->redirect($this->generateUrl('fos_user_security_login'));            
            }else{
                die($host);
                throw new \Exception("No se encontró la página",404);
            }
        }


        return $this->redirect($this->generateUrl('home_web'));
    }



    /**
     * @Route("/authenticate/{token}", name="home_guess_client")
     */
    public function clientAction($token)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('UserProfesionalBundle:ProfesionalEvent')->findOneByClientAccessToken($token);
        if(!$event){
            throw new \Exception("Token not found");
        }
        die("We provide: ".$event->getClient()->getUser()->getUsername());

    } 
}
