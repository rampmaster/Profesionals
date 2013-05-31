<?php

namespace User\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Client
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
     * @ORM\Column(name="alias", type="string", length=255)
     */
    private $alias;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_visit", type="datetimetz")
     */
    private $lastVisit;

    //RELATIONS

    /**
     * @ORM\OneToOne(targetEntity="\Core\UserBundle\Entity\User", inversedBy="client", cascade={ "all" })
     **/
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="\User\ProfesionalBundle\Entity\Report", mappedBy="client")
     */
    private $reports;

    /**
     * @ORM\OneToMany(targetEntity="\User\ProfesionalBundle\Entity\ProfesionalEvent", mappedBy="client")
     */
    private $events;

    public function __toString(){

        return $this->getUser()->getName()." ".$this->getUser()->getSurname();
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
     * Set alias
     *
     * @param string $alias
     * @return Client
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    
        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set lastVisit
     *
     * @param \DateTime $lastVisit
     * @return Client
     */
    public function setLastVisit($lastVisit)
    {
        $this->lastVisit = $lastVisit;
    
        return $this;
    }

    /**
     * Get lastVisit
     *
     * @return \DateTime 
     */
    public function getLastVisit()
    {
        return $this->lastVisit;
    }

    /**
     * Set user
     *
     * @param \Core\UserBundle\Entity\User $user
     * @return Client
     */
    public function setUser(\Core\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Core\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reports = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add reports
     *
     * @param \User\ProfesionalBundle\Entity\Report $reports
     * @return Client
     */
    public function addReport(\User\ProfesionalBundle\Entity\Report $reports)
    {
        $this->reports[] = $reports;
    
        return $this;
    }

    /**
     * Remove reports
     *
     * @param \User\ProfesionalBundle\Entity\Report $reports
     */
    public function removeReport(\User\ProfesionalBundle\Entity\Report $reports)
    {
        $this->reports->removeElement($reports);
    }

    /**
     * Get reports
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReports()
    {
        return $this->reports;
    }

    /**
     * Add events
     *
     * @param \User\ProfesionalBundle\Entity\ProfesionalEvent $events
     * @return Client
     */
    public function addEvent(\User\ProfesionalBundle\Entity\ProfesionalEvent $events)
    {
        $this->events[] = $events;
    
        return $this;
    }

    /**
     * Remove events
     *
     * @param \User\ProfesionalBundle\Entity\ProfesionalEvent $events
     */
    public function removeEvent(\User\ProfesionalBundle\Entity\ProfesionalEvent $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvents()
    {
        return $this->events;
    }
}