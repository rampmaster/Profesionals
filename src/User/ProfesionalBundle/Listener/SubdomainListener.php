<?php

namespace User\ProfesionalBundle\Listener;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use Doctrine\ORM\EntityManager;

class SubdomainListener
{
  private $em = null;

  public function __construct(EntityManager $entityManager){
    $this->em = $entityManager;
  }

   public function subdomainParse(Event $event)
   {
       $request = $event->getRequest();
       $session = $request->getSession();

       // todo: parsing subdomain to detect country
       $default_hosts = array(
          "professionals",
       		"varavan",
       		"www",
          "professionals"
       	);
       $parts = explode(".",$request->getHost());
       if(in_array($parts[0],$default_hosts)){
       		$username = false;
       }else{
       		$username = $parts[0];
          $user = $this->em->getRepository("CoreUserBundle:User")->findOneByUsername($username);
          $session->set('style',false);
          if($user){
            $session->set('style',$user->getProfessional()->getStyles()->getWebPath());
            $session->set('theme_headline',$user->getProfessional()->getHeadline());
            $session->set('theme_skills',$user->getProfessional()->getSkills());
            $session->set('theme_name',$user->getName()." ".$user->getSurname());
            $session->set('theme_id',$user->getId());
          }
       }
       $session->set('subdomain', $username);
       $session->set('host', $parts[0]);
   }
}