<?php

namespace User\CollaboratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Collaborator
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Collaborator
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
