<?php

namespace Core\FileServerBundle\Entity;


use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Doctrine\ORM\Mapping as ORM;

/**
 * File
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Core\FileServerBundle\Entity\FileRepository")
 */
class File
{

    public function removeAllPermissions()
    {
        foreach ($this->permissions as $p) {
            $this->removePermission($p);
        }
    }

    //RELATIONS

    /**
     * @ORM\ManyToOne(targetEntity="\Core\UserBundle\Entity\User", inversedBy="files")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="Permissions", mappedBy="file")
     */
    private $permissions;


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
     * @ORM\Column(name="public", type="boolean")
     *
     * publico para todos mis clientes
     */
    private $public;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string")
     */
    private $hash;
    /**
     * @var string
     *
     * @ORM\Column(name="mime", type="string")
     */
    private $mime;

    /**
     * @var integer
     *
     * @ORM\Column(name="size", type="integer")
     *
     * size in Kb
     */
    private $size;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    //File
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir() . '/' . $this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir() . '/' . $this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../security/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }

    public function upload($file)
    {
        if (null === $this->file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $old = $this->path;
        $this->hash = uniqid();
        $this->size = $file->getSize();
        $this->mime = $file->getMimeType();
        $file->move($this->getUploadRootDir(), $file->getBasename());
        //rename($file->getRealPath(), __DIR__.$this->getUploadRootDir()."/".$file->getBasename());
        //$this->file->move($this->getUploadRootDir(), $this->path);


        $this->file = null;
    }


    private $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile($file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getMimeHuman()
    {

        $chunks = explode("/", $this->mime);
        switch ($chunks[0]) {

            default:
                return "unknown";
                break;

            case "video":
                return 'video';
                break;

            case 'image':
                return 'image';
                break;

            case 'audio':
                return 'audio';
                break;

            case 'application':
                if ($chunks[1] == 'pdf') {
                    return 'pdf';
                } else {
                    return "unknown";
                }

        }
    }

    ///
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
     * @return File
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
     * @return File
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
        $this->permissions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set path
     *
     * @param string $path
     * @return File
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set owner
     *
     * @param \Core\UserBundle\Entity\User $owner
     * @return File
     */
    public function setOwner(\Core\UserBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Core\UserBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Add permissions
     *
     * @param \Core\FileServerBundle\Entity\Permissions $permissions
     * @return File
     */
    public function addPermission(\Core\FileServerBundle\Entity\Permissions $permissions)
    {
        $this->permissions[] = $permissions;

        return $this;
    }


    /**
     * Remove permissions
     *
     * @param \Core\FileServerBundle\Entity\Permissions $permissions
     */
    public function removePermission(\Core\FileServerBundle\Entity\Permissions $permissions)
    {
        $this->permissions->removeElement($permissions);
    }

    /**
     * Get permissions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Set public
     *
     * @param boolean $public
     * @return File
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return File
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return File
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    public function getSizeHum()
    {
        $kb = $this->size / 1000;

        return $kb . " Kb";
    }

    /**
     * Set mime
     *
     * @param string $mime
     * @return File
     */
    public function setMime($mime)
    {
        $this->mime = $mime;

        return $this;
    }

    /**
     * Get mime
     *
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }


}
