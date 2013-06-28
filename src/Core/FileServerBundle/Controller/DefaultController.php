<?php

namespace Core\FileServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CoreFileServerBundle:Default:index.html.twig', array('name' => $name));
    }
}
