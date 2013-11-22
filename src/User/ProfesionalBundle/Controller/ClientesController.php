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
        $user = $this->get('security.context')->getToken()->getUser();
        $clients = $em->createQuery("Select c FROM UserClientBundle:Client c JOIN c.user u WHERE c.professional = :profesional AND u.enabled = TRUE ORDER BY u.name DESC")->setParameter("profesional" , $user->getProfessional()->getId())->getResult();
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

        if(!$professional){
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
        return array('form' => $form->createView(),'cliente'=>$client);
    }

    /**
     * @Route("/clientes/add", name="profesional_clientes_add")
     * @Template()
     */
    public function addAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $professional = $user->getProfessional();

        if(!$professional){
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
                    ->setSubject($owner->getName().' '.$owner->getSurname().' le ha añadido a su plataforma')
                    ->setFrom('noreply@varavan.com')
                    ->setTo($user->getEmail())
                    ->setBody($this->renderView(
                        'UserProfesionalBundle:Email:client_signup.html.twig',
                            array('user'=>$owner,'client'=>$user,'pass'=>$plainPassword)),'text/html');
                
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

        if($request->getMethod() == 'POST'){
            $form->bind($request);

            if($form->isValid()){

                $report->setCreatedAt(new \DateTime());
                $client->addReport($report);

                $em->persist($report);
                $em->persist($client);
                $em->flush();

                return $this->redirect($this->generateUrl('profesional_clientes_show', array('idCliente' => $idCliente)));
            }
        }

        return array('form' => $form->createView(),'cliente'=>$client);
    }

    /**
     * @Route("/clientes/disable/{idClient}", name="profesional_clientes_disable")
     */
    public function diableclientAction($idClient)
    {
        $em = $this->getDoctrine()->getManager();
        $client = $em->getRepository('UserClientBundle:Client')->find($idClient);
        $user = $this->get('security.context')->getToken()->getUser();

        if($client->getProfessional()->getId() != $user->getProfessional()->getId()){
            throw new \Exception('Hack attemp :(');
        }

        $client->getUser()->setEnabled(false);
        $em->persist($client->getUser());

        $em->flush();

        return $this->redirect($this->generateUrl('profesional_clientes'));
    }

    private function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
    }


}