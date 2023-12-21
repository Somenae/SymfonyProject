<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserSecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        //Récupérer les erreurs de connexion
        $error = $authenticationUtils->getLastAuthenticationError();
        //Récupérer le nom de l'utilisateur s'il existe
        $last_username = $authenticationUtils->getLastUsername();
        if($request->getMethod()==='POST'){
            dd($this->getUser());
        }

        return $this->render('security/login.html.twig', [
            'title' => 'Login',
            'last_username' => $last_username,
            "logoutpath" => "app_logout",
            'error' => $error,
            'extends' => "{% extends \'base.html.twig\' %}"
        ]);
    }
}