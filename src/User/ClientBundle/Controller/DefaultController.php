<?php

namespace User\ClientBundle\Controller;

use Core\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Core\UserBundle\Request\Agent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class DefaultController extends Controller
{
    /**
     * @Route("/guess", name="client_guess")
     */
    public function guessAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $this->get('security.context')->getToken()->getUser();
        $securityContext = $this->get('security.context');

        $host = $this->get('session')->get('subdomain');
        $professional = false;
        
        if($host){
            $professional = $userManager->findUserByUsername($host);
            return $this->redirect($this->generateUrl('client_jobs'));
            //check if i have client entity
        }else{
            //go to client_jobs
            return $this->redirect($this->generateUrl('client_jobs'));
        }
        
    }

    /**
     * @Route("/consulta", name="client_consulta")
     * @Template()
     */
    public function consultaAction()
    {
        $useragent = new Agent();

        if (!$useragent->checkCapable()) {
            return $this->render('::browsernotsupported.html.twig');
            throw new \Exception('Explorador no soportado');

        }


        return array();
    }


    /**
     * @Route("/consulta", name="client_request_profesional")
     * @Template()
     */
    public function requestProfesionalAction()
    {

        return array();
    }

    /**
     * @Route("/settings", name="client_settings")
     * @Template()
     */
    public function settingsAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $form = $this->createForm(new UserType(), $user);

        $request = $this->getRequest();

        if($request->getMethod() == 'POST'){
            $form->bind($request);
            if($form->isValid()){

                $user->upload();
                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();
            }
        }


        return array('form' => $form->createView());
    }



    /**
     * @Route("/connect/{pid}", name="client_connect")
     * @Template()
     */
    public function connectAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        return array();
    }


    /**
     * @Route("/files", name="client_files")
     * @Template()
     */
    public function filesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $files = $em->getRepository('CoreFileServerBundle:File')->retrieveUserFilesAccess($user);

        return array('files' => $files);

    }


}
