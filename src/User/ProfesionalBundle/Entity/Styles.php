<?php

namespace User\ProfesionalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Styles
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Styles
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
     * @var string
     *
     * @ORM\Column(name="color_bg_main", type="string", length=255,nullable=true)
     */
    private $colorBgMain;

    /**
     * @var string
     *
     * @ORM\Column(name="color_bg_secd", type="string", length=255,nullable=true)
     */
    private $colorBgSecd;

    /**
     * @var string
     *
     * @ORM\Column(name="color_button", type="string", length=255,nullable=true)
     */
    private $colorButton;

    /**
     * @var string
     *
     * @ORM\Column(name="color_text_button", type="string", length=255,nullable=true)
     */
    private $colorTextButton;

    /**
     * @var string
     *
     * @ORM\Column(name="raw_css", type="text",nullable=true)
     */
    private $rawCss;

 //FILE UPLOAD

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path = "avatar.png";

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir($extra="")
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir().$extra;
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'images/styles/'.$this->professional->getUser()->getUsername();
    }


    public function upload($username="default")
    {
        $this->writeCss($this->getUploadRootDir());
        if (null === $this->file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->path =  'logo.' . $this->file->guessExtension();
        $this->file->move($this->getUploadRootDir("/".$username), $this->path);
        

        $this->file = null;
    }

    public function writeCss($filename){

        $css  = ".background-main { ";
        $css .= "background-color: #".$this->colorBgMain;
        $css .= " }\n";
        $css .= ".container > .login-wrapper { ";
        $css .= "background-color: #".$this->colorBgSecd;
        $css .= " }\n";
        $css .= "#login .input-group input[type=submit]  { ";
        $css .= "color: #".$this->colorTextButton.";\n";
        $css .= "background-color: #".$this->colorButton;
        $css .= " }\n";
        $css .= $this->rawCss;
        if(!is_dir($filename)){
            mkdir($filename);
        }
        file_put_contents($filename."/styles.css", $css);
    }




    /**
     * @ORM\OneToOne(targetEntity="\User\ProfesionalBundle\Entity\Professional", inversedBy="styles", cascade={ "all" })
     * @ORM\JoinColumn(name="professional_id", referencedColumnName="id")
     */
    private $professional;


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
     * @return Styles
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
     * @return Styles
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
     * Set path
     *
     * @param string $path
     * @return Styles
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
     * Set colorBgMain
     *
     * @param string $colorBgMain
     * @return Styles
     */
    public function setColorBgMain($colorBgMain)
    {
        $this->colorBgMain = $colorBgMain;

        return $this;
    }

    /**
     * Get colorBgMain
     *
     * @return string 
     */
    public function getColorBgMain()
    {
        return $this->colorBgMain;
    }

    /**
     * Set colorBgSecd
     *
     * @param string $colorBgSecd
     * @return Styles
     */
    public function setColorBgSecd($colorBgSecd)
    {
        $this->colorBgSecd = $colorBgSecd;

        return $this;
    }

    /**
     * Get colorBgSecd
     *
     * @return string 
     */
    public function getColorBgSecd()
    {
        return $this->colorBgSecd;
    }

    /**
     * Set colorExtra
     *
     * @param string $colorExtra
     * @return Styles
     */
    public function setColorExtra($colorExtra)
    {
        $this->colorExtra = $colorExtra;

        return $this;
    }

    /**
     * Get colorExtra
     *
     * @return string 
     */
    public function getColorExtra()
    {
        return $this->colorExtra;
    }

    /**
     * Set professional
     *
     * @param \User\ProfesionalBundle\Entity\Professional $professional
     * @return Styles
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
     * Set rawCss
     *
     * @param string $rawCss
     * @return Styles
     */
    public function setRawCss($rawCss)
    {
        $this->rawCss = $rawCss;

        return $this;
    }

    /**
     * Get rawCss
     *
     * @return string 
     */
    public function getRawCss()
    {
        return $this->rawCss;
    }

    /**
     * Set colorButton
     *
     * @param string $colorButton
     * @return Styles
     */
    public function setColorButton($colorButton)
    {
        $this->colorButton = $colorButton;

        return $this;
    }

    /**
     * Get colorButton
     *
     * @return string 
     */
    public function getColorButton()
    {
        return $this->colorButton;
    }

    /**
     * Set colorTextButton
     *
     * @param string $colorTextButton
     * @return Styles
     */
    public function setColorTextButton($colorTextButton)
    {
        $this->colorTextButton = $colorTextButton;

        return $this;
    }

    /**
     * Get colorTextButton
     *
     * @return string 
     */
    public function getColorTextButton()
    {
        return $this->colorTextButton;
    }
}
