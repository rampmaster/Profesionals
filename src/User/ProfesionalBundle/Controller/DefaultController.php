<?php

namespace User\ProfesionalBundle\Controller;

use Core\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



class DefaultController extends Controller
{
    /**
     * @Route("/", name="profesional_home")
     */
    public function indexAction()
    {
        return $this->render('UserProfesionalBundle:Default:index.html.twig');
    }

    /**
     * @Route("/consulta", name="profesional_consulta")
     * @Template()
     */
    public function consultaAction()
    {

        return array();
    }

    /**
     * @Route("/settings", name="profesional_settings")
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
     * @Route("/retrieve-client-consulta", name="profesional_retrieve_chunk_user")
     * @Template()
     */
    public function chunkInfoUserConsultaAction()
    {
        if($this->getRequest()->getMethod() != 'POST'){
            throw new \Exception('Hack attemp :(');
        }
        $em = $this->getDoctrine()->getManager();

        $id = $this->getRequest()->get('idUser');
        $user = $em->getRepository('CoreUserBundle:User')->find($id);

        return array('entity' => $user->getClient());
    }
}
