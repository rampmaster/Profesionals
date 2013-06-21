<?php

namespace User\ProfesionalBundle\Controller;

use Core\UserBundle\Form\UserType;
use Core\UserBundle\Request\Agent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Response;
use User\ProfesionalBundle\Entity\Report;
use User\ProfesionalBundle\Form\ReportType;
use User\ProfesionalBundle\Form\StylesType;
use User\ProfesionalBundle\Entity\Styles;

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
     * @Route("/test", name="profesional_test")
     */
    public function testAction()
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('CoreUserBundle:User')->find(5);

        $data = array('user'=>$this->get('security.context')->getToken()->getUser(),'client'=>$user,'pass'=>'xQfyCd4');
        return $this->render('UserProfesionalBundle:Email:signup.html.twig',$data);
        
    }

    /**
     * @Route("/planes", name="profesional_plans")
     * @Template()
     */
    public function plansAction()
    {
        $salesmanager = $this->get('sales_manager');

        $check = $salesmanager->checkIsAvailable('basic_plan');

        return array('plan_available' => $check);
    }

    /**
     * @Route("/Buy-Product/{symbol}", name="profesional_buy_product", defaults={"symbol" = "noplan"})
     */
    public function buyproductAction($symbol)
    {

        $salesManager = $this->get('sales_manager');

        if ($symbol != 'noplan') {


            $trans = $salesManager->buyProduct($symbol);

            /*
                        $bridgeModel = new \Core\SalesBundle\Manager\BridgeManager();

                        $transaction = $bridgeModel->emulateTransaction($trans['transaction'],$trans['method']);

                        $salesManager->transactionComplete($transaction);

                        print_r($transaction);

                        return new \Symfony\Component\HttpFoundation\Response('Ok');
            */
            return $this->render($trans['method']->getViewRedirect(), array('transaction' => $trans['transaction'], 'numCreditsAvailable' => $salesManager->getCredits('count')));

        }

        throw new \Exception('No product selected');
    }

    /**
     * @Route("/crear-y-personalizar", name="profesional_first_style")
     * @Template()
     */
    public function firstStyleAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usermanager = $this->get('fos_user.user_manager');
        $user = $this->get('security.context')->getToken()->getUser();
        $professional = $user->getProfessional();
        $username = $user->getUsername();
        if(!$professional){
            throw new \Exception("Error cargando perfil.");
        }

        $styles = $professional->getStyles();
        if(!$styles){
            $styles = new Styles();
            $styles->setProfessional($professional);
            $professional->setStyles($styles);
            $styles->setCreatedAt(new \DateTime());
        }
        $plainPassword = $this->generateRandomString();

        $form = $this->createForm(new StylesType(), $styles);
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $styles->setUpdatedAt(new \DateTime());
                $styles->upload($username);
                $user->setPlainPassword($plainPassword);
                $usermanager->updateUser($user);

                $em->persist($styles);
                $em->persist($professional);
                $em->flush();

                $message = \Swift_Message::newInstance()
                    ->setSubject('Bienvenido/a a Varavan.com')
                    ->setFrom('noreply@varavan.com')
                    ->setTo($user->getEmail())
                    ->setBody($this->renderView(
                        'UserProfesionalBundle:Email:signup.html.twig',
                            array('user'=>$user,'pass'=>$plainPassword)),'text/html');
                
                $this->get('mailer')->send($message);

                $this->get('sales_manager')->promoProduct('basic_plan','P3M');
                $this->get('session')->getFlashBag()->add('notice', 'Tu plataforma se ha creado con éxito');
                return $this->redirect($this->generateUrl('profesional_consulta'));
            }
        }


        return array('form' => $form->createView());
    }

    /**
     * @Route("/personalizar", name="profesional_style")
     * @Template()
     */
    public function stylesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usermanager = $this->get('fos_user.user_manager');
        $user = $this->get('security.context')->getToken()->getUser();
        $professional = $user->getProfessional();
        $username = $user->getUsername();
        if(!$professional){
            throw new \Exception("Error cargando perfil.");
        }

        $styles = $professional->getStyles();
        if(!$styles){
            $styles = new Styles();
            $styles->setProfessional($professional);
            $professional->setStyles($styles);
            $styles->setCreatedAt(new \DateTime());
        }


        $form = $this->createForm(new StylesType(), $styles);
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $styles->setUpdatedAt(new \DateTime());
                $styles->upload($username);

                //recojo el usuario y hago update de el

                $usermanager->updateUser($styles->getProfessional()->getUser());
                $em->persist($styles);
                $em->persist($professional);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Tu plataforma se ha actualizado con éxito');
                return $this->redirect($this->generateUrl('profesional_consulta'));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/consulta", name="profesional_consulta")
     * @Template()
     */
    public function consultaAction()
    {

        $user = $this->get('security.context')->getToken()->getUser();
        if(!$user){
            throw new \Exception("User not found");
        }
        $professional = $user->getProfessional();
        if(!$professional){
            throw new \Exception("Profile not found");
        }
        $styles = $professional->getStyles();
        if(!$styles){
            return $this->redirect($this->generateUrl('profesional_first_style'));
        }

        $useragent = new Agent();
        $salesManager = $this->get('sales_manager');

        if(!$salesManager->checkIsAvailable('basic_plan')){
            $this->get('sales_manager')->promoProduct('basic_plan','P3M');
            //throw new \Exception('Producto no disponible');

        }

        if (!$useragent->checkCapable()) {
            return $this->render('::browsernotsupported.html.twig');
            throw new \Exception('Explorador no soportado');

        }

        return array();
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

                return new Response('Ok');
            }
        }

        return new Response('Error');
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
                $this->get('session')->getFlashBag()->add('notice', 'Información guardada con éxito.');
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
        $me = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $id = $this->getRequest()->get('idUser');
        $user = $em->getRepository('CoreUserBundle:User')->find($id);

        $report = new Report();
        $report->setCreatedAt(new \DateTime());
        $report->setClient($user->getClient());
        $report->setProfessional($me->getProfessional());

        $form = $this->createForm(new ReportType(), $report);

        return array('entity' => $user->getClient(), 'form' => $form->createView());
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
