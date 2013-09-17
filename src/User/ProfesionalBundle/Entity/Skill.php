<?php

namespace User\ProfesionalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Skill
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="User\ProfesionalBundle\Entity\SkillRepository")
 */
class Skill
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
     * @ORM\ManyToMany(targetEntity="Professional", mappedBy="skills")
     */
    private $professionals;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;


    /**
     * @var integer
     *
     * @ORM\Column(name="ranking_professional", type="integer")
     */
    private $ranking_professional = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="ranking_client", type="integer")
     */
    private $ranking_client = 0;




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
     * Set name
     *
     * @param string $name
     * @return Skill
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Skill
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Skill
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
     * Constructor
     */
    public function __construct()
    {
        $this->professionals = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add professionals
     *
     * @param \User\ProfesionalBundle\Entity\Professional $professionals
     * @return Skill
     */
    public function addProfessional(\User\ProfesionalBundle\Entity\Professional $professionals)
    {
        $this->addRankingProfessional();
        $this->professionals[] = $professionals;

        return $this;
    }

    /**
     * Remove professionals
     *
     * @param \User\ProfesionalBundle\Entity\Professional $professionals
     */
    public function removeProfessional(\User\ProfesionalBundle\Entity\Professional $professionals)
    {
        $this->deleteRankingProfessional();
        $this->professionals->removeElement($professionals);
    }

    /**
     * Get professionals
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProfessionals()
    {
        return $this->professionals;
    }

    /**
     * Set ranking_professional
     *
     * @param integer $rankingProfessional
     * @return Skill
     */
    public function setRankingProfessional($rankingProfessional)
    {
        $this->ranking_professional = $rankingProfessional;

        return $this;
    }

    /**
     * Get ranking_professional
     *
     * @return integer 
     */
    public function getRankingProfessional()
    {
        return $this->ranking_professional;
    }

    /**
     * Set ranking_client
     *
     * @param integer $rankingClient
     * @return Skill
     */
    public function setRankingClient($rankingClient)
    {
        $this->ranking_client = $rankingClient;

        return $this;
    }

    /**
     * Get ranking_client
     *
     * @return integer 
     */
    public function getRankingClient()
    {
        return $this->ranking_client;
    }

    public function addRankingClient(){
           $this->ranking_client++;
    }

    public function addRankingProfessional(){
        $this->ranking_professional++;
    }

    public function deleteRankingProfessional(){
        $this->ranking_professional--;

    }

    public function deleteRankingClient(){
        $this->ranking_professional--;
    }
}
