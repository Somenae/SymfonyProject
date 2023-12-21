<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Users;
use App\Form\UserAddressFormType;
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

    #[Route(path:'address', name:'app_user_update_address')]
    public function updateAdress(
        Security $security,
        Request $request,
        EntityManagerInterface $em,
    ) : Response
    {
        $user = $security->getUser();
        if ($user === NULL) {
            return $this->redirectToRoute('app_login');
        }
        $address = $user->getAddress();
        /* If user doesn't have any address atm it creates a new address entity and linked it to them, it probably can be written with less code, don't hesitate to 
        check on it later */
        if ($address !== NULL) {
            $form = $this->createForm(UserAddressFormType::class, $address);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $user->setAddress($address);
                $em->persist($user);
                $em->flush();
            }
        } else {
            $address = new Address();
            $address->addUser($user);
            $form = $this->createForm(UserAddressFormType::class, $address);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $address->setAddress($form->get('address')->getData());
                $em->persist($address);
                $em->flush();
            }
        }

        return $this->render('user/new.html.twig', [
            'title'=> 'Modifier Adresse',
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