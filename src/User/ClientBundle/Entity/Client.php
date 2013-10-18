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
     * @ORM\OneToMany(targetEntity="\User\ProfesionalBundle\Entity\ProfessionalEvent", mappedBy="client")
     */
    private $events;

    /**
     * @ORM\ManyToOne(targetEntity="\User\ProfesionalBundle\Entity\Professional", inversedBy="clients")
     * @ORM\JoinColumn(name="professional_id", referencedColumnName="id")
     */
    private $professional;

    /**
     * @ORM\OneToMany(targetEntity="\User\ProfesionalBundle\Entity\Analitica", mappedBy="client")
     */
    private $analiticas;

    /**
     * @ORM\OneToMany(targetEntity="\User\ProfesionalBundle\Entity\Radiografia", mappedBy="client")
     */
    private $radiografias;

    /**
     * @ORM\OneToMany(targetEntity="\User\ProfesionalBundle\Entity\Citologia", mappedBy="client")
     */
    private $citologias;

    /**
     * @ORM\OneToMany(targetEntity="\User\ProfesionalBundle\Entity\Urodinamico", mappedBy="client")
     */
    private $urodinamicos;



    public function __toString()
    {

        return $this->getUser()->getName() . " " . $this->getUser()->getSurname();
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
     * @param \User\ProfesionalBundle\Entity\ProfessionalEvent $events
     * @return Client
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
     * Set professional
     *
     * @param \User\ProfesionalBundle\Entity\Professional $professional
     * @return Client
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
     * Add analiticas
     *
     * @param \User\ProfesionalBundle\Entity\Analitica $analiticas
     * @return Client
     */
    public function addAnalitica(\User\ProfesionalBundle\Entity\Analitica $analiticas)
    {
        $this->analiticas[] = $analiticas;

        return $this;
    }

    /**
     * Remove analiticas
     *
     * @param \User\ProfesionalBundle\Entity\Analitica $analiticas
     */
    public function removeAnalitica(\User\ProfesionalBundle\Entity\Analitica $analiticas)
    {
        $this->analiticas->removeElement($analiticas);
    }

    /**
     * Get analiticas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAnaliticas()
    {
        return $this->analiticas;
    }

    /**
     * Add radiografias
     *
     * @param \User\ProfesionalBundle\Entity\Radiografia $radiografias
     * @return Client
     */
    public function addRadiografia(\User\ProfesionalBundle\Entity\Radiografia $radiografias)
    {
        $this->radiografias[] = $radiografias;

        return $this;
    }

    /**
     * Remove radiografias
     *
     * @param \User\ProfesionalBundle\Entity\Radiografia $radiografias
     */
    public function removeRadiografia(\User\ProfesionalBundle\Entity\Radiografia $radiografias)
    {
        $this->radiografias->removeElement($radiografias);
    }

    /**
     * Get radiografias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRadiografias()
    {
        return $this->radiografias;
    }

    /**
     * Add citologias
     *
     * @param \User\ProfesionalBundle\Entity\Citologia $citologias
     * @return Client
     */
    public function addCitologia(\User\ProfesionalBundle\Entity\Citologia $citologias)
    {
        $this->citologias[] = $citologias;

        return $this;
    }

    /**
     * Remove citologias
     *
     * @param \User\ProfesionalBundle\Entity\Citologia $citologias
     */
    public function removeCitologia(\User\ProfesionalBundle\Entity\Citologia $citologias)
    {
        $this->citologias->removeElement($citologias);
    }

    /**
     * Get citologias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCitologias()
    {
        return $this->citologias;
    }

    /**
     * Add urodinamicos
     *
     * @param \User\ProfesionalBundle\Entity\Urodinamico $urodinamicos
     * @return Client
     */
    public function addUrodinamico(\User\ProfesionalBundle\Entity\Urodinamico $urodinamicos)
    {
        $this->urodinamicos[] = $urodinamicos;

        return $this;
    }

    /**
     * Remove urodinamicos
     *
     * @param \User\ProfesionalBundle\Entity\Urodinamico $urodinamicos
     */
    public function removeUrodinamico(\User\ProfesionalBundle\Entity\Urodinamico $urodinamicos)
    {
        $this->urodinamicos->removeElement($urodinamicos);
    }

    /**
     * Get urodinamicos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUrodinamicos()
    {
        return $this->urodinamicos;
    }
}
