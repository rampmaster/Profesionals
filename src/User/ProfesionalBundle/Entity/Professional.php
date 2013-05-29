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
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;



    /**
     * @ORM\OneToOne(targetEntity="\Core\UserBundle\Entity\User", inversedBy="professional", cascade={ "all" })
     **/
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="\User\ProfesionalBundle\Entity\Styles", inversedBy="professional", cascade={ "all" })
     **/
    private $styles;

    /**
     * @ORM\OneToMany(targetEntity="\User\ProfesionalBundle\Entity\Report", mappedBy="professional")
     */
    private $reports;

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
}
