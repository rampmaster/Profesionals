<?php

namespace User\ProfesionalBundle\Controller;

use Core\UserBundle\Form\UserType;
use Core\UserBundle\Request\Agent;
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
     * @Route("/consulta", name="profesional_consulta")
     * @Template()
     */
    public function consultaAction()
    {

        $useragent = new Agent();
        $salesManager = $this->get('sales_manager');

        if(!$salesManager->checkIsAvailable('plan_test')){
            throw new \Exception('Producto no disponible');
        }

        if (!$useragent->checkCapable()) {
            return $this->render('::browsernotsupported.html.twig');
            throw new \Exception('Explorador no soportado');

        }

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
        $em = $this->getDoctrine()->getManager();

        $id = $this->getRequest()->get('idUser');
        $user = $em->getRepository('CoreUserBundle:User')->find($id);

        return array('entity' => $user->getClient());
    }
}
