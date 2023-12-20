<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path:'user/')]
class UserController extends AbstractController
{
    #[Route('new', name: 'app_new_user')]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
    ): Response
    {
        $user = new Users();
        $form = $this->createForm(UserFormType::class, $user);
        $plainTextPassword = '';
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plainTextPassword = $_POST['user_form']['password']['first'];
            $hashedPassword = $hasher->hashPassword(
                $user,
                $plainTextPassword
            );
            $user->setPassword($hashedPassword);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_login');
        }
        return $this->render('user/new.html.twig', [
            'title' => 'Enregistrez-vous !',
            'form' => $form,
        ]);
    }

    #[Route(path:'account', name:'app_user_account')]
    public function account(
        Security $security,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
    ): Response
    {
        $user = $security->getUser();
        if ($user === NULL) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(UserFormType::class, $user);
        $plainTextPassword = '';
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plainTextPassword = $_POST['user_form']['password']['first'];
            $hashedPassword = $hasher->hashPassword(
                $user,
                $plainTextPassword
            );
            $user->setPassword($hashedPassword);
            $em->persist($user);
            $em->flush();
        }

        return $this->render('user/new.html.twig', [
            'title'=> 'Modifiez les informations de votre compte',
            'form'=> $form,
        ]);
    }

    #[Route(path:'history', name:'app_user_history')]
    public function history(
        Security $security,
        ?Users $user,
    ): Response
    {
        $user = $security->getUser();
        if ($user === NULL) {
            return $this->redirectToRoute('app_login');
        }

        $orders = $user->getOrders();

        return $this->render('user/history.html.twig', [
            'title' => 'Historique des commandes',
            'orders' => $orders,
        ]);
    }
}