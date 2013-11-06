<?php

namespace User\ProfesionalBundle\Controller;


use Core\FileServerBundle\Entity\File;
use Core\FileServerBundle\Form\FilePropertiesType;
use Core\FileServerBundle\Form\FileType;
use Core\FileServerBundle\Form\PermissionsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



class FilesController extends Controller
{

    /**
     * @Route("/files/{id}", name="profesional_files", defaults={ "id" = "0" })
     * @Template()
     */
    public function indexAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $files = $user->getFiles();

        if($id > 0){
            $filesnew  = array();

            foreach($files as $f){
                foreach($f->getPermissions() as $p){
                    if($p->getUser() == $id){
                        array_push($filesnew, $f);
                        break;
                    }
                }
            }

            $files = $filesnew;

        }



       return array('files' => $files);
    }

    /**
     * @Route("/files/add/new", name="profesional_files_add")
     * @Template()
     */
    public function addAction()
    {

        return array();
    }

    /**
     * @Route("/files/edit-permissions/{hash}", name="profesional_files_edit_permissions")
     * @Template()
     */
    public function editpermissionsAction($hash)
    {


        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $qb = $this->getDoctrine()->getManager()
            ->createQuery("SELECT f, p FROM CoreFileServerBundle:File f  LEFT JOIN f.permissions p WHERE f.hash = :hash")
            ->setParameter('hash', $hash);


        $file = $qb->getSingleResult();

        $originalPermissions = array();
        foreach ($file->getPermissions() as $tag) {
            $originalPermissions[] = $tag;
        }

        if($user->getId() != $file->getOwner()->getId()){
            throw new \Exception('You are not allowed to edit this file');
        }

        $form =  $this->createForm(new FilePropertiesType(), $file);

        $request = $this->getRequest();

        if($request->getMethod() == 'POST'){

            $form->bind($request);
            if($form->isValid()){

                foreach ($file->getPermissions() as $tag) {
                    foreach ($originalPermissions as $key => $toDel) {
                        if ($toDel->getId() === $tag->getId()) {
                            unset($originalPermissions[$key]);
                        }
                    }
                }

                // remove the relationship between the tag and the Task
                foreach ($originalPermissions as $p) {
                    // remove the Task from the Tag
                    $file->getPermissions()->removeElement($p);

                    // if it were a ManyToOne relationship, remove the relationship like this
                    // $tag->setTask(null);

                    $em->persist($p);

                    // if you wanted to delete the Tag entirely, you can also do that
                    // $em->remove($tag);
                }

                foreach($file->getPermissions() as $p){
                    $created_at = $p->getCreatedAt();
                    if(is_null($created_at)){
                        $p->setCreatedAt(new \DateTime());
                        $p->setFile($file);
                        $file->addPermission($p);
                        $em->persist($p);
                    }


                }


                $em->persist($file);

                $em->flush();

                return $this->redirect($this->generateUrl('profesional_files'));
            }
        }

        return array('form' => $form->createView(), 'file' => $file);
    }
}