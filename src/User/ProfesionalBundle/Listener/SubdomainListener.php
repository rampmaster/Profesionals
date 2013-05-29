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
       		"www"
       	);
       $parts = explode(".",$request->getHost());
       if(array_search($parts[0],$default_hosts)){
       		$username = $parts[0];
       }else{
       		$username = false;
       		
       }
       $session->set('subdomain', $username);
   }
}