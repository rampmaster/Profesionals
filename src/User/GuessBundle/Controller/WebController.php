<?php

namespace User\GuessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

use User\GuessBundle\Model\Contact;

class WebController extends Controller
{

    /**
     * @Route("/home", name="home_web")
     * @Template()
     */
    public function indexAction()
    {
        $sent=false;

        $contact = new Contact();
        $form = $this->createFormBuilder($contact)
            ->add('name', 'text')
            ->add('email', 'email')
            ->add('message', 'textarea',array('required'=>'false'))
            ->getForm();

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $name = $contact->name;
                $email = $contact->email;
                $message = $contact->message;

                $message = \Swift_Message::newInstance()
                    ->setSubject('SOLICITUD DE INFORMACIÓN')
                    ->setFrom('varavan.pro@gmail.com')
                    ->setTo('varavan.pro@gmail.com')
                    ->setBody($this->renderView(
                        'UserGuessBundle:Email:contact.txt.twig',
                            array('email'=>$email,'message'=>$message,'name' => $name)));
                
                $this->get('mailer')->send($message);
                $sent=true;
            }
        } 

        return array('form'=>$form->createView(),'sent'=>$sent);
    }
}
