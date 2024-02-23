<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sortie', name: 'app_sortie')]
class AdresseController extends AbstractController
{
    #[Route('/adresse', name: 'app_adresse')]
    public function listeAdresse(): Response
    {
        return $this->render('adresse/index.html.twig', [
            'controller_name' => 'AdresseController',
        ]);
    }
    #[Route('/detail/{id}', name: '_detail',requirements: ['id' =>'\d+'])]
    public function DetailAdresse(int $id, AdresseRepository $adresseRepository ): Response
    {
        $adresse = $adresseRepository->find($id);

        return $this->render('sortie_detail/sortie_detail.html.twig', [
            'adresse' => $adresse

        ]);
    }
}
