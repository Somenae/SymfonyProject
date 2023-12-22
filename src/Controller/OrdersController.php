<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Repository\CartRepository;
use App\Repository\OrdersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: 'order/')]
class OrdersController extends AbstractController
{
    #[Route('summary', name: 'app_user_summary')]
    public function index(
        Security $security,
        CartRepository $cartrepo,
        Request $request,
    ): Response
    { {
            if (!$security->isGranted('ROLE_USER')) {
                return $this->redirectToRoute('app_login');
            }

            $cart = NULL;
            $price = 0;
            if ($security->isGranted('ROLE_USER')) {
                $user = $security->getUser();
                $cart = $cartrepo->findLastCartByIdUser($user->getId());

                if ($cart === NULL) {
                    return $this->redirectToRoute('app_index'); //change this later if necessary
                }

                $cartline =  $cart->getCartLine();

                foreach ($cartline as $line) {
                    $pricel = + ($line->getQuantity()) * ($line->getProduct()->getPrice());

                    if ($line->getProduct()->getProductTaxes() !== NULL) {
                        $pricel = $pricel * (1 - (($line->getProduct()->getProductTaxes()->getAmount()) / 100));
                    }
                    if ($line->getProduct()->getProductSales() !== NULL) {
                        $pricel = $pricel * (1 - (($line->getProduct()->getProductSales()->getAmountPercentage()) / 100));
                    }
                    $price = $price + $pricel * $line->getQuantity();
                }
            }
            
            $price = round($price, 2);
            return $this->render('orders/index.html.twig', [
                'cartline' =>  $cartline,
                'price' => $price,
                'title' => 'Passer votre commande',
            ]);
        }
    }

    #[Route(path:'order' , name:'app_user_order')]
    public function order(
        Request $request,
        Security $security,
        CartRepository $cartrepo,
    ): Response
    {
        if ($user = $security->getUser() === NULL) {
            return $this->redirectToRoute('app_index');
        }
        if ($request->request->has('buy')) {
            $orders = new Orders();
            $orders->setUsers($user);
            $orders->setCart($cart);
            $orders->setClientName($user->getFirstName().' '.$user->getLastName());

        }

        return $this->render('orders/order.html.twig', [
            'orders' => $orders,
            'title' => 'Commande en cours',
        ]);
    }
}
