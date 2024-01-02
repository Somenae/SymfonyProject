<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartLine;
use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CartController extends AbstractController
{

    public function cartCount(CartRepository $cartrepo, Security $security ): Response
    {

        $cart=NULL;
        if( $security->isGranted('ROLE_USER')){
            $cart = $cartrepo->findLastCartByIdUser($security->getUser()->getId());
        }
        return $this->render('_header/_panier.html.twig', [
            'cart' =>  $cart,
        ]);
}
     #[Route('/cartDisplay', name: 'app_cart_display')]
     public function cartDisplay(CartRepository $cartrepo, Security $security ): Response
     { if( !($security->isGranted('ROLE_USER'))){
        return $this->redirectToRoute('app_login');
     }

        $price=0 ;
        $cart = $cartrepo->findLastCartByIdUser($security->getUser()->getId());
        if($cart === NULL || $cart->getCartLine()=== NULL){
            return $this->render('pages/cartDisplay.html.twig', [
                'cartline' =>  NULL ,
            ]);}

           
            $cartline =  $cart->getCartLine();

                foreach ($cartline as $line) {
                    $pricel = ($line->getQuantity()) * ($line->getProduct()->getPrice());
 
                    if ($line->getProduct()->getProductTaxes() !== NULL) {
                        $pricel = $pricel * (1 + (($line->getProduct()->getProductTaxes()->getAmount()) / 100));
                    }
 
                    if ($line->getProduct()->getProductSales() !== NULL) {
                        $pricel = $pricel * (1 - (($line->getProduct()->getProductSales()->getAmountPercentage()) / 100));
                    }
                   echo ($pricel);
                    $price = $price + $pricel;
                }
            
           
            $price = round($price, 2);
            return $this->render('pages/cartDisplay.html.twig', [
                'cartline' =>  $cartline,
                'price' => $price
            ]);
        }
       
       
    

    // NON TESTE
    #[Route('/AddtoCart{id}', name: 'app_cart_add')]
    public function AddtoCart(CartRepository $cartrepo, Security $security, Product $product, EntityManagerInterface $em ) : Response
    { if( !($security->isGranted('ROLE_USER'))){
       return $this->redirectToRoute('app_login');
    }

 
           $cart = $cartrepo->findLastCartByIdUser($security->getUser()->getId());
           if ($cart === NULL)
           {
            $cart = new Cart;
            // $cart->setCreationDate(getDate()["year"]."-".getDate()["mon"]."-".getDate()["mday"]);
            $cart->setCreationDate(new DateTime());
            $cart->setUser($security->getUser());
           }
           $cartline = new CartLine;
           $cartline->setQuantity($_POST['quantity']);
           $cartline->setProduct($product);
           $cartline->setCart($cart);
           $em->persist($cart);
           $em->persist($cartline);
           $em->flush();
           return $this->redirectToRoute('app_cart_display');
    }

    #[Route('/RemoveFromCart{id}', name: 'app_cart_remove')]
    public function RemoveFromCart(CartRepository $cartrepo, Security $security, Cartline $cartline, EntityManagerInterface $em ) : Response
    { if( !($security->isGranted('ROLE_USER'))){
       return $this->redirectToRoute('app_login');
    }
           $em->remove($cartline);
           $em->flush();
           return $this->redirectToRoute('app_cart_display');
    }
   
}