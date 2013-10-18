<?php

namespace User\ProfesionalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Analitica
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Analitica
{


    /**
     * @ORM\ManyToOne(targetEntity="Professional", inversedBy="analiticas")
     * @ORM\JoinColumn(name="professional_id", referencedColumnName="id")
     */
    private $professional;

    /**
     * @ORM\ManyToOne(targetEntity="\User\ClientBundle\Entity\Client", inversedBy="analiticas")
     * @ORM\JoinColumn(name="professional_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var boolean
     *
     * @ORM\Column(name="extra", type="text", nullable=true)
     */
    private $extra;


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hemograma_completo", type="boolean")
     */
    private $hemogramaCompleto;

    /**
     * @var boolean
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;


    /**
     * @var boolean
     *
     * @ORM\Column(name="plaquetas", type="boolean")
     */
    private $plaquetas;

    /**
     * @var boolean
     *
     * @ORM\Column(name="creatinina", type="boolean")
     */
    private $creatinina;

    /**
     * @var boolean
     *
     * @ORM\Column(name="uricemia", type="boolean")
     */
    private $uricemia;

    /**
     * @var boolean
     *
     * @ORM\Column(name="gruposanguineo", type="boolean")
     */
    private $gruposanguineo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="proteinas", type="boolean")
     */
    private $proteinas;


    /**
     * @var boolean
     *
     * @ORM\Column(name="testoterona_libre", type="boolean")
     */
    private $testoterona_libre;

    /**
     * @var boolean
     *
     * @ORM\Column(name="testoterona_total", type="boolean")
     */
    private $testoterona_total;

    /**
     * @var boolean
     *
     * @ORM\Column(name="colesterol_hdl", type="boolean")
     */
    private $colesterolHdl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="colesterol_ldl", type="boolean")
     */
    private $colesterolLdl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="glicemia", type="boolean")
     */
    private $glicemia;

    /**
     * @var boolean
     *
     * @ORM\Column(name="proteina_c_reactiva", type="boolean")
     */
    private $proteinaCReactiva;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hepatitis_b_c", type="boolean")
     */
    private $hepatitisBC;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tg", type="boolean")
     */
    private $tg;

    /**
     * @var boolean
     *
     * @ORM\Column(name="got_gpt", type="boolean")
     */
    private $gotGpt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="bt", type="boolean")
     */
    private $bt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ggt", type="boolean")
     */
    private $ggt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="na", type="boolean")
     */
    private $na;

    /**
     * @var boolean
     *
     * @ORM\Column(name="k", type="boolean")
     */
    private $k;

    /**
     * @var boolean
     *
     * @ORM\Column(name="p", type="boolean")
     */
    private $p;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ca", type="boolean")
     */
    private $ca;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pth", type="boolean")
     */
    private $pth;

    /**
     * @var boolean
     *
     * @ORM\Column(name="alcalinas", type="boolean")
     */
    private $alcalinas;

    /**
     * @var boolean
     *
     * @ORM\Column(name="vsg", type="boolean")
     */
    private $vsg;

    /**
     * @var boolean
     *
     * @ORM\Column(name="psa_libre", type="boolean")
     */
    private $psa_libre;

    /**
     * @var boolean
     *
     * @ORM\Column(name="psa_total", type="boolean")
     */
    private $psa_total;

    /**
     * @var boolean
     *
     * @ORM\Column(name="fsh", type="boolean")
     */
    private $fsh;

    /**
     * @var boolean
     *
     * @ORM\Column(name="lh", type="boolean")
     */
    private $lh;

    /**
     * @var boolean
     *
     * @ORM\Column(name="prl", type="boolean")
     */
    private $prl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hcg", type="boolean")
     */
    private $hcg;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ldh", type="boolean")
     */
    private $ldh;

    /**
     * @var boolean
     *
     * @ORM\Column(name="fetoproteina", type="boolean")
     */
    private $fetoproteina;

    /**
     * @var boolean
     *
     * @ORM\Column(name="cea", type="boolean")
     */
    private $cea;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tsh", type="boolean")
     */
    private $tsh;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sedimento_orina", type="boolean")
     */
    private $sedimento_orina;

    /**
     * @var boolean
     *
     * @ORM\Column(name="cultivo_orina", type="boolean")
     */
    private $cultivo_orina;

    /**
     * @var boolean
     *
     * @ORM\Column(name="antibiograma_orina", type="boolean")
     */
    private $antibiograma_orina;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pca3_orina", type="boolean")
     */
    private $pca3_orina;


    /**
     * @var boolean
     *
     * @ORM\Column(name="seminograma_semen", type="boolean")
     */
    private $seminograma_semen;

    /**
     * @var boolean
     *
     * @ORM\Column(name="seminocultivo_semen", type="boolean")
     */
    private $seminocultivo_semen;

    /**
     * @var boolean
     *
     * @ORM\Column(name="orina_24h_calcio", type="boolean")
     */
    private $orina_24h_calcio;

    /**
     * @var boolean
     *
     * @ORM\Column(name="orina_24h_fosforo", type="boolean")
     */
    private $orina_24h_fosforo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="orina_24h_uratos", type="boolean")
     */
    private $orina_24h_uratos;

    /**
     * @var boolean
     *
     * @ORM\Column(name="orina_24h_citratos", type="boolean")
     */
    private $orina_24h_citratos;


    /**
     * @var boolean
     *
     * @ORM\Column(name="orina_24h_magnesio", type="boolean")
     */
    private $orina_24h_magnesio;


    /**
     * @var boolean
     *
     * @ORM\Column(name="orina_24h_proteinas_totales", type="boolean")
     */
    private $orina_24h_proteinas_totales;

    /**
     * @var boolean
     *
     * @ORM\Column(name="orina_24h_volumen", type="boolean")
     */
    private $orina_24h_volumen;


    /**
     * @var boolean
     *
     * @ORM\Column(name="orina_24h_oxalatos", type="boolean")
     */
    private $orina_24h_oxalatos;




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
     * Set hemogramaCompleto
     *
     * @param boolean $hemogramaCompleto
     * @return Analitica
     */
    public function setHemogramaCompleto($hemogramaCompleto)
    {
        $this->hemogramaCompleto = $hemogramaCompleto;

        return $this;
    }

    /**
     * Get hemogramaCompleto
     *
     * @return boolean 
     */
    public function getHemogramaCompleto()
    {
        return $this->hemogramaCompleto;
    }

    /**
     * Set plaquetas
     *
     * @param boolean $plaquetas
     * @return Analitica
     */
    public function setPlaquetas($plaquetas)
    {
        $this->plaquetas = $plaquetas;

        return $this;
    }

    /**
     * Get plaquetas
     *
     * @return boolean 
     */
    public function getPlaquetas()
    {
        return $this->plaquetas;
    }

    /**
     * Set uricemia
     *
     * @param boolean $uricemia
     * @return Analitica
     */
    public function setUricemia($uricemia)
    {
        $this->uricemia = $uricemia;

        return $this;
    }

    /**
     * Get uricemia
     *
     * @return boolean 
     */
    public function getUricemia()
    {
        return $this->uricemia;
    }

    /**
     * Set gruposanguineo
     *
     * @param boolean $gruposanguineo
     * @return Analitica
     */
    public function setGruposanguineo($gruposanguineo)
    {
        $this->gruposanguineo = $gruposanguineo;

        return $this;
    }

    /**
     * Get gruposanguineo
     *
     * @return boolean 
     */
    public function getGruposanguineo()
    {
        return $this->gruposanguineo;
    }

    /**
     * Set proteinas
     *
     * @param boolean $proteinas
     * @return Analitica
     */
    public function setProteinas($proteinas)
    {
        $this->proteinas = $proteinas;

        return $this;
    }

    /**
     * Get proteinas
     *
     * @return boolean 
     */
    public function getProteinas()
    {
        return $this->proteinas;
    }

    /**
     * Set colesterolHdl
     *
     * @param boolean $colesterolHdl
     * @return Analitica
     */
    public function setColesterolHdl($colesterolHdl)
    {
        $this->colesterolHdl = $colesterolHdl;

        return $this;
    }

    /**
     * Get colesterolHdl
     *
     * @return boolean 
     */
    public function getColesterolHdl()
    {
        return $this->colesterolHdl;
    }

    /**
     * Set colesterolLdl
     *
     * @param boolean $colesterolLdl
     * @return Analitica
     */
    public function setColesterolLdl($colesterolLdl)
    {
        $this->colesterolLdl = $colesterolLdl;

        return $this;
    }

    /**
     * Get colesterolLdl
     *
     * @return boolean 
     */
    public function getColesterolLdl()
    {
        return $this->colesterolLdl;
    }

    /**
     * Set glicemia
     *
     * @param boolean $glicemia
     * @return Analitica
     */
    public function setGlicemia($glicemia)
    {
        $this->glicemia = $glicemia;

        return $this;
    }

    /**
     * Get glicemia
     *
     * @return boolean 
     */
    public function getGlicemia()
    {
        return $this->glicemia;
    }

    /**
     * Set proteinaCReactiva
     *
     * @param boolean $proteinaCReactiva
     * @return Analitica
     */
    public function setProteinaCReactiva($proteinaCReactiva)
    {
        $this->proteinaCReactiva = $proteinaCReactiva;

        return $this;
    }

    /**
     * Get proteinaCReactiva
     *
     * @return boolean 
     */
    public function getProteinaCReactiva()
    {
        return $this->proteinaCReactiva;
    }

    /**
     * Set hepatitisBC
     *
     * @param boolean $hepatitisBC
     * @return Analitica
     */
    public function setHepatitisBC($hepatitisBC)
    {
        $this->hepatitisBC = $hepatitisBC;

        return $this;
    }

    /**
     * Get hepatitisBC
     *
     * @return boolean 
     */
    public function getHepatitisBC()
    {
        return $this->hepatitisBC;
    }

    /**
     * Set tg
     *
     * @param boolean $tg
     * @return Analitica
     */
    public function setTg($tg)
    {
        $this->tg = $tg;

        return $this;
    }

    /**
     * Get tg
     *
     * @return boolean 
     */
    public function getTg()
    {
        return $this->tg;
    }

    /**
     * Set gotGpt
     *
     * @param boolean $gotGpt
     * @return Analitica
     */
    public function setGotGpt($gotGpt)
    {
        $this->gotGpt = $gotGpt;

        return $this;
    }

    /**
     * Get gotGpt
     *
     * @return boolean 
     */
    public function getGotGpt()
    {
        return $this->gotGpt;
    }

    /**
     * Set bt
     *
     * @param boolean $bt
     * @return Analitica
     */
    public function setBt($bt)
    {
        $this->bt = $bt;

        return $this;
    }

    /**
     * Get bt
     *
     * @return boolean 
     */
    public function getBt()
    {
        return $this->bt;
    }

    /**
     * Set ggt
     *
     * @param boolean $ggt
     * @return Analitica
     */
    public function setGgt($ggt)
    {
        $this->ggt = $ggt;

        return $this;
    }

    /**
     * Get ggt
     *
     * @return boolean 
     */
    public function getGgt()
    {
        return $this->ggt;
    }

    /**
     * Set na
     *
     * @param boolean $na
     * @return Analitica
     */
    public function setNa($na)
    {
        $this->na = $na;

        return $this;
    }

    /**
     * Get na
     *
     * @return boolean 
     */
    public function getNa()
    {
        return $this->na;
    }

    /**
     * Set k
     *
     * @param boolean $k
     * @return Analitica
     */
    public function setK($k)
    {
        $this->k = $k;

        return $this;
    }

    /**
     * Get k
     *
     * @return boolean 
     */
    public function getK()
    {
        return $this->k;
    }

    /**
     * Set p
     *
     * @param boolean $p
     * @return Analitica
     */
    public function setP($p)
    {
        $this->p = $p;

        return $this;
    }

    /**
     * Get p
     *
     * @return boolean 
     */
    public function getP()
    {
        return $this->p;
    }

    /**
     * Set ca
     *
     * @param boolean $ca
     * @return Analitica
     */
    public function setCa($ca)
    {
        $this->ca = $ca;

        return $this;
    }

    /**
     * Get ca
     *
     * @return boolean 
     */
    public function getCa()
    {
        return $this->ca;
    }

    /**
     * Set pth
     *
     * @param boolean $pth
     * @return Analitica
     */
    public function setPth($pth)
    {
        $this->pth = $pth;

        return $this;
    }

    /**
     * Get pth
     *
     * @return boolean 
     */
    public function getPth()
    {
        return $this->pth;
    }

    /**
     * Set alcalinas
     *
     * @param boolean $alcalinas
     * @return Analitica
     */
    public function setAlcalinas($alcalinas)
    {
        $this->alcalinas = $alcalinas;

        return $this;
    }

    /**
     * Get alcalinas
     *
     * @return boolean 
     */
    public function getAlcalinas()
    {
        return $this->alcalinas;
    }

    /**
     * Set vsg
     *
     * @param boolean $vsg
     * @return Analitica
     */
    public function setVsg($vsg)
    {
        $this->vsg = $vsg;

        return $this;
    }

    /**
     * Get vsg
     *
     * @return boolean 
     */
    public function getVsg()
    {
        return $this->vsg;
    }


    /**
     * Set fsh
     *
     * @param boolean $fsh
     * @return Analitica
     */
    public function setFsh($fsh)
    {
        $this->fsh = $fsh;

        return $this;
    }

    /**
     * Get fsh
     *
     * @return boolean 
     */
    public function getFsh()
    {
        return $this->fsh;
    }

    /**
     * Set lh
     *
     * @param boolean $lh
     * @return Analitica
     */
    public function setLh($lh)
    {
        $this->lh = $lh;

        return $this;
    }

    /**
     * Get lh
     *
     * @return boolean 
     */
    public function getLh()
    {
        return $this->lh;
    }

    /**
     * Set prl
     *
     * @param boolean $prl
     * @return Analitica
     */
    public function setPrl($prl)
    {
        $this->prl = $prl;

        return $this;
    }

    /**
     * Get prl
     *
     * @return boolean 
     */
    public function getPrl()
    {
        return $this->prl;
    }

    /**
     * Set hcg
     *
     * @param boolean $hcg
     * @return Analitica
     */
    public function setHcg($hcg)
    {
        $this->hcg = $hcg;

        return $this;
    }

    /**
     * Get hcg
     *
     * @return boolean 
     */
    public function getHcg()
    {
        return $this->hcg;
    }

    /**
     * Set ldh
     *
     * @param boolean $ldh
     * @return Analitica
     */
    public function setLdh($ldh)
    {
        $this->ldh = $ldh;

        return $this;
    }

    /**
     * Get ldh
     *
     * @return boolean 
     */
    public function getLdh()
    {
        return $this->ldh;
    }

    /**
     * Set fetoproteina
     *
     * @param boolean $fetoproteina
     * @return Analitica
     */
    public function setFetoproteina($fetoproteina)
    {
        $this->fetoproteina = $fetoproteina;

        return $this;
    }

    /**
     * Get fetoproteina
     *
     * @return boolean 
     */
    public function getFetoproteina()
    {
        return $this->fetoproteina;
    }

    /**
     * Set cea
     *
     * @param boolean $cea
     * @return Analitica
     */
    public function setCea($cea)
    {
        $this->cea = $cea;

        return $this;
    }

    /**
     * Get cea
     *
     * @return boolean 
     */
    public function getCea()
    {
        return $this->cea;
    }

    /**
     * Set tsh
     *
     * @param boolean $tsh
     * @return Analitica
     */
    public function setTsh($tsh)
    {
        $this->tsh = $tsh;

        return $this;
    }

    /**
     * Get tsh
     *
     * @return boolean 
     */
    public function getTsh()
    {
        return $this->tsh;
    }

    /**
     * Set sedimento_orina
     *
     * @param boolean $sedimentoOrina
     * @return Analitica
     */
    public function setSedimentoOrina($sedimentoOrina)
    {
        $this->sedimento_orina = $sedimentoOrina;

        return $this;
    }

    /**
     * Get sedimento_orina
     *
     * @return boolean 
     */
    public function getSedimentoOrina()
    {
        return $this->sedimento_orina;
    }

    /**
     * Set cultivo_orina
     *
     * @param boolean $cultivoOrina
     * @return Analitica
     */
    public function setCultivoOrina($cultivoOrina)
    {
        $this->cultivo_orina = $cultivoOrina;

        return $this;
    }

    /**
     * Get cultivo_orina
     *
     * @return boolean 
     */
    public function getCultivoOrina()
    {
        return $this->cultivo_orina;
    }

    /**
     * Set antibiograma_orina
     *
     * @param boolean $antibiogramaOrina
     * @return Analitica
     */
    public function setAntibiogramaOrina($antibiogramaOrina)
    {
        $this->antibiograma_orina = $antibiogramaOrina;

        return $this;
    }

    /**
     * Get antibiograma_orina
     *
     * @return boolean 
     */
    public function getAntibiogramaOrina()
    {
        return $this->antibiograma_orina;
    }

    /**
     * Set pca3_orina
     *
     * @param boolean $pca3Orina
     * @return Analitica
     */
    public function setPca3Orina($pca3Orina)
    {
        $this->pca3_orina = $pca3Orina;

        return $this;
    }

    /**
     * Get pca3_orina
     *
     * @return boolean 
     */
    public function getPca3Orina()
    {
        return $this->pca3_orina;
    }

    /**
     * Set seminograma_semen
     *
     * @param boolean $seminogramaSemen
     * @return Analitica
     */
    public function setSeminogramaSemen($seminogramaSemen)
    {
        $this->seminograma_semen = $seminogramaSemen;

        return $this;
    }

    /**
     * Get seminograma_semen
     *
     * @return boolean 
     */
    public function getSeminogramaSemen()
    {
        return $this->seminograma_semen;
    }

    /**
     * Set seminocultivo_semen
     *
     * @param boolean $seminocultivoSemen
     * @return Analitica
     */
    public function setSeminocultivoSemen($seminocultivoSemen)
    {
        $this->seminocultivo_semen = $seminocultivoSemen;

        return $this;
    }

    /**
     * Get seminocultivo_semen
     *
     * @return boolean 
     */
    public function getSeminocultivoSemen()
    {
        return $this->seminocultivo_semen;
    }

    /**
     * Set orina_24h_calcio
     *
     * @param boolean $orina24hCalcio
     * @return Analitica
     */
    public function setOrina24hCalcio($orina24hCalcio)
    {
        $this->orina_24h_calcio = $orina24hCalcio;

        return $this;
    }

    /**
     * Get orina_24h_calcio
     *
     * @return boolean 
     */
    public function getOrina24hCalcio()
    {
        return $this->orina_24h_calcio;
    }

    /**
     * Set orina_24h_fosforo
     *
     * @param boolean $orina24hFosforo
     * @return Analitica
     */
    public function setOrina24hFosforo($orina24hFosforo)
    {
        $this->orina_24h_fosforo = $orina24hFosforo;

        return $this;
    }

    /**
     * Get orina_24h_fosforo
     *
     * @return boolean 
     */
    public function getOrina24hFosforo()
    {
        return $this->orina_24h_fosforo;
    }

    /**
     * Set orina_24h_uratos
     *
     * @param boolean $orina24hUratos
     * @return Analitica
     */
    public function setOrina24hUratos($orina24hUratos)
    {
        $this->orina_24h_uratos = $orina24hUratos;

        return $this;
    }

    /**
     * Get orina_24h_uratos
     *
     * @return boolean 
     */
    public function getOrina24hUratos()
    {
        return $this->orina_24h_uratos;
    }

    /**
     * Set orina_24h_citratos
     *
     * @param boolean $orina24hCitratos
     * @return Analitica
     */
    public function setOrina24hCitratos($orina24hCitratos)
    {
        $this->orina_24h_citratos = $orina24hCitratos;

        return $this;
    }

    /**
     * Get orina_24h_citratos
     *
     * @return boolean 
     */
    public function getOrina24hCitratos()
    {
        return $this->orina_24h_citratos;
    }

    /**
     * Set orina_24h_magnesio
     *
     * @param boolean $orina24hMagnesio
     * @return Analitica
     */
    public function setOrina24hMagnesio($orina24hMagnesio)
    {
        $this->orina_24h_magnesio = $orina24hMagnesio;

        return $this;
    }

    /**
     * Get orina_24h_magnesio
     *
     * @return boolean 
     */
    public function getOrina24hMagnesio()
    {
        return $this->orina_24h_magnesio;
    }

    /**
     * Set orina_24h_proteinas_totales
     *
     * @param boolean $orina24hProteinasTotales
     * @return Analitica
     */
    public function setOrina24hProteinasTotales($orina24hProteinasTotales)
    {
        $this->orina_24h_proteinas_totales = $orina24hProteinasTotales;

        return $this;
    }

    /**
     * Get orina_24h_proteinas_totales
     *
     * @return boolean 
     */
    public function getOrina24hProteinasTotales()
    {
        return $this->orina_24h_proteinas_totales;
    }

    /**
     * Set orina_24h_volumen
     *
     * @param boolean $orina24hVolumen
     * @return Analitica
     */
    public function setOrina24hVolumen($orina24hVolumen)
    {
        $this->orina_24h_volumen = $orina24hVolumen;

        return $this;
    }

    /**
     * Get orina_24h_volumen
     *
     * @return boolean 
     */
    public function getOrina24hVolumen()
    {
        return $this->orina_24h_volumen;
    }

    /**
     * Set orina_24h_oxalatos
     *
     * @param boolean $orina24hOxalatos
     * @return Analitica
     */
    public function setOrina24hOxalatos($orina24hOxalatos)
    {
        $this->orina_24h_oxalatos = $orina24hOxalatos;

        return $this;
    }

    /**
     * Get orina_24h_oxalatos
     *
     * @return boolean 
     */
    public function getOrina24hOxalatos()
    {
        return $this->orina_24h_oxalatos;
    }

    /**
     * Set professional
     *
     * @param \User\ProfesionalBundle\Entity\Professional $professional
     * @return Analitica
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
     * @return Analitica
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
     * Set creatinina
     *
     * @param boolean $creatinina
     * @return Analitica
     */
    public function setCreatinina($creatinina)
    {
        $this->creatinina = $creatinina;

        return $this;
    }

    /**
     * Get creatinina
     *
     * @return boolean 
     */
    public function getCreatinina()
    {
        return $this->creatinina;
    }

    /**
     * Set testoterona_libre
     *
     * @param boolean $testoteronaLibre
     * @return Analitica
     */
    public function setTestoteronaLibre($testoteronaLibre)
    {
        $this->testoterona_libre = $testoteronaLibre;

        return $this;
    }

    /**
     * Get testoterona_libre
     *
     * @return boolean 
     */
    public function getTestoteronaLibre()
    {
        return $this->testoterona_libre;
    }

    /**
     * Set testoterona_total
     *
     * @param boolean $testoteronaTotal
     * @return Analitica
     */
    public function setTestoteronaTotal($testoteronaTotal)
    {
        $this->testoterona_total = $testoteronaTotal;

        return $this;
    }

    /**
     * Get testoterona_total
     *
     * @return boolean 
     */
    public function getTestoteronaTotal()
    {
        return $this->testoterona_total;
    }

    /**
     * Set psa_libre
     *
     * @param boolean $psaLibre
     * @return Analitica
     */
    public function setPsaLibre($psaLibre)
    {
        $this->psa_libre = $psaLibre;

        return $this;
    }

    /**
     * Get psa_libre
     *
     * @return boolean 
     */
    public function getPsaLibre()
    {
        return $this->psa_libre;
    }

    /**
     * Set psa_total
     *
     * @param boolean $psaTotal
     * @return Analitica
     */
    public function setPsaTotal($psaTotal)
    {
        $this->psa_total = $psaTotal;

        return $this;
    }

    /**
     * Get psa_total
     *
     * @return boolean 
     */
    public function getPsaTotal()
    {
        return $this->psa_total;
    }

    /**
     * Set extra
     *
     * @param string $extra
     * @return Analitica
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

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Analitica
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}
