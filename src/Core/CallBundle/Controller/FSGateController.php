<?php

namespace Core\CallBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class FSGateController extends Controller
{
    /**
     * @Route("/fsgate/Retrieve-Directory", name="core_call_fsgate_retrievedirectory")
     */
    public function retrievedirectoryAction()
    {
        $this->checkHosts();

        $em = $this->getDoctrine()->getEntityManager();

        $request = $this->getRequest();
        $userId = $request->get('user');
        $user = $em->getRepository('CoreUserBundle:User')->find($userId); //lista completa de usuarios

        //RESPONSE
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'text/xml');

        $cached_data = $this->render('CoreCallBundle:FSGate:retrievedirectory.xml.twig', array(
            'user' => $user
        ), $response);

        return $cached_data;


    }

    /**
     * @Route("/core/call/resources/config-xml", name="core_call_resources_config_xml")
     */
    public function configAction()
    {
        //return xml response

        $server = $this->container->getParameter('sipRtmpGateway');
        $port = $this->container->getParameter('sipRtmpGatewayPort');


        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'text/xml');
        $cached_data = $this->render('CoreCallBundle:FSGate:flashphoner.xml.twig', array(
            'server' => $server,
            'port' => $port
        ), $response);

        return $cached_data;

    }

    private function checkHosts()
    {
        $agent = new \Core\UserBundle\Request\Agent($this->getRequest()->server->get('HTTP_USER_AGENT'));

        $servers = array(
            '176.31.230.58',
            'sip.medibaby.net',
            '178.33.161.91',
            '94.23.250.202', //beta
            '127.0.0.1'

        );

        if (!in_array($agent->getIp(), $servers)) {

            throw new \Exception('Hack attemp :(');
        }
    }
}
