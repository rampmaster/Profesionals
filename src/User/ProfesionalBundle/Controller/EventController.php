<?php

namespace User\ProfesionalBundle\Controller;

use Core\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use User\ProfesionalBundle\Entity\ProfessionalEvent;
use User\ProfesionalBundle\Form\ProfessionalEventType;


class EventController extends Controller
{
    /**
     * @Route("/calendario", name="profesional_calendar")
     * @Template()
     */
    public function calendarioAction()
    {

        return array();
    }

    /**
     * @Route("/add-event", name="profesional_add_event")
     * @Template()
     */
    public function addeventAction()
    {
        $event = new ProfessionalEvent();
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $event->setProfessional($user->getProfessional());

        $form = $this->createForm(new ProfessionalEventType(), $event);

        $request = $this->getRequest();

        if($request->getMethod() == 'POST'){



            $form->bind($request);

            $professional = $user->getProfessional();

            $professional->addEvent($event);
            $event->setProfessional($professional);

            $em->persist($professional);
            $em->persist($event);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Evento añadido con éxito');
            return $this->redirect($this->generateUrl('profesional_calendar'));

        }

        return array('form' => $form->createView());
    }
    /**
     * @Route("/show-event/{idEvent}", name="profesional_show_event")
     * @Template()
     */
    public function showeventAction($idEvent)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('UserProfesionalBundle:ProfessionalEvent')->find($idEvent);


        return array('entity' => $event);

    }
}