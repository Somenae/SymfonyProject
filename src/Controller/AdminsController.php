<?php

namespace App\Controller;

use App\Entity\Admins;
use App\Form\AdminFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path:'admin/')]
class AdminsController extends AbstractController
{
    #[Route('new', name: 'app_admin_new_admin')]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        Security $security,
    ): Response
    {
        if (!$security->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->redirectToRoute('app_admin_dashboard');
        }
        $admin = new Admins();
        $form = $this->createForm(AdminFormType::class, $admin);
        $plainTextPassword = '';
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plainTextPassword = $_POST['admin_form']['password']['first'];
            $hashedPassword = $hasher->hashPassword(
                $admin,
                $plainTextPassword
            );
            $admin->setPassword($hashedPassword);
            $em->persist($admin);
            $em->flush();
            return $this->redirectToRoute('app_admin_new_user');
        }
        return $this->render('admin/new.html.twig', [
            'title' => 'Enregistrez-vous !',
            'form' => $form,
        ]);
    }
}
