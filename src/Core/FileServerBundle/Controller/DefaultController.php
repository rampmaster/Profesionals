<?php

namespace Core\FileServerBundle\Controller;

use Core\FileServerBundle\Entity\File;
use Core\FileServerBundle\Entity\Permissions;
use Core\FileServerBundle\Form\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CoreFileServerBundle:Default:index.html.twig', array('name' => $name));
    }


    /**
     * @Route("/core/downloader/{hash}", name="core_fileserver_downloader", defaults={ "hash" = "none" })
     * @Template()
     *
     * devuelvo el formulario para subir archivos
     */
    public function downloadAction($hash)
    {
        $me = $this->get('security.context')->getToken()->getUser();
        //busco el archivo
        if(empty($me)){
            throw new \Exception('You must be logged in');
        }


        $file = $this->getDoctrine()->getManager()->getRepository('CoreFileServerBundle:File')->findOneByHash($hash);

        if(empty($file)){
           throw new \Exception('No file found');
        }

        $val = false;

        //compruebo si tengo acceso al archivo

        $qb = $this->getDoctrine()->getManager()
                    ->createQuery("SELECT p FROM CoreFileServerBundle:Permissions p WHERE p.user = :user AND p.permission >= :permissions AND p.file = :file")
                    ->setParameter('user', $me->getId())
                    ->setParameter('file', $file->getId())
                    ->setParameter('permissions', 4);


        $permissions = $qb->getResult();

        //Compruebo los permisos
        if(count($permissions) > 0){
            $val = true;
        }

        //el archivo es publico
        if($file->getPublic()){
            $val = true;
        }

        if($file->getOwner()->getId() == $me->getId()){//el archivo es mio
            $val = true;
        }

        if(!$val){
            throw new \Exception('You dont`t have access');
        }

        $options = array(
            'absolute_path' => true,
            'inline' => false,
        );

        $response = $this->get('igorw_file_serve.response_factory')
            ->create($file->getAbsolutePath(), 'text/plain', $options);

        return $response;

    }


    /**
     * @Route("/core/uploader/{idUser}/{redirect}", name="core_fileserver_uploader", defaults={ "redirect" = "none", "idUser" = "0" })
     * @Template()
     *
     * devuelvo el formulario para subir archivos
     */
    public function uploaderAction($idUser,$redirect)
    {

        $me = $this->get('security.context')->getToken()->getUser();
        $file = new File();

        $file->setCreatedAt(new \DateTime());

        $file->setOwner($me);

        if($idUser > 0){
            //añadimos un usuario como permiso
            $permission = new Permissions();
            $permission->setFile($file);
            $permission->setUser($idUser);

            $file->addPermission($permission);
        }


        $form = $this->createForm(new FileType(), $file);

        $request = $this->getRequest();

        if($request->getMethod() == 'POST'){


            $form->bind($request);
            if($form->isValid()){

                foreach($file->getPermissions() as $p){
                    $p->setCreatedAt(new \DateTime());
                    $p->setFile($file);

                    $file->addPermission($p);

                    $this->getDoctrine()->getManager()->persist($p);
                }

                if ($handle = $file->getFile()) {


                    $file->setPath($handle->getBasename());
                    $file->upload($handle);


                }

                $this->getDoctrine()->getManager()->persist($file);
                $this->getDoctrine()->getManager()->flush();

                //return new Response('Ok');

                if(is_null($redirect)){
                    $redirect = $request->get('redirect');
                }
                //

                if($redirect == 'ajax' OR $redirect == 'none'){
                    return new Response('Ok');
                }else{

                    return $this->redirect($this->generateUrl($redirect));
                }

            }else{
                print_r($form->getErrors());
            }
        }


        return array('form' => $form->createView(), 'redirect' => $redirect);


    }

    /**
     * @Route("/core/listfiles/{idUser}", name="core_fileserver_listfiles", defaults={ "idUser" = "0" })
     * @Template()
     *
     * devuelvo el formulario para subir archivos
     */
    public function listfilesAction($idUser)
    {

            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository('CoreUserBundle:User')->find($idUser);

        $qb = $em
            ->createQuery("SELECT p, f FROM CoreFileServerBundle:Permissions p LEFT JOIN p.file f WHERE p.user = :user AND p.permission >= :permissions")
            ->setParameter('user', $user->getId())
            ->setParameter('permissions', 4);

        $result = $qb->getResult();

        $files = array();
        foreach($result as $r){
            array_push($files, $r->getFile());
        }

        

        return array('files' => $files);
    }
}
