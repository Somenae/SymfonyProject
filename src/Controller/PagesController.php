<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PagesController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ProductRepository $productrepo, Request $request ): Response
    {
        if (isset($_GET["submit"])){
            $products = $productrepo->searchByName($request->query->get('product',''));
        } else {
            $products = $productrepo->getRandomProduct();
        }
       
        ;
        return $this->render('pages/index.html.twig', [
            'products' =>  $products ,
            'product' => $request->query->get('product','')
        ]);
    }
    
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

        $cart=NULL;
        $price=0 ;
        if( $security->isGranted('ROLE_USER')){
            $cart = $cartrepo->findLastCartByIdUser($security->getUser()->getId());
            $cartline =  $cart->getCartLine();

            foreach($cartline as $line){
                $pricel= + ($line->getQuantity())*($line->getProduct()->getPrice());
               
                if ($line->getProduct()->getProductTaxes() !== NULL){
                    $pricel = $pricel *(1-(($line->getProduct()->getProductTaxes()->getAmount())/100));

                }
                if ($line->getProduct()->getProductSales() !== NULL){
                    $pricel = $pricel *(1-(($line->getProduct()->getProductSales()->getAmountPercentage())/100));
                    echo($line->getProduct()->getProductSales()->getAmountPercentage()%100);
                    echo " Poucentage de reduc    " ;
                }
                $price = $price +  $pricel;


            }
        }
        return $this->render('pages/cartDisplay.html.twig', [
            'cartline' =>  $cartline,
            'price' => $price
        ]);
    }
}
