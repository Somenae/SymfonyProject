<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/admin')]
class AdminPagesController extends AbstractController
{
    #[Route(path:'/', name:'app_admin_index')]
    public function index(
        Security $security,
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }
        return $this->redirectToRoute('app_admin_dashboard');
    }

    #[Route('/dashboard', name: 'app_admin_dashboard')]
    public function dashboard(
        Security $security,
    ): Response
    {
        {
            if (!$security->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_admin_login');
            }
        }

        return $this->render('admin_pages/dashboard.html.twig', [
            'title' => 'Dahsboard',
        ]);
    }
}
