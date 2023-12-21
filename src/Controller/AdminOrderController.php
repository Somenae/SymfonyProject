<?php

namespace App\Controller;

use App\Repository\OrdersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/admin')]
class AdminOrderController extends AbstractController
{
    #[Route(path:'/showorder', name:'app_Show_order')]
    public function showOrder(
        Security $security,
        OrdersRepository $ordersrepo,
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }
        $orders = $ordersrepo ->findAll();
        return $this->render('admin_pages/orderlist.html.twig', [
            'title' => 'Orders list',
            'orders' => $orders,
        ]);
    }


}