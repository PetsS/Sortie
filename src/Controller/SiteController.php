<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\SiteType;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/site', name: 'app_site')]
class SiteController extends AbstractController
{
    #[Route('/liste', name: '_liste')]
    public function index(SiteRepository $siteRepository): Response
    {
        $sites = $siteRepository->findAll();

        return $this->render('site/listeSite.html.twig', [
            'sites' => $sites,
        ]);
    }

    #[Route('/create', name: '_create')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $site = new Site();

        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($site);
            $em->flush();

            $this->addFlash('success', 'Le campus a été enregistrée');
            return $this->redirectToRoute('app_site_liste');
        }

        return $this->render('site/createSite.html.twig', [
            'form' => $form
        ]);
    }


    #[Route('/update/{id}', name: '_update', requirements: ['id' => '\d+'])]
    public function update(int $id, SiteRepository $siteRepository, Request $request, EntityManagerInterface $em): Response
    {
        $site = $siteRepository->find($id);

        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($site);
            $em->flush();

            $this->addFlash('success', 'Le campus a été modifié');
            return $this->redirectToRoute('app_site_liste');
        }

        return $this->render('site/updateSite.html.twig', [
            'form' => $form,
            'site' => $site
        ]);
    }

    #[Route('/delete/{id}', name: '_delete', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(int $id, SiteRepository $siteRepository, EntityManagerInterface $em): Response
    {
        $site = $siteRepository->find($id);

        $em->remove($site);
        $em->flush();

        $this->addFlash('success', 'Campus a été supprimer!');

        return $this->redirectToRoute('app_site_liste');
    }
}
