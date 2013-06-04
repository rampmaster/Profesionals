<?php

namespace User\ProfesionalBundle\Entity;

use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProfesionalEvent
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ProfessionalEvent
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $start_date;


    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="string")
     */
    private $duration;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notify", type="boolean")
     */
    private $notify;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="notify_at", type="datetime")
     */
    private $notify_at;



    /**
     * @var \DateTime
     *
     * @ORM\Column(name="notified_at", type="datetime", nullable=true)
     */
    private $notified_at;


    /**
     * @var string
     *
     * @ORM\Column(name="client_access_token", type="string", length=255)
     */
    private $client_access_token;

    /**
     * @var \DateTime
     *
     * //si es un usuario invitado, guardamos aqui su información
     *
     * @ORM\Column(name="guess", type="datetime", nullable=true)
     */
    private $guess;

    //RELATIONS


    /**
     * @ORM\ManyToOne(targetEntity="Professional", inversedBy="events")
     * @ORM\JoinColumn(name="professional_id", referencedColumnName="id")
     */
    private $professional;


    /**
     * @ORM\ManyToOne(targetEntity="\User\ClientBundle\Entity\Client", inversedBy="events")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;


    public function __construct(){

        $this->client_access_token = md5(uniqid());
    }

    public function setHourDate($hour){
        $this->start_date->setTime($hour->format('H'), $hour->format('i'));
    }
    public function isHourDate(){
        $buffer = new \DateTime();
        $buffer->setTime('12', '00');
        return $buffer;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set start_date
     *
     * @param \DateTime $startDate
     * @return ProfesionalEvent
     */
    public function setStartDate($startDate)
    {
        $this->start_date = $startDate;
    
        return $this;
    }

    /**
     * Get start_date
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Set duration
     *
     * @param string $duration
     * @return ProfesionalEvent
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    
        return $this;
    }

    /**
     * Get duration
     *
     * @return string 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set notify
     *
     * @param boolean $notify
     * @return ProfesionalEvent
     */
    public function setNotify($notify)
    {
        $this->notify = $notify;
    
        return $this;
    }

    /**
     * Get notify
     *
     * @return boolean 
     */
    public function getNotify()
    {
        return $this->notify;
    }

    /**
     * Set notify_at
     *
     * @param \DateTime $notifyAt
     * @return ProfesionalEvent
     */
    public function setNotifyAt($notifyAt)
    {

        if(is_string($notifyAt)){
            //es un date interval, hay que restarselo al inicio
            $interval = new \DateInterval($notifyAt);

            $date = $this->getStartDate()->sub($interval);

            $this->notify_at = $date;
        }else{
        $this->notify_at = $notifyAt;
        }
    
        return $this;
    }

    /**
     * Get notify_at
     *
     * @return \DateTime 
     */
    public function getNotifyAt()
    {
        return $this->notify_at;
    }

    /**
     * Set notified_at
     *
     * @param \DateTime $notifiedAt
     * @return ProfesionalEvent
     */
    public function setNotifiedAt($notifiedAt)
    {
        $this->notified_at = $notifiedAt;
    
        return $this;
    }

    /**
     * Get notified_at
     *
     * @return \DateTime 
     */
    public function getNotifiedAt()
    {
        return $this->notified_at;
    }

    /**
     * Set client_access_token
     *
     * @param string $clientAccessToken
     * @return ProfesionalEvent
     */
    public function setClientAccessToken($clientAccessToken)
    {
        $this->client_access_token = $clientAccessToken;
    
        return $this;
    }

    /**
     * Get client_access_token
     *
     * @return string 
     */
    public function getClientAccessToken()
    {
        return $this->client_access_token;
    }

    /**
     * Set guess
     *
     * @param \DateTime $guess
     * @return ProfesionalEvent
     */
    public function setGuess($guess)
    {
        $this->guess = $guess;
    
        return $this;
    }

    /**
     * Get guess
     *
     * @return \DateTime 
     */
    public function getGuess()
    {
        return $this->guess;
    }

    /**
     * Set professional
     *
     * @param \User\ProfesionalBundle\Entity\Professional $professional
     * @return ProfesionalEvent
     */
    public function setProfessional(\User\ProfesionalBundle\Entity\Professional $professional = null)
    {
        $this->professional = $professional;
    
        return $this;
    }

    /**
     * Get professional
     *
     * @return \User\ProfesionalBundle\Entity\Professional 
     */
    public function getProfessional()
    {
        return $this->professional;
    }

    /**
     * Set client
     *
     * @param \User\ClientBundle\Entity\Client $client
     * @return ProfesionalEvent
     */
    public function setClient(\User\ClientBundle\Entity\Client $client = null)
    {
        $this->client = $client;
    
        return $this;
    }

    /**
     * Get client
     *
     * @return \User\ClientBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }
}