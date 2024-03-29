<?php

namespace User\ProfesionalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GoalEvent
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class GoalEvent
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;


    /**
     * @var boolean
     *
     * @ORM\Column(name="reached", type="boolean")
     */
    private $reached = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reached_at", type="datetime", nullable=true)
     */
    private $reachedAt;

    //Relations

    /**
     * @ORM\ManyToOne(targetEntity="ProfessionalEvent", inversedBy="goals")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;


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
     * Set title
     *
     * @param string $title
     * @return GoalEvent
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return GoalEvent
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set reachedAt
     *
     * @param \DateTime $reachedAt
     * @return GoalEvent
     */
    public function setReachedAt($reachedAt)
    {
        $this->reachedAt = $reachedAt;

        return $this;
    }

    /**
     * Get reachedAt
     *
     * @return \DateTime 
     */
    public function getReachedAt()
    {
        return $this->reachedAt;
    }

    /**
     * Set event
     *
     * @param \User\ProfesionalBundle\Entity\ProfessionalEvent $event
     * @return GoalEvent
     */
    public function setEvent(\User\ProfesionalBundle\Entity\ProfessionalEvent $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \User\ProfesionalBundle\Entity\ProfessionalEvent 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set reached
     *
     * @param boolean $reached
     * @return GoalEvent
     */
    public function setReached($reached)
    {
        $this->reached = $reached;

        return $this;
    }

    /**
     * Get reached
     *
     * @return boolean 
     */
    public function getReached()
    {
        return $this->reached;
    }
}
