<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\OrderLine;
use App\Entity\Orders;
use App\Entity\OrderState;
use App\Form\OrdersFormType;
use App\Repository\CartRepository;
use App\Repository\OrdersRepository;
use App\Repository\OrderStateRepository;
use App\Repository\ShippingRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
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
                    $pricel = ($line->getQuantity()) * ($line->getProduct()->getPrice());

                    if ($line->getProduct()->getProductTaxes() !== NULL) {
                        $pricel = $pricel * (1 + (($line->getProduct()->getProductTaxes()->getAmount()) / 100));
                    }

                    if ($line->getProduct()->getProductSales() !== NULL) {
                        $pricel = $pricel * (1 - (($line->getProduct()->getProductSales()->getAmountPercentage()) / 100));
                    }
                    
                    $price = $price + $pricel;
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
        OrderStateRepository $stateRepository,
        EntityManagerInterface $em,
    ): Response
    {
        if ($security->getUser() === NULL) {
            return $this->redirectToRoute('app_index');
        }
        
        $orders = new Orders();
        $user = $security->getUser();
        if ($user->getAddress() === NULL) {
            return $this->redirectToRoute('app_user_account');
        }

        $cart = $cartrepo->findLastCartByIdUser($user->getId());
        
        if ($cart->getCartLine()[0] === NULL) {
            return $this->redirectToRoute('app_index');
        }

        $form = $this->createForm(OrdersFormType::class, $orders, [
            'address' => [$user->getAddress()],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $orderState = $stateRepository->findByLabel('Attente de payement');

            $orders->setUsers($user);
            $orders->setCart($cart);
            $orders->setClientName($user->getFirstName().' '.$user->getLastName());
            $orders->setOrderDate(date_create_immutable());

            $cartline =  $cart->getCartLine();

            $price = 0;

            foreach ($cartline as $line) {
                $pricel = ($line->getQuantity()) * ($line->getProduct()->getPrice());

                if ($line->getProduct()->getProductTaxes() !== NULL) {
                    $pricel = $pricel * (1 + (($line->getProduct()->getProductTaxes()->getAmount()) / 100));
                }

                if ($line->getProduct()->getProductSales() !== NULL) {
                    $pricel = $pricel * (1 - (($line->getProduct()->getProductSales()->getAmountPercentage()) / 100));
                }
                
                $price = $price + $pricel;
            }

            $orders->setTotalPrice($price);
            $orders->setOrderState($orderState[0]);
            $em->persist($orders);

            foreach ($cartline as $line) {
                $orderLine = new OrderLine();
                $productPrice = $line->getProduct()->getPrice();
                $productSales = 0;
                if ($line->getProduct()->getProductSales() !== NULL) {
                    $productSales = $line->getProduct()->getProductSales()->getAmountPercentage();
                }
                $productTaxes = 0;
                if ($line->getProduct()->getProductTaxes() !== NULL) {
                    $productTaxes = $line->getProduct()->getProductTaxes()->getAmount();
                }
                $orderLine->setProductsName($line->getProduct()->getName());
                $orderLine->setProductUnitPrice($productPrice);
                $orderLine->setTotalPrice($productPrice * (1 - ($productSales / 100)) * (1 + ($productTaxes / 100)));
                $orderLine->setQuantity($line->getQuantity());
                $orderLine->setSales($productSales);
                $orderLine->setTaxe($productTaxes);
                $orderLine->setOrders($orders);
                $em->persist($orderLine);
            }

            $cart = new Cart;
            $cart->setCreationDate(new DateTime());
            $cart->setUser($security->getUser());
            $em->persist($cart);

            $em->flush();
            return $this->redirectToRoute('app_index');
        }

        return $this->render('orders/order.html.twig', [
            'orders' => $orders,
            'form' => $form,
            'title' => 'Commande en cours',
        ]);
    }
}
