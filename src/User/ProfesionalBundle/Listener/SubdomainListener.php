<?php

namespace User\ProfesionalBundle\Listener;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;

class SubdomainListener
{
   public function subdomainParse(Event $event)
   {
       $request = $event->getRequest();
       $session = $request->getSession();

       // todo: parsing subdomain to detect country
       $DEFAULT_HOST = "professionals";
       $parts = explode(".",$request->getHost());
       if($parts[0] == $DEFAULT_HOST){
       		$username = false;
       }else{
       		$username = $parts[0];
       }
       $session->set('subdomain', $username);
   }
}