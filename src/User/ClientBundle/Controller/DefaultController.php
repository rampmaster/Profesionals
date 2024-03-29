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
                $this->get('session')->getFlashBag()->add('notice', 'Datos actualizados con éxito.');
            }
        }


        return array('form' => $form->createView());
    }

    /**
     * @Route("/files", name="client_files")
     * @Template()
     */
    public function filesAction()
    {

        return array();

    }

    /**
     * @Route("/files/add/new", name="client_files_add")
     * @Template()
     */
    public function filesaddAction()
    {

        return array();

    }



}
