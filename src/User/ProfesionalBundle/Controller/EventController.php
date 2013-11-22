<?php

namespace User\ProfesionalBundle\Controller;

use Core\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use User\ProfesionalBundle\Entity\GoalEvent;
use User\ProfesionalBundle\Entity\ProfessionalEvent;
use User\ProfesionalBundle\Form\GoalEventType;
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
     * @Route("/delete-event/{idEvent}", name="profesional_delete_event")
     */
    public function deleteeventAction($idEvent)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('UserProfesionalBundle:ProfessionalEvent')->find($idEvent);

        $user = $this->get('security.context')->getToken()->getUser();
        $professional = $user->getProfessional();

        $professional->getEvents()->removeElement($event);

        $em->remove($event);
        $em->persist($professional);
        $em->flush();

        return $this->redirect($this->generateUrl("profesional_calendar"));

    }

        /**
     * @Route("/show-event/{idEvent}", name="profesional_show_event")
     * @Template()
     */
    public function showeventAction($idEvent)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('UserProfesionalBundle:ProfessionalEvent')->find($idEvent);

        $goal = new GoalEvent();
        $goal->setCreatedAt(new \DateTime());
        $goal->setEvent($event);
        $goalform = $this->createForm(new GoalEventType(), $goal);

        $request = $this->getRequest();

        if($request->getMethod() == 'POST'){

            $goalform->bind($request);
            if($goalform->isValid()){
                $event->addGoal($goal);

                $em->persist($event);
                $em->persist($goal);

                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Objetivo añadido con éxito');

                //recreo el form
                $goal = new GoalEvent();
                $goal->setCreatedAt(new \DateTime());
                $goal->setEvent($event);
                $goalform = $this->createForm(new GoalEventType(), $goal);

            }


        }

        return array('entity' => $event, 'goalform' => $goalform->createView());

    }

    /**
     * @Route("/update-goal", name="profesional_event_update_goal")
     *
     * params post
     *
     *  goal -> id del objetivo
     *  action -> REACHED, CHANGE TITLE, DELETE
     *  param -> parametro para
     *
     */
    public function updategoalAction()
    {

        $em = $this->getDoctrine()->getManager();

        $request = $this->getRequest();
        $user = $this->get('security.context')->getToken()->getUser();

        $goalID  = $request->get('goal');
        $action = $request->get('action');
        $param = $request->get('param');
        $redirect = $request->get('redirect');

        $goal = $em->getRepository('UserProfesionalBundle:GoalEvent')->find($goalID);

        if(!$goal OR $user->getId() != $goal->getEvent()->getProfessional()->getUser()->getId()){
            return new Response('false');
        }

        switch($action){

            case 'REACHED':
                    if($param == '1'){
                        $goal->setReached(true);
                        $goal->setReachedAt(new \DateTime());
                    }else{
                        $goal->setReached(false);
                    }

                    $em->persist($goal);
                    $em->flush();
                break;
            case 'CHANGE TITLE':
                    $goal->setTitle($param);



                    $em->persist($goal);
                    $em->flush();
                break;

            case "DELETE":
                    $event = $goal->getEvent();

                    $event->removeGoal($goal);

                    $em->persist($event);
                    $em->remove($goal);
                    $em->flush();
                break;
        }

        if(is_string($redirect)){
            return $this->redirect($redirect);
        }

        return new Response('okey');

    }
}