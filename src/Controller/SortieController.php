<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\CreerUneSortieType;
use App\Form\UserType;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/sortie', name: 'app_sortie')]
class SortieController extends AbstractController
{
    #[Route('/liste', name: '_liste')]
    public function listeSortie(): Response
    {
        return $this->render('sortie/listeSortie.html.twig');
    }

    #[Route('/detail/{id}', name: '_detail',requirements: ['id' =>'\d+'])]
    public function Detaildelasortie(int $id, SortieRepository $sortieRepository): Response
    {
        $sortie = $sortieRepository->find($id);

        return $this->render('sortie_detail/sortie_detail.html.twig', [
            'sortie' => $sortie

        ]);
    }

       #[Route('/create', name: '_create')]
    public function create(Request $request, EntityManagerInterface $em): Response

    {
        $sortie = new Sortie();

        $form = $this->createForm(CreerUneSortieType::class, $sortie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($sortie);
            $em->flush();

             $this->addFlash('success', 'La sortie a été enregistrée');

            return $this->redirectToRoute('app_adresse_create');

        }

        return $this->render('sortie_detail/edit.html.twig', [

            'form' => $form

        ]);

    }

    #[Route('/update/{id}', name: '_update', requirements: ['id' => '\d+'])]
    public function update(int $id, SortieRepository $sortieRepository, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $sortie = $sortieRepository->find($id);

        $form = $this->createForm(CreerUneSortieType::class, $sortie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'La sortie a été modifié');
            return $this->redirectToRoute('app_adresse_update', ['id' => $id]);
        }

        return $this->render('sortie_detail/sortieupdate.html.twig', [
            'form' => $form,
            'sortie' => $sortie
        ]);
    }

    #[Route('/delete/{id}', name: '_delete', requirements: ['id' => '\d+'])]
    //#[IsGranted('ROLE_ADMIN')]
    public function supprimer(Sortie $sortie, EntityManagerInterface $em): Response
    {


            $em->remove($sortie);
            $em->flush();

            $this->addFlash('success', 'la sortie a été supprimer!');

            return $this->redirectToRoute('app_sortie_liste');


    }










}
