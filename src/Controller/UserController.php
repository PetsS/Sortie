<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user', name: 'app_user')]
class UserController extends AbstractController
{
    #[Route('/profil', name: '_profil')]
    public function profil(): Response
    {
        return $this->render('user/profil.html.twig');
    }

    #[Route('/creer', name: '_creer')]
    #[IsGranted('ROLE_ADMIN')]
    public function creer(): Response
    {
        return $this->render('user/creerUser.html.twig');
    }
}
