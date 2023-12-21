<?php

namespace App\Controller;

use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: 'order/')]
class OrdersController extends AbstractController
{
    #[Route('order', name: 'app_user_orders')]
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
                $cart = $cartrepo->findLastCartByIdUser($security->getUser()->getId());
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
                        echo ($line->getProduct()->getProductSales()->getAmountPercentage() % 100);
                        echo " Poucentage de reduc    ";
                    }
                    $price = $price +  $pricel;
                }
            }
            return $this->render('pages/cartDisplay.html.twig', [
                'cartline' =>  $cartline,
                'price' => $price,
                'title' => 'Passer votre commande',
            ]);
        }
    }
}
