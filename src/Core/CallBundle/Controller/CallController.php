<?php

namespace Core\CallBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/core/call")
 */
class CallController extends Controller
{
    /**
     * @Route("/Notify/{productSymbol}/{idProvider}", name="core_call_notify_call")
     * @Route("/Notify/{productSymbol}/", name="core_call_notify_call_base")
     */
    public function notifycallAction($productSymbol, $idProvider)
    {
        $salesManager = $this->get('sales_manager');

        //esto me devuelve la visita ya incializada, y conmigo puesto o la visita que tengo activa
        $visit = $salesManager->spendProduct($productSymbol);

        //seteo el provider
        $provider = $this->getDoctrine()->getEntityManager()->getRepository('CoreUserBundle:User')->find($idProvider);

        $salesManager->setProvider($visit, $provider); //esto setea el provider.

        return new \Symfony\Component\HttpFoundation\Response("ok");
    }

    /**
     * @Route("/test")
     * @Template()
     */
    public function testAction()
    {

        return array();
    }

    /**
     * @Route("/test-html5")
     * @Template()
     */
    public function testhtml5Action()
    {

        return array();
    }

    /**
     * @Route("/Retrieve-Info-User", name="core_call_retrieve_info_user")
     * @Method("POST")
     */
    public function retrieveinfouserAction()
    {
        //devuelvo un json con la info de usurio
        $user = $this->get('security.context')->getToken()->getUser();
        if (isset($user)) {

            $idUser = $this->getRequest()->get('id');
            $user = $this->getDoctrine()->getManager()->getRepository('CoreUserBundle:User')->find($idUser);
            /*

            $array = array(
                'isSchool' => $user->hasRole('ROLE_SCHOOL'),
                'isDoctor' => $user->hasRole('ROLE_DOCTOR'),
                'isPatient' => $user->hasRole('ROLE_PATIENT'),
                'isOperator' => $user->hasRole('ROLE_CENTER'),
                'profile_image' => "/" . $user->getWebPath(),
            );
            */
            $array['profile_image'] = "/" . $user->getWebPath();
            $array['fullname'] = $user->getName() . " " . $user->getSurname();
            $array['email'] = $user->getEmail();

            return new \Symfony\Component\HttpFoundation\Response(json_encode($array));
        } else {
            return new \Symfony\Component\HttpFoundation\Response('Error no user');
        }
    }

    /**
     * @Route("/contacts")
     * @Template()
     */
    public function contactsAction($consulta = false, $iOSApp = false)
    {

        $security = $this->get('security.context');
        $me = $security->getToken()->getUser();

        $em = $this->getDoctrine()->getEntityManager();
        $min = new \DateTime();

        $users = $em->getRepository('CoreUserBundle:User')->findAll();


        return array('users' => $users, 'numUsers' => count($users));
    }

    /**
     * @Route("/controls")
     * @Template()
     */
    public function controlsAction()
    {

        return array();
    }

    /**
     * @Route("/notifications")
     * @Template()
     */
    public function notificationsAction()
    {

        return array();
    }

    /**
     * @Route("/videoconference")
     * @Template()
     */
    public function videoconferenceAction()
    {

        return array();
    }

    /**
     * @Route("/chat")
     * @Template()
     */
    public function chatAction()
    {

        return array();
    }

}
