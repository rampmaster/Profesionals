<?php

namespace User\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class SalesController extends Controller
{

    /**
     * @Route("/sales/", name="admin_sales")
     * @Template()
     */
    public function indexAction()
    {

        return array();

    }


    /**
     * @Route("/sales/user/{id}", name="admin_sales_users")
     */
    public function usersalesAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $user = $em->getRepository('CoreUserBundle:User')->find($id);

        $products = $em->getRepository('CoreSalesBundle:ProductSkeleton')->findByRemove(false);
        $pdf = $this->get('knp_snappy.pdf');
        $mail = $this->get('mailer');
        $twig = $this->get('twig');


        $salesmanager = new \Core\SalesBundle\Manager\SalesManager($user->getId(), $dm, $em,$mail, $pdf, $twig);
        $request = $this->getRequest();
        if($request->getMethod() == 'POST'){
            $product = $request->get('product_symbol');
            $expiration = $request->get('expiration');

            $salesmanager->promoProduct($product, $expiration);


        }
        $bag = $salesmanager->getMyBag();

        $unusedBag = $dm->getRepository('CoreSalesBundle:productbag')->findBy(array('userId' => $user->getId()));
        $usedBag = $bag->getMyUsedBag();
        $plans = $bag->getPlans();
        $plansProduct = $bag->getPlansProducts();
        $credits = $dm->getRepository('CoreSalesBundle:credit')->findBy(array('user' => "" . $user->getId() . "", 'available' => true));

        $paymentsPending = $dm->getRepository('CoreSalesBundle:payment')->findBy(array('user' => $user->getId(), 'payed' => false, 'active' => true));
        $paymentSuccess = $dm->getRepository('CoreSalesBundle:payment')->findBy(array('user' => $user->getId(), 'payed' => true, 'active' => true));

        return $this->render('UserAdminBundle:Sales:user.html.twig',array(
            'bag' => $unusedBag,
            'usedbag' => $usedBag,
            'credits' => count($credits),
            'entity' => $user,
            'paymentsPending' => $paymentsPending,
            'paymentSuccess' => $paymentSuccess,
            'plans' => $plans,
            'plansProduct' => $plansProduct,
            'user' => $user,
            'products' => $products
        ));

        return array();
    }
}