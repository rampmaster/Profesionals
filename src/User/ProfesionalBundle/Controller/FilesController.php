<?php

namespace User\ProfesionalBundle\Controller;


use Core\FileServerBundle\Entity\File;
use Core\FileServerBundle\Form\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class FilesController extends Controller
{

    /**
     * @Route("/files", name="profesional_files")
     * @Template()
     */
    public function indexAction()
    {

       return array();
    }

    /**
     * @Route("/files/add", name="profesional_files_add")
     * @Template()
     */
    public function addAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $file = new File();
        $file->setOwner($user);
        $form = $this->createForm(new FileType(), $file);

        $request = $this->getRequest();

        if($request->getMethod() == 'POST'){
            $form->bind($request);
            if($form->isValid()){

                $file->upload();

                $em->persist($file);
                $em->flush();
            }
        }


        return array('form' => $form->createView());
    }
}