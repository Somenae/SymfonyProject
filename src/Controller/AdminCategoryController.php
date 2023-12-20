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

#[Route('/admin')]
class AdminCategoryController extends AbstractController
{
    #[Route('/createCategory', name: 'app_admin_create_category')]
    public function createCategory(EntityManagerInterface $em, Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category,[
            'label_name' => "Créer nouvelle categorie"
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('app_admin_show_category', ['id' => $category->getId()]);    
            }
           
                
        return $this->render('category/create.html.twig', [
            'form' => $form,
                    
                ]);
            
    }

    #[Route('/showCategory/{id}', name: 'app_admin_show_category')]
    public function showCategory(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/listCategories', name: 'app_admin_list_categories')]
    public function listCategories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/list.html.twig', [
            'categories' => $categories,
        ]);
    }


    #[Route('/updateCategory/{id}', name: 'app_admin_update_category')]
    public function updateCategory(EntityManagerInterface $em, Request $request, ?Category $category): Response
    {
        if ($category === null) {
            throw $this->createNotFoundException('Categorie non trouvée');
        }
        $form = $this->createForm(categoryFormType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('app_admin_show_category', ['id' => $category->getId()]);    
            }
           
                
        return $this->render('category/update.html.twig', [
            'form' => $form,
                    
                ]);
            
    }

    #[Route('/deleteCategory/{id}', name: 'app_admin_delete_category')]
    public function deleteCategory(EntityManagerInterface $em, Request $request, ?Category $category): Response
    {
        if ($category === null) {
            throw $this->createNotFoundException('Categorie non trouvée');
        }
        $token = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete'.$category->getId(), $token)) {
        $em->remove($category);
        $em->flush();
        }
        return $this->redirectToRoute('app_admin_create_category');
    }

}

