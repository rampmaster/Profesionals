<?php

namespace User\ProfesionalBundle\Controller;

use Core\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\DateTime;
use User\ClientBundle\Entity\Client;
use User\ClientBundle\Form\ClientType;
use User\ProfesionalBundle\Entity\Report;
use User\ProfesionalBundle\Form\ReportType;

class ClientesController extends Controller
{
    /**
     * @Route("/clientes/", name="profesional_clientes")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clientes = $em->getRepository('UserClientBundle:Client')->findAll();


        return array('clientes' => $clientes);
    }

    /**
     * @Route("/clientes/add", name="profesional_clientes_add")
     * @Template()
     */
    public function addAction()
    {
        $usermanager = $this->get('fos_user.user_manager');
        $client = new Client();
        $user = $usermanager->createUser();

        $client->setUser($user);
        $user->setClient($client);
        $form = $this->createForm(new ClientType(), $client);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $client->setLastVisit(new \DateTime());

                $this->getDoctrine()->getManager()->persist($client);
                $this->getDoctrine()->getManager()->persist($client->getUser());
                $this->getDoctrine()->getManager()->flush();
                $usermanager->updateUser($client->getUser());


                return $this->redirect($this->generateUrl('profesional_clientes'));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/clientes/show/{idCliente}", name="profesional_clientes_show")
     * @Template()
     */
    public function showAction($idCliente)
    {
        $em = $this->getDoctrine()->getManager();
        $cliente = $em->getRepository('UserClientBundle:Client')->find($idCliente);


        return array('cliente' => $cliente);
    }

    /**
     * @Route("/clientes/add-report/{idCliente}", name="profesional_clientes_add_report")
     * @Template()
     */
    public function addreportAction($idCliente)
    {
        $em = $this->getDoctrine()->getManager();

        $client = $em->getRepository('UserClientBundle:Client')->find($idCliente);

        $report = new Report();

        $report->setClient($client);

        $form = $this->createForm(new ReportType(), $report);

        $request = $this->getRequest();

        if($request->getMethod() == 'POST'){
            $form->bind($request);

            if($form->isValid()){

                $client->addReport($report);

                $em->persist($report);
                $em->persist($client);
                $em->flush();

                return $this->redirect($this->generateUrl('profesional_clientes_show', array('idCliente' => $idCliente)));
            }
        }

        return array('form' => $form->createView());
    }


}