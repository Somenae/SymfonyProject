<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\AdminUserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/')]
class AdminUserController extends AbstractController
{
    #[Route('user/new', name: 'app_admin_new_user')]
    public function index(): Response
    {
        $user = new Users();
        $form = $this->createForm(AdminUserFormType::class, $user);
        return $this->render('admin_user/index.html.twig', [
            'form' => $form,
        ]);
    }
}
