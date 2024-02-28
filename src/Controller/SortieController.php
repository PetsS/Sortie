<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\User;
use App\Form\CreerUneSortieType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/sortie', name: 'app_sortie')]
class SortieController extends AbstractController
{
    #[Route('/liste', name: '_liste')]
    public function listeSortie(SortieRepository $sortieRepository, Request $request): Response
    {
        $user = $this->getUser();
        $sortie = new Sortie();
        $dateNow = new \DateTime();

        $form = $this->createForm(SortieType::class, null, ['method' => 'GET']);
        $form->handleRequest($request);

        if (($sortie->getDateDebut() < $dateNow)) {

            if ($form->isSubmitted() && $form->isValid()) {

                $site = $form->get('site')->getData();
                $nom = $form->get('nom')->getData();
                $dateDebut = $form->get('dateDebut')->getData();
                $dateFin = $form->get('dateFin')->getData();
                $checkOrga = $form->get('checkOrganisateur')->getData();
                $checkParticipant = $form->get('checkParticipant')->getData();
                $checkNonParticipant = $form->get('checkNonParticipant')->getData();
                $datePasse = $form->get('datePasse')->getData();


                $sorties = $sortieRepository->findFilteredSorties($user, [
                    'site' => $site,
                    'nom' => $nom,
                    'dateDebut' => $dateDebut,
                    'dateFin' => $dateFin,
                    'checkOrganisateur' => $checkOrga,
                    'checkParticipant' => $checkParticipant,
                    'checkNonParticipant' => $checkNonParticipant,
                    'datePasse' => $datePasse

                ]);

            } else {
                $sorties = $sortieRepository->findSortiesCourant([
                    'dateNow' => $dateNow,
                ]);
            }
        }

        $isUserInscrit = [];
        $isOrganisateur = [];

        foreach ($sorties as $sortie) {
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

    #[Route('/inscription/{sortie}', name: '_inscription', requirements: ['sortie' => '\d+'])]
    public function inscription(Sortie $sortie, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if ($user instanceof User) {
            $sortie->addParticipant($user);
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

        if ($user instanceof User) {
            $sortie->removeParticipant($user);
        }

        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'Vous avez été retiré de la liste des participants !');
        return $this->redirectToRoute('app_sortie_liste');
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
        $user = $this->getUser();

        $form = $this->createForm(CreerUneSortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortie->setEtat('EN ATTENTE');

            if ($user instanceof User) {
                $sortie->setSite($user->getSite());
                $sortie->setOrganisateur($user);
            }

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

            $this->addFlash('success', 'La sortie a été modifié.');
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
