<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Repository\SalesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PagesController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ProductRepository $productrepo, Request $request, SalesRepository $salesRepository): Response
    {
        if (isset($_GET["submit"])) {
            $productsNoPromo = $productrepo->searchByName($request->query->get('product', ''));
            $productsPromo = $productrepo->searchByName($request->query->get('product', ''));
        } else {
            $productsNoPromo = $productrepo->getRandomProductNoPromo();
            $productsPromo = $productrepo->getRandomProductPromo();
        }


        return $this->render('pages/index.html.twig', [
            'products' =>  $productsNoPromo,
            'productsPromo' =>  $productsPromo,
            'product' => $request->query->get('product', '')
        ]);
    }



    #[Route('/faq', name: 'app_faq')]
    public function faq(): Response
    {
        return $this->render('pages/faq.html.twig');
    }

    #[Route('/histoire', name: 'app_histoire')]
    public function histoire(): Response
    {
        return $this->render('pages/histoire.html.twig');
    }

    #[Route('/entreprise', name: 'app_entreprise')]
    public function entreprise(): Response
    {
        return $this->render('pages/entreprise.html.twig');
    }

    #[Route('/mentions', name: 'app_mentions')]
    public function mentions(): Response
    {
        return $this->render('pages/mentions.html.twig');
    }

    #[Route('/datas', name: 'app_datas')]
    public function datas(): Response
    {
        return $this->render('pages/datas.html.twig');
    }

    #[Route('/searchProducts', name: 'app_search_products')]
    public function search(Request $request, ProductRepository $productrepository): Response
    {
        $searchTerm = $request->query->get('search');

        $products = [];
        if ($searchTerm !== null) {
            $products = $productrepository->search($searchTerm);
        }

        return $this->render('pages/productsList.html.twig', [
            'products' => $products,
        ]);
    }


    #[Route('/cartDisplay', name: 'app_cart_display')]
    public function cartDisplay(CartRepository $cartrepo, Security $security ): Response
    { if( !($security->isGranted('ROLE_USER'))){
       return $this->redirectToRoute('app_login');
    }

       $price=0 ;
       $cart = $cartrepo->findLastCartByIdUser($security->getUser()->getId());
       if($cart === NULL || $cart->getCartLine()[0]=== NULL){
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
      





}

