<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class UserController extends AbstractController
{
    #[Route('/admin/users', name: 'admin_user_list')]
    #[IsGranted('ROLE_ADMIN')] // Seuls les admins peuvent accéder
    public function listUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('admin/user/user.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/users/delete/{id}', name: 'admin_user_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')] // Seuls les admins peuvent supprimer
    public function deleteUser(User $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'Utilisateur supprimé avec succès.');

        return $this->redirectToRoute('admin_user_list');
    }

    #[Route('/admin/users/edit/{id}', name: 'admin_user_edit')]
    #[IsGranted('ROLE_ADMIN')] // Seuls les admins peuvent modifier
    public function editUser(Request $request, User $user, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Utilisateur mis à jour.');
            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/admin/users/reset-password/{id}', name: 'admin_user_reset_password', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')] // Seuls les admins peuvent réinitialiser le mot de passe
    public function resetPassword(
        User $user,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): Response {
        $newPassword = bin2hex(random_bytes(8)); // Exemple de mot de passe aléatoire
        $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);
        $em->flush();

        $this->addFlash('success', 'Mot de passe réinitialisé. Nouveau mot de passe : ' . $newPassword);
        return $this->redirectToRoute('admin_user_list');
    }

    #[Route('/admin/users/new', name: 'admin_user_new')]
    #[IsGranted('ROLE_ADMIN')] // Seuls les admins peuvent créer des utilisateurs
    public function createUser(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
            $user->setRoles(['ROLE_ADMIN']); // Ou 'ROLE_MANAGER'
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Utilisateur créé.');
            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
