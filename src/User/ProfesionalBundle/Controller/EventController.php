<?php

namespace User\ProfesionalBundle\Controller;

use Core\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use User\ProfesionalBundle\Entity\ProfesionalEvent;
use User\ProfesionalBundle\Form\ProfesionalEventType;


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
        $event = new ProfesionalEvent();
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $form = $this->createForm(new ProfesionalEventType(), $event);

        $request = $this->getRequest();

        if($request->getMethod() == 'POST'){



            $form->bind($request);

            $professional = $user->getProfessional();

            $professional->addEvent($event);
            $event->setProfessional($professional);

            $em->persist($professional);
            $em->persist($event);
            $em->flush();

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


        return array();

    }
}