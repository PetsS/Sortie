<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\User;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sortie', name: 'app_sortie')]
class SortieController extends AbstractController
{
    #[Route('/liste', name: '_liste')]
    public function listeSortie(SortieRepository $sortieRepository, Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(SortieType::class, null, ['method' => 'GET']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // TODO custom queryBuilder dans SortieReposisory

            $site = $form->get('site')->getData();
            $nom = $form->get('nom')->getData();
            $dateDebut = $form->get('dateDebut')->getData();

            $sorties = $sortieRepository->findFilteredSorties([
                'site' => $site,
                'nom' => $nom,
                'dateDebut' => $dateDebut,

            ]);

        } else {
            $sorties = $sortieRepository->findAll();
        }


        $isUserInscrit = [];
        $isOrganisateur = [];

        foreach ($sorties as $sortie){
            $isUserInscrit[$sortie->getId()] = $sortie->getParticipants()->contains($user);
            $isOrganisateur[$sortie->getId()] = $sortie->getOrganisateur() === $user;
        }

        return $this->render('sortie/listeSortie.html.twig', [
            'sorties' => $sorties,
            'isUserInscrit' => $isUserInscrit,
            'isOrganisateur' => $isOrganisateur,
            'form' => $form
        ]);
    }

    #[Route('/reset', name: '_reset')]
    public function resetListe(): Response
    {
        return $this->redirectToRoute('app_sortie_liste');
    }

    #[Route('/detail/{id}', name: '_detail', requirements: ['id' => '\d+'])]
    public function detail( int $id): Response
    {
        return $this->render('sortie/infoSortie.html.twig', [
            'id' => $id
        ]);
    }

    #[Route('/inscription/{sortie}', name: '_inscription', requirements: ['sortie' => '\d+'])]
    public function inscription(Sortie $sortie, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if ($user instanceof User){
            $sortie-> addParticipant($user);
        }

        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'Vous avez été ajouté à la liste des participants !');
        return $this->redirectToRoute('app_sortie_liste');
    }

    #[Route('/desinscription/{sortie}', name: '_desinscription', requirements: ['sortie' => '\d+'])]
    public function desinscription(Sortie $sortie, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if ($user instanceof User){
            $sortie-> removeParticipant($user);
        }

        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'Vous avez été retiré de la liste des participants !');
        return $this->redirectToRoute('app_sortie_liste');
    }
}
