<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;



class ProductController extends AbstractController
{
    #[Route ('/product/{id}', name:'app_product_details')]
    public function productDetails(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $description = $product->getDescription();
        $price = $product->getPrice();
        $image = $product->getImage();

        return $this->render('pages/productDetails.html.twig', [
            'product' => $product,
            'description' => $description,
            'price' => $price,
            'image' => $image,
        ]);
    }


}