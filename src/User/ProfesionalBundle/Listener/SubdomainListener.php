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
       }
       $session->set('subdomain', $username);
   }
}