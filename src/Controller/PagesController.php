<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PagesController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ProductRepository $productrepo ): Response
    {
        $products = $productrepo->getRandomProduct();
        return $this->render('pages/index.html.twig', [
            'products' =>  $products ,
        ]);
    }
}
