<?php

namespace Core\SalesBundle\Manager;


use Core\SalesBundle\Document\transaction;
use Core\SalesBundle\Manager\Model\CommissionModel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

/*
 * Este servicio se encarga de manejar  lo que tiene que ver con los diferentes procesos de ventas
 *
 */
class SalesManager
{

    private $user;
    private $fileserver;
    public function __construct($user, $fileserver){

        $this->user = $user;
        $this->fileserver = $fileserver;
    }


    public function getMyFiles(){

    }


    public function uploadFile(){


    }


}