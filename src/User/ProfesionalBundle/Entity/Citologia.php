<?php

namespace User\ProfesionalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Citologia
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Citologia
{

    /**
     * @ORM\ManyToOne(targetEntity="Professional", inversedBy="citologias")
     * @ORM\JoinColumn(name="professional_id", referencedColumnName="id")
     */
    private $professional;

    /**
     * @ORM\ManyToOne(targetEntity="\User\ClientBundle\Entity\Client", inversedBy="citologias")
     * @ORM\JoinColumn(name="professional_id", referencedColumnName="id")
     */
    private $client;


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
     * @return Citologia
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
     * Set professional
     *
     * @param \User\ProfesionalBundle\Entity\Professional $professional
     * @return Citologia
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
     * @return Citologia
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