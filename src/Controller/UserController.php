<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/profil', name: 'app_profil')]
class UserController extends AbstractController
{
    #[Route('/{id}', name: '', requirements: ['id' => '\d+'])]
    public function profil(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException("L'utilisateur n'existe pas!");
        }

        return $this->render('user/profil.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/creer', name: '_creer')]
//    #[IsGranted('ROLE_ADMIN')]
    public function creer(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('photo')->getData() instanceof UploadedFile) {
                $photoFile = $form->get('photo')->getData();
                $fileName = $slugger->slug($user->getPseudo()) . '-' . uniqid() . '.' . $photoFile->guessExtension();
                $photoFile->move($this->getParameter('photo_dir'), $fileName);
                $user->setPhoto($fileName);
            }

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'L\'utilisateur a été enregistrée');
            return $this->redirectToRoute('app_profil', ['id' => $user->getId()]);
        }

        return $this->render('user/creerUser.html.twig', [
            'form' => $form
        ]);
    }


    #[Route('/update/{id}', name: '_update', requirements: ['id' => '\d+'])]
    public function update(int $id, UserRepository $userRepository, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $user = $userRepository->find($id);

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('photo')->getData() instanceof UploadedFile) {
                $dir = $this->getParameter('photo_dir');
                $photoFile = $form->get('photo')->getData();
                $fileName = $slugger->slug($user->getPseudo()) . '-' . uniqid() . '.' . $photoFile->guessExtension();
                $photoFile->move($dir, $fileName);

                if ($user->getPhoto() && \file_exists($dir . '/' . $user->getPhoto())) {
                    unlink($dir . '/' . $user->getPhoto());
                }

                $user->setPhoto($fileName);

            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'L\'utilisateur a été modifié');
            return $this->redirectToRoute('app_profil', ['id' => $id]);
        }

        return $this->render('user/updateUser.html.twig', [
            'form' => $form,
            'user' => $user
        ]);
    }

    #[Route('/supprimer/{id}', name: '_supprimer', requirements: ['id' => '\d+'])]
//    #[IsGranted('ROLE_ADMIN')]
    public function supprimer(int $id, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $user = $userRepository->find($id);

        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'Utilisateur a été supprimer!');

        return $this->redirectToRoute('app_sortie_liste');
    }




}
