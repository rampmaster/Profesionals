<?php

namespace User\AdminBundle\Controller;

use Core\UserBundle\Form\UserAddType as UserType;
use Core\UserBundle\Form\UserEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\DateTime;
use User\ClientBundle\Entity\Client;
use User\ClientBundle\Form\ClientType;
use User\ProfesionalBundle\Entity\Report;
use User\ProfesionalBundle\Form\ReportType;
use User\ProfesionalBundle\Form\StylesType;
use User\ProfesionalBundle\Entity\Professional;
use User\ProfesionalBundle\Entity\Styles;


class ProfessionalController extends Controller
{
    /**
     * @Route("/professionals/", name="admin_professionals")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $professionals = $em->createQuery('SELECT u FROM CoreUserBundle:User u WHERE u.roles LIKE :roles')
                            ->setParameter('roles','%ROLE_PROFESIONAL%')->getResult();


        return array('professionals' => $professionals);
    }

    /**
     * @Route("/professionals/add", name="admin_professionals_add")
     * @Template()
     */
    public function addAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usermanager = $this->get('fos_user.user_manager');
        $user = $usermanager->createUser();
        $user->addRole('ROLE_PROFESIONAL');
        $user->setEnabled(true);


        $form = $this->createForm(new UserType(), $user);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {

                $em->persist($user);
                $user->upload();
                $user->setUpdatedAt(new \DateTime());
                $user->setCreatedAt(new \DateTime());

                $professional = new Professional();
                $professional->setUpdatedAt(new \DateTime());
                $professional->setCreatedAt(new \DateTime());
                $professional->setUser($user);
                $user->setProfessional($professional);

                $em->persist($professional);
                $em->persist($user);
                $usermanager->updateUser($user);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Profesional añadido con éxito');
                return $this->redirect($this->generateUrl('admin_professionals'));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/professionals/edit/{id}", name="admin_professionals_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $usermanager = $this->get('fos_user.user_manager');
        $user = $em->getRepository('CoreUserBundle:User')->find($id);
        $professional = $user->getProfessional();
        if(!$professional){
            $professional = new Professional();
            $professional->setUser($user);
            $professional->setCreatedAt(new \DateTime());
        }

        $form = $this->createForm(new UserEditType(), $user);
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $user->upload();
                $user->setUpdatedAt(new \DateTime());
                $professional->setUpdatedAt(new \DateTime());
                $user->setProfessional($professional);

                $usermanager->updateUser($user);
                $em->persist($user);
                $em->persist($professional);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Datos del profesional guardado on éxito');

                return $this->redirect($this->generateUrl('admin_professionals_show',array('id'=>$id)));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/professionals/styles/{id}", name="admin_professionals_styles")
     * @Template()
     */
    public function stylesAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $usermanager = $this->get('fos_user.user_manager');
        $user = $em->createQuery("SELECT u FROM CoreUserBundle:User u LEFT JOIN u.professional p  WHERE u.id = :uid")
                    ->setParameter('uid',$id)->getSingleResult();

        $professional = $user->getProfessional();
        $username = $user->getUsername();
        if(!$professional){
            throw new \Exception("Error cargando profesional.");
        }

        $styles = $professional->getStyles();
        if(!$styles){
            $styles = new Styles();
            $styles->setProfessional($professional);
            $professional->setStyles($styles);
            $styles->setCreatedAt(new \DateTime());
        }


        $form = $this->createForm(new StylesType(), $styles);
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $styles->setUpdatedAt(new \DateTime());
                $styles->upload($username);
                $em->persist($styles);
                $em->persist($professional);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Estilo del profesional guardado on éxito');
                return $this->redirect($this->generateUrl('admin_professionals_styles',array('id'=>$id)));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/professionals/show/{id}", name="admin_professionals_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $cliente = $em->getRepository('CoreUserBundle:User')->find($id);


        return array('user' => $cliente);
    }

   


}