<?php

namespace User\GuessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class DefaultController extends Controller
{

    /**
     * @Route("/", name="home_guess")
     */
    public function indexAction()
    {

        return $this->render('UserGuessBundle:Default:index.html.twig');
    }
}
