<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Security;

class DefaultController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('app_index');   
        }
   
        public function indexAdmin(): RedirectResponse
    {
        return $this->redirectToRoute('app_admin_dashboard');   
        }
}