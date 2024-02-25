<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AdresseType;
use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/adresse', name: 'app_adresse')]
class AdresseController extends AbstractController
{
    #[Route('/liste', name: '_liste')]
    public function listeAdresse(): Response
    {
        return $this->render('adresse/listeAdresse.html.twig', [
            'controller_name' => 'AdresseController',
        ]);
    }
    #[Route('/detail/{id}', name: '_detail',requirements: ['id' =>'\d+'])]
    public function DetailAdresse(int $id, AdresseRepository $adresseRepository ): Response
    {
        $adresse= $adresseRepository->find($id);

        return $this->render('adresse/index.html.twig', [
            'adresse' => $adresse

        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(Request $request, EntityManagerInterface $em): Response

    {
        $adresse = new Adresse();

        $form = $this->createForm(AdresseType::class, $adresse);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($adresse);
            $em->flush();

            $this->addFlash('success', 'La adresse a été enregistrée');

            return $this->redirectToRoute('app_adresse_liste');

        }

        return $this->render('adresse/editadresse.html.twig', [

            'form' => $form

        ]);


    }
}


