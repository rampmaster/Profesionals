<?php

namespace User\ProfesionalBundle\Controller;

use xCore\UserBundle\Form\UserType;
use Core\UserBundle\Request\Agent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Response;
use User\ProfesionalBundle\Entity\Report;
use User\ProfesionalBundle\Entity\Skill;
use User\ProfesionalBundle\Form\ReportType;
use User\ProfesionalBundle\Form\StylesType;
use User\ProfesionalBundle\Entity\Styles;
use User\ProfesionalBundle\Form\WelcomeType;

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
                    ->setSubject('Bienvenido/a a Centro Gil Vernet')
                    ->setFrom('centrogilvernet@gmail.com')
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
     * @Route("/personalizar-inicio", name="profesional_custom_home")
     * @Template()
     */
    public function personalizarinicioAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $form_welcome = $this->createForm(new WelcomeType($user->getProfessional()));
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        if($request->getMethod() == 'POST'){
            $form_welcome->bind($request);

            if($form_welcome->isValid()){



                $data = $form_welcome->getData();

                //añado los datos personales

                $user->getProfessional()->setPublicPhone($data['phone']);
                $user->getProfessional()->setPublicDirection($data['direction']);
                $user->getProfessional()->setPublicCity($data['city']);
                $user->getProfessional()->setPublicPostal($data['postal']);


                //me peleo con las skills


                $skillsraw = $data['skills'];

                $controlSkillsIds = array();
                //consigo todos los ids de los skills actuales
                foreach($user->getProfessional()->getSkills() as $s){
                    array_push($controlSkillsIds, $s->getId());
                }

                $updateSkillsIds = array();
                $createSkillsKeys = array();
                //separo los skills segun si estan creados o hay que crearlos
                foreach($skillsraw as $s){
                    if(is_numeric($s)){
                        //es una entidad skill, la añado
                        array_push($updateSkillsIds, $s);
                    }else{
                        //no es una entidad asi que tengo que creala
                        array_push($createSkillsKeys, $s);
                    }
                }

                $updateSkillsCreate = array_diff($updateSkillsIds, $controlSkillsIds);
                $updateSkillsIdsDelete = array_diff($controlSkillsIds, $updateSkillsIds);

                foreach($updateSkillsCreate as $c){
                    $skillObject = $em->getRepository('UserProfesionalBundle:Skill')->find($c);

                    $skillObject->addProfessional($user->getProfessional());
                    $user->getProfessional()->addSkill($skillObject);



                    $em->persist($skillObject);
                    $em->persist($user->getProfessional());
                }

                foreach($updateSkillsIdsDelete as $c){
                    $skillObject = $em->getRepository('UserProfesionalBundle:Skill')->find($c);

                    $skillObject->removeProfessional($user->getProfessional());
                    $user->getProfessional()->removeSkill($skillObject);



                    $em->persist($skillObject);
                    $em->persist($user->getProfessional());
                }

                foreach($createSkillsKeys as $k){
                    $skillObject = new Skill();
                    $skillObject->setCreatedAt(new \DateTime());
                    $skillObject->setName($k);
                    $skillObject->setDescription('Not defined');
                    $skillObject->addRankingProfessional();

                    $em->persist($skillObject);
                    $skillObject->addProfessional($user->getProfessional());

                    $user->getProfessional()->addSkill($skillObject);

                    $em->persist($skillObject);
                    $em->persist($user->getProfessional());
                }

                $em->flush();



                $form_welcome = $this->createForm(new WelcomeType($user->getProfessional()));

                $this->get('session')->getFlashBag()->add('notice', 'Información guardada con éxito.');

            }
        }

        return array('form_welcome' => $form_welcome->createView(), 'professional' => $user->getProfessional());
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
        /*
        $salesManager = $this->get('sales_manager');


        if(!$salesManager->checkIsAvailable('basic_plan')){
            $this->get('sales_manager')->promoProduct('basic_plan','P3M');
            //throw new \Exception('Producto no disponible');

        }



        if (!$useragent->checkCapable()) {
            return $this->render('::browsernotsupported.html.twig');
            throw new \Exception('Explorador no soportado');

        }
        */

        return array();
    }

    /**
     * @Route("/retrieve-json-skills", name="profesional_json_skills")
     */
    public function retriebejsonskillsAction()
    {
        $query = $this->getRequest()->get('query');

        $result = $this->getDoctrine()->getManager()->createQuery(
            'SELECT s.id, s.name FROM UserProfesionalBundle:Skill s WHERE s.name LIKE :name'
        )
            ->setParameter('name', "%".$query."%")
            ->getResult();

        return new Response(json_encode($result));

    }

    /**
     * @Route("/clientes/add-report-ajax/{idCliente}", name="profesional_clientes_add_report_ajax")
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
     * @Route("/test-email", name="profesional_test_email")
     * @Template()
     */
    public function testemailAction()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Bienvenido/a a Centro Gil Vernet')
            ->setFrom('centrogilvernet@gmail.com')
            ->setTo('ivan.ruiz.delatorre@gmail.com')
            ->setBody('test');

        $this->get('mailer')->send($message);

        return array();

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
