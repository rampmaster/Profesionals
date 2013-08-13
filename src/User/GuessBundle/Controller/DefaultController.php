<?php

namespace User\GuessBundle\Controller;

use Core\UserBundle\Request\Agent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;


use Symfony\Component\EventDispatcher\EventDispatcher,
    Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken,
    Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

use FOS\UserBundle\Model\UserInterface;

use Symfony\Component\Security\Core\Exception\AccountStatusException;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="home_guess")
     */
    public function indexAction()
    {

        $user = $this->get('security.context')->getToken()->getUser();
        $securityContext = $this->get('security.context');

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
                //die($host);
                throw new \Exception("No se encontró la página",404);
            }
        }


        return $this->redirect($this->generateUrl('home_web'));
    }



    /**
     * @Route("/acceder/{token}", name="home_guess_client")
     */
    public function clientAction($token)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('UserProfesionalBundle:ProfessionalEvent')->findOneBy(array('client_access_token'=>$token));
        if(!$event){
            throw new \Exception("Token not found");
        }
        
        $user_email = $event->getClient()->getUser()->getEmail();
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByEmail($user_email);
        if(!$user){
            throw new \Exception("User not found");
        }
        $response = $this->redirect($this->generateUrl('home_guess')); 
        
        return $this->authenticateUser($user,$response);
    }

    /**
     * Authenticate a user with Symfony Security
     *
     * @param \FOS\UserBundle\Model\UserInterface        $user
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    protected function authenticateUser(UserInterface $user, Response $response)
    {
        try {
            $this->get('fos_user.security.login_manager')->loginUser(
                $this->container->getParameter('fos_user.firewall_name'),
                $user,
                $response);
        } catch (AccountStatusException $ex) {
            // We simply do not authenticate users which do not pass the user
            // checker (not enabled, expired, etc.).
            throw new \Exception("OOps not avaliable...");
        } catch (\Exception $e) {
            throw new \Exception("OOps not login...");
        }
        return $response;
    }

    /**
     * @Route("/consulta/{username}", name="consulta_guess")
     * @Template()
     */
    public function consultaAction($username)
    {

        $professional = $this->getDoctrine()->getManager()->getRepository('CoreUserBundle:User')->findOneByUsername($username);

        if(!$professional){
            throw new \Exception('No professional found');
        }

        $useragent = new Agent();

        if (!$useragent->checkCapable()) {
            return $this->render('::browsernotsupported.html.twig');
            throw new \Exception('Explorador no soportado');

        }

        return array('professional' => $professional);
    }

}
