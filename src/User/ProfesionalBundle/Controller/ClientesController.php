<?php

namespace User\ProfesionalBundle\Controller;

use Core\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use User\ClientBundle\Entity\Client;
use User\ClientBundle\Form\ClientType;
use User\ProfesionalBundle\Entity\Analitica;
use User\ProfesionalBundle\Entity\Citologia;
use User\ProfesionalBundle\Entity\Radiografia;
use User\ProfesionalBundle\Entity\Receta;
use User\ProfesionalBundle\Entity\Report;
use User\ProfesionalBundle\Entity\Urodinamico;
use User\ProfesionalBundle\Form\AnaliticaType;
use User\ProfesionalBundle\Form\RadiografiaType;
use User\ProfesionalBundle\Form\RecetaType;
use User\ProfesionalBundle\Form\ReportType;
use User\ProfesionalBundle\Form\UrodinamicoType;

class ClientesController extends Controller
{
    /**
     * @Route("/clientes/", name="profesional_clientes")
     * @Template()
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $clients = $em->createQuery("Select c FROM UserClientBundle:Client c JOIN c.user u WHERE c.professional = :profesional AND u.enabled = TRUE ORDER BY u.name ASC")->setParameter("profesional" , $user->getProfessional()->getId())->getResult();
        return array('clients' => $clients);
    }

    /**
     * @Route("/clientes/edit/{idUser}", name="profesional_clientes_edit")
     * @Template()
     */
    public function editAction($idUser)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $professional = $user->getProfessional();

        if (!$professional) {
            throw new \Exception("Professional not found");
        }
        $usermanager = $this->get('fos_user.user_manager');

        $user = $this->getDoctrine()->getManager()->getRepository('CoreUserBundle:User')->find($idUser);
        $client = $user->getClient();

        $form = $this->createForm(new ClientType(), $client);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $client->setLastVisit(new \DateTime());
                $user->upload();

                $client->setProfessional($professional);
                $professional->addClient($client);
                $this->getDoctrine()->getManager()->persist($client);
                $this->getDoctrine()->getManager()->persist($professional);
                $this->getDoctrine()->getManager()->persist($client->getUser());
                $this->getDoctrine()->getManager()->flush();
                $usermanager->updateUser($client->getUser());
                $this->get('session')->getFlashBag()->add('notice', 'Cliente editado con éxito.');

                return $this->redirect($this->generateUrl('profesional_clientes_show', array('idCliente' => $client->getId())));
            }
        }
        return array('form' => $form->createView(), 'cliente' => $client);
    }

    /**
     * @Route("/clientes/add", name="profesional_clientes_add")
     * @Template()
     */
    public function addAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $professional = $user->getProfessional();

        if (!$professional) {
            throw new \Exception("Professional not found");
        }
        $usermanager = $this->get('fos_user.user_manager');
        $client = new Client();
        $user = $usermanager->createUser();
        $user->addRole('ROLE_CLIENTE');
        $user->setEnabled(true);

        $plainPassword = $this->generateRandomString();


        $client->setUser($user);
        $user->setClient($client);
        $form = $this->createForm(new ClientType(), $client);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $client->setLastVisit(new \DateTime());
                $user->setUsername(md5(uniqid()));
                $user->setPlainPassword($plainPassword);
                $user->upload();
                $owner = $this->get('security.context')->getToken()->getUser();

                $client->setProfessional($professional);
                $professional->addClient($client);
                $this->getDoctrine()->getManager()->persist($client);
                $this->getDoctrine()->getManager()->persist($professional);
                $this->getDoctrine()->getManager()->persist($client->getUser());
                $this->getDoctrine()->getManager()->flush();
                $usermanager->updateUser($client->getUser());
                $this->get('session')->getFlashBag()->add('notice', 'Cliente añadido con éxito.');
                $message = \Swift_Message::newInstance()
                    ->setSubject($owner->getName() . ' ' . $owner->getSurname() . ' le ha añadido a su plataforma')
                    ->setFrom('centrogilvernet@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody($this->renderView(
                        'UserProfesionalBundle:Email:client_signup.html.twig',
                        array('user' => $owner, 'client' => $user, 'pass' => $plainPassword)), 'text/html');

                $this->get('mailer')->send($message);

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

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {

                $report->setCreatedAt(new \DateTime());
                $client->addReport($report);

                $em->persist($report);
                $em->persist($client);
                $em->flush();

                return $this->redirect($this->generateUrl('profesional_clientes_show', array('idCliente' => $idCliente)));
            }
        }

        return array('form' => $form->createView(), 'cliente' => $client);
    }

    /**
     * @Route("/clientes/add-analitica/{idUser}", name="profesional_clientes_add_analitica")
     * @Template()
     */
    public function addanaliticaAction($idUser)
    {
        $em = $this->getDoctrine()->getManager();

        $client = $em->getRepository('CoreUserBundle:User')->find($idUser);
        $me = $this->get('security.context')->getToken()->getUser();


        $analitica = new Analitica();

        $analitica->setProfessional($me->getProfessional());
        $analitica->setClient($client->getClient());

        $form = $this->createForm(new AnaliticaType(), $analitica);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $analitica->setCreatedAt(new \DateTime());
                $me->getProfessional()->addAnalitica($analitica);
                $client->getClient()->addAnalitica($analitica);

                $em->persist($analitica);
                $em->persist($me->getProfessional());
                $em->persist($client->getClient());

                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Analitica añadida con éxito. El paciente podrá encontrarla en las sección recursos');

                return $this->redirect($this->generateUrl('profesional_clientes_show', array('idCliente' => $client->getClient()->getId())));


            }
        }

        return array('form' => $form->createView(), 'cliente' => $client);
    }

    /**
     * @Route("/clientes/add-urodinamico/{idUser}", name="profesional_clientes_add_urodinamico")
     * @Template()
     */
    public function addurodinamicoAction($idUser)
    {
        $em = $this->getDoctrine()->getManager();

        $client = $em->getRepository('CoreUserBundle:User')->find($idUser);
        $me = $this->get('security.context')->getToken()->getUser();


        $urodinamico = new Urodinamico();

        $urodinamico->setProfessional($me->getProfessional());
        $urodinamico->setClient($client->getClient());

        $form = $this->createForm(new UrodinamicoType(), $urodinamico);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {


                $urodinamico->setCreatedAt(new \DateTime());
                $me->getProfessional()->addUrodinamico($urodinamico);
                $client->getClient()->addUrodinamico($urodinamico);

                $em->persist($urodinamico);
                $em->persist($me->getProfessional());
                $em->persist($client->getClient());

                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Estudio Urodinámico añadido con éxito. El paciente podrá encontrarlo en las sección recursos');

                return $this->redirect($this->generateUrl('profesional_clientes_show', array('idCliente' => $client->getClient()->getId())));


            }
        }

        return array('form' => $form->createView(), 'cliente' => $client);
    }

    /**
     * @Route("/clientes/add-receta/{idUser}", name="profesional_clientes_add_receta")
     * @Template()
     */
    public function addrecetaAction($idUser)
    {
        $em = $this->getDoctrine()->getManager();

        $client = $em->getRepository('CoreUserBundle:User')->find($idUser);
        $me = $this->get('security.context')->getToken()->getUser();


        $receta = new Receta();

        $receta->setProfessional($me->getProfessional());
        $receta->setClient($client->getClient());

        $form = $this->createForm(new RecetaType(), $receta);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {


                $receta->setCreatedAt(new \DateTime());
                $me->getProfessional()->addReceta($receta);
                $client->getClient()->addReceta($receta);

                $em->persist($receta);
                $em->persist($me->getProfessional());
                $em->persist($client->getClient());

                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Receta añadido con éxito. El paciente podrá encontrarlo en las sección recursos');

                return $this->redirect($this->generateUrl('profesional_clientes_show', array('idCliente' => $client->getClient()->getId())));


            }
        }

        return array('form' => $form->createView(), 'cliente' => $client);
    }


    /**
     * @Route("/clientes/add-radiografia/{idUser}", name="profesional_clientes_add_radiografia")
     * @Template()
     */
    public function addradiografiaAction($idUser)
    {
        $em = $this->getDoctrine()->getManager();

        $client = $em->getRepository('CoreUserBundle:User')->find($idUser);
        $me = $this->get('security.context')->getToken()->getUser();


        $radiografia = new Radiografia();

        $radiografia->setProfessional($me->getProfessional());
        $radiografia->setClient($client->getClient());

        $form = $this->createForm(new RadiografiaType(), $radiografia);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {


                $radiografia->setCreatedAt(new \DateTime());
                $me->getProfessional()->addRadiografia($radiografia);
                $client->getClient()->addRadiografia($radiografia);

                $em->persist($radiografia);
                $em->persist($me->getProfessional());
                $em->persist($client->getClient());

                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Radiografia añadida con éxito. El paciente podrá encontrarla en las sección recursos');

                return $this->redirect($this->generateUrl('profesional_clientes_show', array('idCliente' => $client->getClient()->getId())));


            }
        }

        return array('form' => $form->createView(), 'cliente' => $client);
    }

    /**
     * @Route("/clientes/add-citologia/{idUser}", name="profesional_clientes_add_citologia")
     * @Template()
     */
    public function addcitologiaAction($idUser)
    {
        $em = $this->getDoctrine()->getManager();

        $client = $em->getRepository('CoreUserBundle:User')->find($idUser);
        $me = $this->get('security.context')->getToken()->getUser();


        $citologia = new Citologia();

        $citologia->setProfessional($me->getProfessional());
        $citologia->setClient($client->getClient());


        $citologia->setCreatedAt(new \DateTime());
        $me->getProfessional()->addCitologia($citologia);
        $client->getClient()->addCitologia($citologia);

        $em->persist($citologia);
        $em->persist($me->getProfessional());
        $em->persist($client->getClient());

        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Citologia añadida con éxito. El paciente podrá encontrarla en las sección recursos');

        return $this->redirect($this->generateUrl('profesional_clientes_show', array('idCliente' => $client->getClient()->getId())));


    }

    /**
     * @Route("/clientes/disable/{idClient}", name="profesional_clientes_disable")
     */
    public function diableclientAction($idClient)
    {
        $em = $this->getDoctrine()->getManager();
        $client = $em->getRepository('UserClientBundle:Client')->find($idClient);
        $user = $this->get('security.context')->getToken()->getUser();

        if ($client->getProfessional()->getId() != $user->getProfessional()->getId()) {
            throw new \Exception('Hack attemp :(');
        }

        $client->getUser()->setEnabled(false);
        $em->persist($client->getUser());

        $em->flush();

        return $this->redirect($this->generateUrl('profesional_clientes'));
    }

    private function generateRandomString($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }


    /**
     * @Route("/descarga-analitica/{id}", name="professional_recursos_decarga_analitica")
     */
    public function recursosdescarganaliticaAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $analitica = $em->getRepository('UserProfesionalBundle:Analitica')->find($id);

        $response = $this->renderView('UserClientBundle:Default:analiticapdf.html.twig', array('analitica' => $analitica, 'paciente' => $user));

        //return new Response($response);
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($response),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="peticion_analitica.pdf"'
            )
        );

    }

    /**
     * @Route("/descarga-citologia/{id}", name="professinal_recursos_decarga_citologia")
     */
    public function recursosdescargancitologiaAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $citologia = $em->getRepository('UserProfesionalBundle:Citologia')->find($id);

        $response = $this->renderView('UserClientBundle:Default:citologiapdf.html.twig', array('citologia' => $citologia, 'paciente' => $user));


        //return new Response($response);
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($response),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="peticion_citologia.pdf"'
            )
        );

    }

    /**
     * @Route("/descarga-urodinamico/{id}", name="professinal_recursos_decarga_urodinamico")
     */
    public function recursosdescargaurodinamicoAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $urodinamico = $em->getRepository('UserProfesionalBundle:Urodinamico')->find($id);

        $response = $this->renderView('UserClientBundle:Default:urodinamicopdf.html.twig', array('urodinamico' => $urodinamico, 'paciente' => $user));


        //return new Response($response);
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($response),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="peticion_estudio_urodinamico.pdf"'
            )
        );

    }

    /**
     * @Route("/descarga-radiografia/{id}", name="professinal_recursos_decarga_radiografia")
     */
    public function recursosdescargancradiografiaAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $radiografia = $em->getRepository('UserProfesionalBundle:Radiografia')->find($id);


        $response = $this->renderView('UserClientBundle:Default:radiografiapdf.html.twig', array('radiografia' => $radiografia, 'paciente' => $user));


        //return new Response($response);
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($response),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="peticion_radiografia.pdf"'
            )
        );

    }

    /**
     * @Route("/descarga-receta/{id}", name="professinal_recursos_decarga_receta")
     */
    public function recursosdescargarecetaAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $receta = $em->getRepository('UserProfesionalBundle:Receta')->find($id);

        $response = $this->renderView('UserClientBundle:Default:recetapdf.html.twig', array('receta' => $receta, 'paciente' => $user));


        //return new Response($response);
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($response),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="receta.pdf"'
            )
        );

    }

    /**
     * @Route("/clientes/delete-resources/{nameResource}/{id}", name="profesional_clientes_delete_resources")
     * @Template()
     */
    public function deleteresourceAction($nameResource, $id)
    {

        if($nameResource == 'Receta' || $nameResource == "Citologia" || $nameResource == "Analitica" || $nameResource == "Urodinamico" || $nameResource == "Radiografia"){

            $em = $this->getDoctrine()->getManager();
            $resource = $em->getRepository("UserProfesionalBundle:".$nameResource)->find($id);
            $clientId = $resource->getClient()->getId();
            $em->remove($resource);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Recurso eliminado con éxito');

            return $this->redirect($this->generateUrl("profesional_clientes_show", array('idCliente' => $clientId)));
        }else{
            throw new \Exception('Bad request');
        }
    }


}