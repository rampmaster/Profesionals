<?php

namespace Core\CallBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/core/call/chat")
 * @Template()
 */
class ChatController extends Controller
{

    /**
     * @Route("/upload", name="core_call_chat_upload")
     * @Template()
     */
    public function uploadAction()
    {

        $document = new \Core\UserBundle\Entity\Upload();
        $form = $this->createFormBuilder($document)
            ->add('file')
            ->getForm();

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();

                $em->persist($document);
                $em->flush();

                $this->getRequest()->getSession()->setFlash('upload', $document->getWebPath());

                $response = Array('file' => $document);

            } else {
                $response = Array('form' => $form->createView());
            }
        } else {
            $response = Array('form' => $form->createView());
        }

        return $response;



    }
}