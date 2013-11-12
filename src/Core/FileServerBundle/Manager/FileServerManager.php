<?php

namespace Core\FileServerBundle\Manager;


use Core\FileServerBundle\Entity\File;
use Core\SalesBundle\Document\transaction;
use Core\SalesBundle\Manager\Model\CommissionModel;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

/*
 * Clase que ayuda a administrar un archivo compartido
 *
 */
class FileServerManager
{

    private $file;
    private $em;
    public function __construct(File $file, $entitymanager){

        $this->file = $file;
        $this->em = $entitymanager;
    }

    public function deleteFile(){

        try{
            @unlink($this->file->getAbsolutePath());
        }catch (Exception $e){

        }
        foreach($this->file->getPermissions() as $p){
            $this->file->getPermissions()->removeElement($p);
            $this->em->remove($p);
        }
        $this->em->remove($this->file);
        $this->em->flush();

        return true;
    }

    public function havePermissions($user){

        $val = false;
        foreach($this->file->getPermissions() as $p){
            if($p->getUser() == $user->getId()){
                $val = true;
            }
        }

        if($this->file->getOwner()->getId() == $user->getId()){
            $val = true;
        }

        return $val;
    }


}