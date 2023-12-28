<?php

namespace App\Controller;

use App\Repository\AdminsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UsersRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\SalesRepository;


#[Route('/admin')]
class AdminPagesController extends AbstractController
{
    #[Route(path: '/', name: 'app_admin_index')]
    public function index(
        Security $security,
    ): Response {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }
        return $this->redirectToRoute('app_admin_dashboard');
    }

    #[Route('/dashboard', name: 'app_admin_dashboard')]
    public function dashboard(
        Security $security,
        UsersRepository $usersrepository,
        AdminsRepository $adminrepository,
        CategoryRepository $categoryrepository,
        ProductRepository $productrepository,
        Salesrepository $salesrepository,
    ): Response { {
            if (!$security->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_admin_login');
            }
        }

        $countUsers = $usersrepository->countUsers();
        $countAdmins = $adminrepository->countAdmins();
        $countCategories = $categoryrepository->countCategories();
        $countProducts = $productrepository->countProducts();
        $countDiscounts = $salesrepository->countDiscounts();


        return $this->render('admin_pages/dashboard.html.twig', [
            'title' => 'Dahsboard',
            'countUsers' => $countUsers,
            'countAdmins' => $countAdmins,
            'countCategories' => $countCategories,
            'countProducts' => $countProducts,
            'countDiscounts' => $countDiscounts,
        ]);
    }
}
