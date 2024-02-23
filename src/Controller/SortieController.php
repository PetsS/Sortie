<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\CreerUneSortieType;
use App\Repository\AdresseRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
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
    public function Detaildelasortie(int $id, SortieRepository $sortieRepository, AdresseRepository $adresseRepository): Response
    {
        $sortie = $sortieRepository->find($id);
        return $this->render('sortie_detail/sortie_detail.html.twig', [
            'sortie' => $sortie
        ]);
    }

       #[Route('/create', name: '_create')]
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response

    {

        $sortie = new Sortie();

        $form = $this->createForm(CreerUneSortieType::class, $sortie);



        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em= $this->getDoctrine()->getManager();
            $em->persist($sortie);

            $em->flush();

            // $this->addFlash('success', 'La série a été enregistrée');

            return $this->redirectToRoute('app_sortie_liste');

        }

        return $this->render('sortie_detail/sortie_detail.html.twig', [

            'form' => $form

        ]);

    }







}
