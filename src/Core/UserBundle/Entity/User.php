<?php
// src/Acme/UserBundle/Entity/User.php

namespace Core\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="User")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name",type="string", nullable=true)
     */
    private $name = null;


    /**
     * @ORM\Column(name="is_male",type="boolean", nullable=true)
     */
    private $is_male = null;

    /**
     * @ORM\Column(name="surname",type="string", nullable=true)
     */
    private $surname = null;

    /**
     * @ORM\Column(name="mobile",type="string", nullable=true)
     */
    private $mobile = null;


    //RELATIONS

    /**
     * @ORM\OneToOne(targetEntity="\User\ClientBundle\Entity\Client", inversedBy="user", cascade={ "all" })
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;


    public function __construct()
    {
        parent::__construct();
        // your own logic
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
     * Set name
     *
     * @param string $name
     * @return User
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
     * Set is_male
     *
     * @param boolean $isMale
     * @return User
     */
    public function setIsMale($isMale)
    {
        $this->is_male = $isMale;
    
        return $this;
    }

    /**
     * Get is_male
     *
     * @return boolean 
     */
    public function getIsMale()
    {
        return $this->is_male;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    
        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     * @return User
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    
        return $this;
    }

    /**
     * Get mobile
     *
     * @return string 
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set client
     *
     * @param \User\ClientBundle\Entity\Client $client
     * @return User
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