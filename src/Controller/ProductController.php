<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Form\_ProductFormType;
use App\Form\ProductFormType;
use Doctrine\ORM\EntityManagerInterface;

#[Route('admin/')]
class ProductController extends AbstractController
{
    #[Route('product', name: 'app_product')]
    public function index(ProductRepository $ProductRepository): Response
    {
        $product = $ProductRepository->findAll();
        return $this->render('product/index.html.twig', [
            'product' => $product,  
        ]);
    }


    #[Route('createproduct', name:'app_create_product')]
    public function createArticle(
        Request $request, 
        EntityManagerInterface $em,
        ): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) 
        {
            $em->persist($product);
            $em->flush(); 
            return $this->redirectToRoute('app_product');
        } 
       
        return $this->render('product/create.html.twig',[
            'title' => 'Création d\'un nouveau produit',
            'form' => $form,
        ]);
    }
}
