<?php

namespace User\AdminBundle\Controller;

use Core\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\DateTime;
use User\ClientBundle\Entity\Client;
use User\ClientBundle\Form\ClientType;
use User\ProfesionalBundle\Entity\Report;
use User\ProfesionalBundle\Form\ReportType;

class ConsultaController extends Controller
{

 /**
     * @Route("/consulta", name="admin_consulta")
     * @Template()
     */
    public function consultaAction()
    {

        return array();
    }

   


}


