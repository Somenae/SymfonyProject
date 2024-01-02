<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Form\OrderStateFormType;
use App\Repository\OrdersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminOrderController extends AbstractController
{
    #[Route(path:'/listorder', name:'app_admin_list_order')]
    public function listOrder(
        Request $request,
        Security $security,
        OrdersRepository $ordersrepo,
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }
        $page = ($request->query->get('page','1'));
        $offset = ($page-1)*10;
        $selectionType = $request->query->get('selectionType','id');
        $SelectionName = $request->query->get('SelectionName', '');
        $ordercount = count($ordersrepo ->findByAttributeCount($SelectionName, $selectionType));
        $ordercount= $ordercount/10;
        $ordercount= ceil($ordercount);
        $orders = $ordersrepo ->findByAttributeOffset($SelectionName, $selectionType, $offset );
    //    dd($ordercount);
        
        
        return $this->render('admin_pages/orderlist.html.twig', [
            'title' => 'Orders list',
            'orders' => $orders,
            'ordercount' => $ordercount,
            'page' =>  $page,
            'selectionType' => $selectionType ,
            'SelectionName' => $SelectionName
        ]);
    }

    #[Route(path:'/showorder/{id}', name:'app_admin_show_order')]
    public function showOrder(
        Security $security,
        Request $request,
        Orders $order,
    ): Response
    {

        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }
        if ($order === NULL) {
            return $this->redirectToRoute('app_admin_list_order');
        }
        $orderlines = $order->getOrderLines();

        return $this->render('admin_pages/ordershow.html.twig', [
            'title' => 'Orders list',
            'order' => $order,
            'orderlines' => $orderlines,
        ]);
    }

    #[Route(path:'/updateorderstate/{id}', name:'app_admin_updatestate_order')]
    public function updateOrderState(
        Security $security,
        Request $request,
        Orders $order,
        EntityManagerInterface $em
    ): Response
    {

        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }
        if ($order === NULL) {
            return $this->redirectToRoute('app_admin_list_order');
        }

        $form = $this->createForm(OrderStateFormType::class, $order);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($order);
            $em->flush();
            return $this->redirectToRoute('app_admin_show_order', ['id' => $order->getId()]);    
            }

        return $this->render('admin_pages/updateorderstate.html.twig', [
            'title' => 'Orders state change',
            'order' => $order,
            'form' => $form,
        ]);
    }


}