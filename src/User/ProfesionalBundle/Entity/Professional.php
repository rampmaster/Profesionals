<?php

namespace User\ProfesionalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Professional
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Professional
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
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="headline", type="string",nullable=true)
     */
    private $headline;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    //public data


    /**
     * @var string
     *
     * @ORM\Column(name="public_direction", type="string")
     */
    private $public_direction;


    /**
     * @var string
     *
     * @ORM\Column(name="public_city", type="string")
     */
    private $public_city;


    /**
     * @var string
     *
     * @ORM\Column(name="public_postal", type="string")
     */
    private $public_postal;


    /**
     * @var string
     *
     * @ORM\Column(name="public_phone", type="string")
     */
    private $public_phone;



    /**
     * @ORM\ManyToMany(targetEntity="Skill", inversedBy="professionals")
     * @ORM\JoinTable(name="professional_skill",
     *      joinColumns={@ORM\JoinColumn(name="professional_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="skill_id", referencedColumnName="id")}
     *      )
     */
    private $skills;

    /**
     * @ORM\OneToOne(targetEntity="\Core\UserBundle\Entity\User", inversedBy="professional", cascade={ "all" })
     **/
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="\User\ProfesionalBundle\Entity\Styles", mappedBy="professional", cascade={ "all" })
     **/
    private $styles;

    /**
     * @ORM\OneToMany(targetEntity="\User\ProfesionalBundle\Entity\Report", mappedBy="professional")
     */
    private $reports;

    /**
     * @ORM\OneToMany(targetEntity="ProfessionalEvent", mappedBy="professional")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="\User\ClientBundle\Entity\Client", mappedBy="professional")
     */
    private $clients;

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Professional
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Professional
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reports = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set user
     *
     * @param \Core\UserBundle\Entity\User $user
     * @return Professional
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
     * Add reports
     *
     * @param \User\ProfesionalBundle\Entity\Report $reports
     * @return Professional
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
     * Set styles
     *
     * @param \User\ProfesionalBundle\Entity\Styles $styles
     * @return Professional
     */
    public function setStyles(\User\ProfesionalBundle\Entity\Styles $styles = null)
    {
        $this->styles = $styles;

        return $this;
    }

    /**
     * Get styles
     *
     * @return \User\ProfesionalBundle\Entity\Styles 
     */
    public function getStyles()
    {
        return $this->styles;
    }

    /**
     * Add events
     *
     * @param \User\ProfesionalBundle\Entity\ProfessionalEvent $events
     * @return Professional
     */
    public function addEvent(\User\ProfesionalBundle\Entity\ProfessionalEvent $events)
    {
        $this->events[] = $events;
    
        return $this;
    }

    /**
     * Remove events
     *
     * @param \User\ProfesionalBundle\Entity\ProfessionalEvent $events
     */
    public function removeEvent(\User\ProfesionalBundle\Entity\ProfessionalEvent $events)
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

    /**
     * Add clients
     *
     * @param \User\ClientBundle\Entity\Client $clients
     * @return Professional
     */
    public function addClient(\User\ClientBundle\Entity\Client $clients)
    {
        $this->clients[] = $clients;
    
        return $this;
    }

    /**
     * Remove clients
     *
     * @param \User\ClientBundle\Entity\Client $clients
     */
    public function removeClient(\User\ClientBundle\Entity\Client $clients)
    {
        $this->clients->removeElement($clients);
    }

    /**
     * Get clients
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClients()
    {
        $b = array();
        foreach($this->clients as $c){
            if($c->getUser()->isEnabled()){
                array_push($b, $c);
            }
        }
        return $b;
    }

    /**
     * Set headline
     *
     * @param string $headline
     * @return Professional
     */
    public function setHeadline($headline)
    {
        $this->headline = $headline;

        return $this;
    }

    /**
     * Get headline
     *
     * @return string 
     */
    public function getHeadline()
    {
        return $this->headline;
    }


    /**
     * Add skills
     *
     * @param \User\ProfesionalBundle\Entity\Skill $skills
     * @return Professional
     */
    public function addSkill(\User\ProfesionalBundle\Entity\Skill $skills)
    {
        $this->skills[] = $skills;

        return $this;
    }

    /**
     * Remove skills
     *
     * @param \User\ProfesionalBundle\Entity\Skill $skills
     */
    public function removeSkill(\User\ProfesionalBundle\Entity\Skill $skills)
    {

        $this->skills->removeElement($skills);
    }

    /**
     * Get skills
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Set public_direction
     *
     * @param string $publicDirection
     * @return Professional
     */
    public function setPublicDirection($publicDirection)
    {
        $this->public_direction = $publicDirection;

        return $this;
    }

    /**
     * Get public_direction
     *
     * @return string 
     */
    public function getPublicDirection()
    {
        return $this->public_direction;
    }

    /**
     * Set public_city
     *
     * @param string $publicCity
     * @return Professional
     */
    public function setPublicCity($publicCity)
    {
        $this->public_city = $publicCity;

        return $this;
    }

    /**
     * Get public_city
     *
     * @return string 
     */
    public function getPublicCity()
    {
        return $this->public_city;
    }

    /**
     * Set public_postal
     *
     * @param string $publicPostal
     * @return Professional
     */
    public function setPublicPostal($publicPostal)
    {
        $this->public_postal = $publicPostal;

        return $this;
    }

    /**
     * Get public_postal
     *
     * @return string 
     */
    public function getPublicPostal()
    {
        return $this->public_postal;
    }

    /**
     * Set public_phone
     *
     * @param string $publicPhone
     * @return Professional
     */
    public function setPublicPhone($publicPhone)
    {
        $this->public_phone = $publicPhone;

        return $this;
    }

    /**
     * Get public_phone
     *
     * @return string 
     */
    public function getPublicPhone()
    {
        return $this->public_phone;
    }
}
