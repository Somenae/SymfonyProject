<?php

namespace App\Controller;

use App\Entity\Sales;
use App\Form\SalesFormType;
use App\Repository\ProductRepository;
use App\Repository\SalesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalesController extends AbstractController
{
    #[Route('/admin/listsales', name: 'app_admin_listsales')]
    public function listeSales(SalesRepository $salesrepo): Response
    {
        $sales=$salesrepo->findAll();
        return $this->render('sales/list.html.twig', [
            'sales' => $sales,
        ]);
    }

    #[Route('/admin/createsale', name: 'app_admin_createsale')]
    public function createSale(Request $request, EntityManagerInterface $em): Response
    {

        $sales= New Sales;
        $form = $this->createForm(SalesFormType ::class, $sales);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ) {
            $em->persist($sales);
            $em->flush();
            return $this->redirectToRoute('app_admin_listsales');
        }
        return $this->render('sales/new.html.twig', [
            'form' =>  $form,
        ]);
    }

        #[Route('/admin/updatesale/{id}', name: 'app_admin_updatesale')]
    public function updateSale(Request $request, EntityManagerInterface $em, ?Sales $sales): Response
    {
        if ($sales === NULL){
            return $this->redirectToRoute('app_admin_listsales');
        }
        $form = $this->createForm(SalesFormType ::class, $sales);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($sales);
            $em->flush();
            return $this->redirectToRoute('app_admin_listsales');
        }
        return $this->render('sales/new.html.twig', [
            'form' =>  $form,
        ]);
    }

    #[Route('/admin/deletesale/{id}', name: 'app_admin_deletesale')]
    public function deleteSale(Request $request, EntityManagerInterface $em, ?Sales $sales): Response
    {
        if ($sales === NULL){
            return $this->redirectToRoute('app_admin_listsales');
        }
        $products = $sales->getProducts();

        foreach($products as $product){
            $sales->removeProduct($product);
        }


        if(isset($_POST["submit"])) {
            $em->remove($sales);
            $em->flush();
            return $this->redirectToRoute('app_admin_listsales');
        }
        return $this->render('sales/delete.html.twig', [
        ]);
    }

    #[Route('/admin/productsale/{id}', name: 'app_admin_productesale')]
    public function ProductSale(Request $request, EntityManagerInterface $em, ?Sales $sale, ProductRepository $productrepo): Response
    {
        if ($sale === NULL){
            return $this->redirectToRoute('app_admin_listsales');
        }
        $products = $productrepo->findAll();

        if(isset($_POST["submit"]))
        {
            foreach ($_POST as $P){
                if ($P !== "")
                {

                   $produit= $productrepo->find($P);
                   $produit->setProductSales($sale);
                   $em->persist($produit);
                   $em->flush();
                };

            }
             return $this->redirectToRoute('app_admin_listsales');

        }


         return $this->render('sales/productSales.html.twig', [
             "products" => $products,
             "labelname" => "Ajouter la promotion a ce produit"
         ]);
    }
    
    #[Route('/admin/Eraseproductsale', name: 'app_admin_erase_productesale')]
    public function EraseProductSale(Request $request, EntityManagerInterface $em, ProductRepository $productrepo): Response
    {
        
        $products = $productrepo->searchByIDSale();

        if(isset($_POST["submit"]))
        {
            foreach ($_POST as $P){
                if ($P !== "")
                {

                   $produit= $productrepo->find($P);
                   $sales=$produit->getProductSales();
                   $sales->removeProduct($produit);
                   $em->persist($sales);
                   $em->flush();
                };

            }
             return $this->redirectToRoute('app_admin_listsales');

        }


         return $this->render('sales/productSales.html.twig', [
             "products" => $products,
             "labelname" => "Supprimer la promotion a ce produit"
         ]);
    }
}
