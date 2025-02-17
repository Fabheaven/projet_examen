<?php

namespace App\Controller;

use App\Form\ResetPasswordRequestFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\UserRepository;
use App\Service\JWTService;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;


class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('pages/security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Le firewall de sécurité doit être configuré pour gérer la déconnexion.');
    }

    #[Route('/mot-de-passe-oublie', name: 'request_password')]
    public function requestPassword(
        Request $request,
        UserRepository $userRepository,
        JWTService $jwt,
        MailerService $mailerService
    ): Response {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneByEmail($form->get('email')->getData());

            if ($user) {
                $token = $jwt->generate(
                    ["alg" => "HS256", "typ" => "JWT"],
                    ['user_id' => $user->getId()],
                    $this->getParameter('app.jwtsecret')
                );

                $url = $this->generateUrl('reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                $mailerService->sendConfirmationEmail(
                    'no-reply@diossotourisme.com',
                    $user->getEmail(),
                    'Récupération de mot de passe sur le site Diosso Tourisme',
                    'password_reset',
                    compact('user', 'url')
                );

                $this->addFlash("success", "Un email de récupération a été envoyé.");
                return $this->redirectToRoute('app_login');
            }

            $this->addFlash('danger', 'Aucun compte ne correspond à cet email.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('pages/security/reset_password_request.html.twig', [
            'requestPassForm' => $form->createView(),
        ]);
    }

    #[Route('/mot-de-passe-oublie/{token}', name: 'reset_password')]
    public function resetPassword(
        string $token,
        JWTService $jwt,
        UserRepository $userRepository,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): Response {
        if (!$jwt->isValid($token) || $jwt->isExpired($token) || !$jwt->check($token, $this->getParameter('app.jwtsecret'))) {
            $this->addFlash('danger', 'Le token est invalide ou a expiré.');
            return $this->redirectToRoute('app_login');
        }

        $payload = $jwt->getPayload($token);
        $user = $userRepository->find($payload['user_id']);

        if (!$user) {
            $this->addFlash('danger', 'Utilisateur introuvable.');
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
            $em->flush();

            $this->addFlash('success', 'Votre mot de passe a été changé avec succès.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('pages/security/reset_password.html.twig', [
            'PassForm' => $form->createView(),
        ]);
    }

}
