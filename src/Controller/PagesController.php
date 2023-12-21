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
    
    
}
