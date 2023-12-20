<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\categoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


Class CategoryController extends AbstractController
{
public function indexCategories(CategoryRepository $categoryRepository): Response
{
  $categories = $categoryRepository->findAll();

  return $this->render('category/index.html.twig', [
      'categories' => $categories,
  ]);
}

/* #[Route('/indexCategory', name: 'app_admin_index_category')]
public function index(CategoryRepository $categoryRepository)
{
    $categories = $categoryRepository->findAllOrderedByProductCount();

    return $this->render('base.html.twig', [
    'categories' => $categories,
    ]);
}*/
}