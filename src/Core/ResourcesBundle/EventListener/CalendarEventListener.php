<?php
namespace Core\ResourcesBundle\EventListener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;

class CalendarEventListener
{
    private $entityManager;
    private $router;
    private $user;

    public function __construct(EntityManager $entityManager, $router, $securityContext)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
        if (is_object($securityContext->getToken())) {
            $this->user = $securityContext->getToken()->getUser();
        }
    }

    public function loadEvents(CalendarEvent $calendarEvent)
    {
        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();

// load events using your custom logic here,
// for instance, retrieving events from a repository

        $companyEvents = $this->entityManager->getRepository('UserProfesionalBundle:ProfessionalEvent')
            ->createQueryBuilder('company_events')
            ->where('company_events.start_date BETWEEN :startDate and :endDate AND company_events.client = :user')
            ->setParameter('user', $this->user->getProfessional()->getId())
            ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
            ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))
            ->getQuery()->getResult();


        foreach ($companyEvents as $companyEvent) {

// create an event with a start/end time, or an all day event

            $interval = new \DateInterval($companyEvent->getDuration());
            $enddate = $companyEvent->getStartDate()->add($interval);
            $eventEntity = new EventEntity($companyEvent->getClient()->getUser()->getName() . " " . $companyEvent->getClient()->getUser()->getSurname(), $companyEvent->getStartDate(), $enddate);


//optional calendar event settings
            $eventEntity->setAllDay(true); // default is false, set to true if this is an all day event
            $eventEntity->setBgColor('#FF0000'); //set the background color of the event's label
            $eventEntity->setFgColor('#FFFFFF'); //set the foreground color of the event's label
            $eventEntity->setUrl($this->router->generate('profesional_show_event', array('idEvent' => $companyEvent->getId()))); // url to send user to when event label is clicked
            $eventEntity->setCssClass('consulta'); // a custom class you may want to apply to event labels

//finally, add the event to the CalendarEvent for displaying on the calendar
            $calendarEvent->addEvent($eventEntity);
        }
    }
}