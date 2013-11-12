<?php

namespace User\ProfesionalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Radiografia
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Radiografia
{

    /**
     * @ORM\ManyToOne(targetEntity="Professional", inversedBy="radiografias")
     * @ORM\JoinColumn(name="professional_id", referencedColumnName="id")
     */
    private $professional;

    /**
     * @ORM\ManyToOne(targetEntity="\User\ClientBundle\Entity\Client", inversedBy="radiografias")
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
     * @var string
     *
     * @ORM\Column(name="servicio_radiologia", type="text", nullable=true)
     */
    private $servicioRadiologia;

    /**
     * @var boolean
     *
     * @ORM\Column(name="rx_torax_frente", type="boolean")
     */
    private $rxToraxFrente;

    /**
     * @var boolean
     *
     * @ORM\Column(name="rx_torax_perfix", type="boolean")
     */
    private $rxToraxPerfix;

    /**
     * @var boolean
     *
     * @ORM\Column(name="simple_abdomen", type="boolean")
     */
    private $simpleAbdomen;

    /**
     * @var boolean
     *
     * @ORM\Column(name="urografia_descendente", type="boolean")
     */
    private $urografiaDescendente;

    /**
     * @var boolean
     *
     * @ORM\Column(name="cistouretrografia_micionales", type="boolean")
     */
    private $cistouretrografiaMicionales;

    /**
     * @var boolean
     *
     * @ORM\Column(name="cistouretrografias_postmiccional", type="boolean")
     */
    private $cistouretrografiasPostmiccional;

    /**
     * @var boolean
     *
     * @ORM\Column(name="cistouretrografia_retrograda", type="boolean")
     */
    private $cistouretrografiaRetrograda;

    /**
     * @var boolean
     *
     * @ORM\Column(name="prostaica", type="boolean")
     */
    private $prostaica;

    /**
     * @var boolean
     *
     * @ORM\Column(name="rmn_prostatica", type="boolean")
     */
    private $rmn_prostatica;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ecografia_renal", type="boolean")
     */
    private $ecografiaRenal;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ecografia_vesical", type="boolean")
     */
    private $ecografiaVesical;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ecografia_prostatica", type="boolean")
     */
    private $ecografiaProstatica;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ecografia_prostatica_endorectal", type="boolean")
     */
    private $ecografiaProstaticaEndorectal;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ecografia_escrotal", type="boolean")
     */
    private $ecografiaEscrotal;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ecografia_abdominal", type="boolean")
     */
    private $ecografiaAbdominal;

    /**
     * @var text
     *
     * @ORM\Column(name="extra", type="text", nullable=true)
     */
    private $extra;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tac_abdominopelviano", type="boolean")
     */
    private $tacAbdominopelviano;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tac_toracico", type="boolean")
     */
    private $tacToracico;

    /**
     * @var boolean
     *
     * @ORM\Column(name="rmn_abdominal", type="boolean")
     */
    private $rmnAbdominal;

    /**
     * @var string
     *
     * @ORM\Column(name="orientacion_diagnostica", type="text", nullable=true)
     */
    private $orientacionDiagnostica;


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
     * @return Radiografia
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
     * Set servicioRadiologia
     *
     * @param string $servicioRadiologia
     * @return Radiografia
     */
    public function setServicioRadiologia($servicioRadiologia)
    {
        $this->servicioRadiologia = $servicioRadiologia;

        return $this;
    }

    /**
     * Get servicioRadiologia
     *
     * @return string 
     */
    public function getServicioRadiologia()
    {
        return $this->servicioRadiologia;
    }

    /**
     * Set rxToraxFrente
     *
     * @param boolean $rxToraxFrente
     * @return Radiografia
     */
    public function setRxToraxFrente($rxToraxFrente)
    {
        $this->rxToraxFrente = $rxToraxFrente;

        return $this;
    }

    /**
     * Get rxToraxFrente
     *
     * @return boolean 
     */
    public function getRxToraxFrente()
    {
        return $this->rxToraxFrente;
    }

    /**
     * Set rxToraxPerfix
     *
     * @param boolean $rxToraxPerfix
     * @return Radiografia
     */
    public function setRxToraxPerfix($rxToraxPerfix)
    {
        $this->rxToraxPerfix = $rxToraxPerfix;

        return $this;
    }

    /**
     * Get rxToraxPerfix
     *
     * @return boolean 
     */
    public function getRxToraxPerfix()
    {
        return $this->rxToraxPerfix;
    }

    /**
     * Set simpleAbdomen
     *
     * @param boolean $simpleAbdomen
     * @return Radiografia
     */
    public function setSimpleAbdomen($simpleAbdomen)
    {
        $this->simpleAbdomen = $simpleAbdomen;

        return $this;
    }

    /**
     * Get simpleAbdomen
     *
     * @return boolean 
     */
    public function getSimpleAbdomen()
    {
        return $this->simpleAbdomen;
    }

    /**
     * Set urografiaDescendente
     *
     * @param boolean $urografiaDescendente
     * @return Radiografia
     */
    public function setUrografiaDescendente($urografiaDescendente)
    {
        $this->urografiaDescendente = $urografiaDescendente;

        return $this;
    }

    /**
     * Get urografiaDescendente
     *
     * @return boolean 
     */
    public function getUrografiaDescendente()
    {
        return $this->urografiaDescendente;
    }

    /**
     * Set cistouretrografiaMicionales
     *
     * @param boolean $cistouretrografiaMicionales
     * @return Radiografia
     */
    public function setCistouretrografiaMicionales($cistouretrografiaMicionales)
    {
        $this->cistouretrografiaMicionales = $cistouretrografiaMicionales;

        return $this;
    }

    /**
     * Get cistouretrografiaMicionales
     *
     * @return boolean 
     */
    public function getCistouretrografiaMicionales()
    {
        return $this->cistouretrografiaMicionales;
    }

    /**
     * Set cistouretrografiasPostmiccional
     *
     * @param boolean $cistouretrografiasPostmiccional
     * @return Radiografia
     */
    public function setCistouretrografiasPostmiccional($cistouretrografiasPostmiccional)
    {
        $this->cistouretrografiasPostmiccional = $cistouretrografiasPostmiccional;

        return $this;
    }

    /**
     * Get cistouretrografiasPostmiccional
     *
     * @return boolean 
     */
    public function getCistouretrografiasPostmiccional()
    {
        return $this->cistouretrografiasPostmiccional;
    }

    /**
     * Set cistouretrografiaRetrograda
     *
     * @param boolean $cistouretrografiaRetrograda
     * @return Radiografia
     */
    public function setCistouretrografiaRetrograda($cistouretrografiaRetrograda)
    {
        $this->cistouretrografiaRetrograda = $cistouretrografiaRetrograda;

        return $this;
    }

    /**
     * Get cistouretrografiaRetrograda
     *
     * @return boolean 
     */
    public function getCistouretrografiaRetrograda()
    {
        return $this->cistouretrografiaRetrograda;
    }

    /**
     * Set prostaica
     *
     * @param boolean $prostaica
     * @return Radiografia
     */
    public function setProstaica($prostaica)
    {
        $this->prostaica = $prostaica;

        return $this;
    }

    /**
     * Get prostaica
     *
     * @return boolean 
     */
    public function getProstaica()
    {
        return $this->prostaica;
    }

    /**
     * Set ecografiaRenal
     *
     * @param boolean $ecografiaRenal
     * @return Radiografia
     */
    public function setEcografiaRenal($ecografiaRenal)
    {
        $this->ecografiaRenal = $ecografiaRenal;

        return $this;
    }

    /**
     * Get ecografiaRenal
     *
     * @return boolean 
     */
    public function getEcografiaRenal()
    {
        return $this->ecografiaRenal;
    }

    /**
     * Set ecografiaVesical
     *
     * @param boolean $ecografiaVesical
     * @return Radiografia
     */
    public function setEcografiaVesical($ecografiaVesical)
    {
        $this->ecografiaVesical = $ecografiaVesical;

        return $this;
    }

    /**
     * Get ecografiaVesical
     *
     * @return boolean 
     */
    public function getEcografiaVesical()
    {
        return $this->ecografiaVesical;
    }

    /**
     * Set ecografiaProstatica
     *
     * @param boolean $ecografiaProstatica
     * @return Radiografia
     */
    public function setEcografiaProstatica($ecografiaProstatica)
    {
        $this->ecografiaProstatica = $ecografiaProstatica;

        return $this;
    }

    /**
     * Get ecografiaProstatica
     *
     * @return boolean 
     */
    public function getEcografiaProstatica()
    {
        return $this->ecografiaProstatica;
    }

    /**
     * Set ecografiaProstaticaEndorectal
     *
     * @param boolean $ecografiaProstaticaEndorectal
     * @return Radiografia
     */
    public function setEcografiaProstaticaEndorectal($ecografiaProstaticaEndorectal)
    {
        $this->ecografiaProstaticaEndorectal = $ecografiaProstaticaEndorectal;

        return $this;
    }

    /**
     * Get ecografiaProstaticaEndorectal
     *
     * @return boolean 
     */
    public function getEcografiaProstaticaEndorectal()
    {
        return $this->ecografiaProstaticaEndorectal;
    }

    /**
     * Set ecografiaEscrotal
     *
     * @param boolean $ecografiaEscrotal
     * @return Radiografia
     */
    public function setEcografiaEscrotal($ecografiaEscrotal)
    {
        $this->ecografiaEscrotal = $ecografiaEscrotal;

        return $this;
    }

    /**
     * Get ecografiaEscrotal
     *
     * @return boolean 
     */
    public function getEcografiaEscrotal()
    {
        return $this->ecografiaEscrotal;
    }


    /**
     * Set tacAbdominopelviano
     *
     * @param boolean $tacAbdominopelviano
     * @return Radiografia
     */
    public function setTacAbdominopelviano($tacAbdominopelviano)
    {
        $this->tacAbdominopelviano = $tacAbdominopelviano;

        return $this;
    }

    /**
     * Get tacAbdominopelviano
     *
     * @return boolean 
     */
    public function getTacAbdominopelviano()
    {
        return $this->tacAbdominopelviano;
    }

    /**
     * Set tacToracico
     *
     * @param boolean $tacToracico
     * @return Radiografia
     */
    public function setTacToracico($tacToracico)
    {
        $this->tacToracico = $tacToracico;

        return $this;
    }

    /**
     * Get tacToracico
     *
     * @return boolean 
     */
    public function getTacToracico()
    {
        return $this->tacToracico;
    }

    /**
     * Set rmnAbdominal
     *
     * @param boolean $rmnAbdominal
     * @return Radiografia
     */
    public function setRmnAbdominal($rmnAbdominal)
    {
        $this->rmnAbdominal = $rmnAbdominal;

        return $this;
    }

    /**
     * Get rmnAbdominal
     *
     * @return boolean 
     */
    public function getRmnAbdominal()
    {
        return $this->rmnAbdominal;
    }

    /**
     * Set orientacionDiagnostica
     *
     * @param string $orientacionDiagnostica
     * @return Radiografia
     */
    public function setOrientacionDiagnostica($orientacionDiagnostica)
    {
        $this->orientacionDiagnostica = $orientacionDiagnostica;

        return $this;
    }

    /**
     * Get orientacionDiagnostica
     *
     * @return string 
     */
    public function getOrientacionDiagnostica()
    {
        return $this->orientacionDiagnostica;
    }

    /**
     * Set professional
     *
     * @param \User\ProfesionalBundle\Entity\Professional $professional
     * @return Radiografia
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
     * @return Radiografia
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

    /**
     * Set rmn_prostatica
     *
     * @param boolean $rmnProstatica
     * @return Radiografia
     */
    public function setRmnProstatica($rmnProstatica)
    {
        $this->rmn_prostatica = $rmnProstatica;

        return $this;
    }

    /**
     * Get rmn_prostatica
     *
     * @return boolean 
     */
    public function getRmnProstatica()
    {
        return $this->rmn_prostatica;
    }

    /**
     * Set ecografiaAbdominal
     *
     * @param boolean $ecografiaAbdominal
     * @return Radiografia
     */
    public function setEcografiaAbdominal($ecografiaAbdominal)
    {
        $this->ecografiaAbdominal = $ecografiaAbdominal;

        return $this;
    }

    /**
     * Get ecografiaAbdominal
     *
     * @return boolean 
     */
    public function getEcografiaAbdominal()
    {
        return $this->ecografiaAbdominal;
    }

    /**
     * Set extra
     *
     * @param string $extra
     * @return Radiografia
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * Get extra
     *
     * @return string 
     */
    public function getExtra()
    {
        return $this->extra;
    }
}
