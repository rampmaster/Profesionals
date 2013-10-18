<?php

namespace User\ClientBundle\Controller;

use Core\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Core\UserBundle\Request\Agent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;


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
            }
        }


        return array('form' => $form->createView());
    }

    /**
     * @Route("/recursos", name="client_recursos")
     * @Template()
     */
    public function recursosAction()
    {


        return array();
    }

    /**
     * @Route("/descarga-analitica/{id}", name="client_recursos_decarga_analitica")
     */
    public function recursosdescarganaliticaAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $analitica = $em->getRepository('UserProfesionalBundle:Analitica')->find($id);

        if($analitica->getClient()->getId() != $user->getClient()->getId()){
           throw new \Exception('Esta analitica no te pertenece');
        }

        $response = $this->renderView('UserClientBundle:Default:analiticapdf.html.twig', array('analitica' => $analitica, 'paciente' => $user));

        //return new Response($response);
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($response),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="file.pdf"'
            )
        );

    }

    /**
     * @Route("/descarga-citologia/{id}", name="client_recursos_decarga_citologia")
     */
    public function recursosdescargancitologiaAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $citologia = $em->getRepository('UserProfesionalBundle:Citologia')->find($id);

        if($citologia->getClient()->getId() != $user->getClient()->getId()){
            throw new \Exception('Esta citologia no te pertenece');
        }

        $response = $this->renderView('UserClientBundle:Default:citologiapdf.html.twig', array('citologia' => $citologia, 'paciente' => $user));


        //return new Response($response);
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($response),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="file.pdf"'
            )
        );

    }

    /**
     * @Route("/descarga-radiografia/{id}", name="client_recursos_decarga_radiografia")
     */
    public function recursosdescargancradiografiaAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $radiografia = $em->getRepository('UserProfesionalBundle:Radiografia')->find($id);

        if($radiografia->getClient()->getId() != $user->getClient()->getId()){
            throw new \Exception('Esta radiografia no te pertenece');
        }

        $response = $this->renderView('UserClientBundle:Default:radiografiapdf.html.twig', array('radiografia' => $radiografia, 'paciente' => $user));


        //return new Response($response);
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($response),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="file.pdf"'
            )
        );

    }

    /**
     * @Route("/files", name="client_files")
     * @Template()
     */
    public function filesAction()
    {

        return array();

    }


}
