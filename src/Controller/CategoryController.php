<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{

    #[Route('/categories', name: 'app_index_categories')]
    public function indexCategories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('pages/categoryList.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/searchCategories', name: 'app_search_categories')]
    public function search(Request $request, CategoryRepository $categoryRepository): Response
    {
        $searchTerm = $request->query->get('search');

        $categories = [];
        if ($searchTerm !== null) {
            $categories = $categoryRepository->search($searchTerm);
        }

        return $this->render('pages/categoryList.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('category/{id}/products', name: 'app_category_products')]
    public function categoryProducts(int $id, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        return $this->render('pages/categoryProducts.html.twig', [
            'category' => $category,
            'products' => $category->getProducts(),
        ]);
    }

    public function categoryLink(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findTop4Categories();

        return $this->render('_header/_categoryLink.html.twig', [
            'categories' => $categories,
        ]);
    }


    #[Route('/product/{id}', name: 'app_product_details')]
    public function productDetails(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        return $this->render('pages/productDetails.html.twig', [
            'product' => $product,
        ]);
    }
}
