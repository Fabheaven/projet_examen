<?php

namespace App\Controller;

use App\Form\ResetPasswordRequestFormType;
use App\Form\ResetPasswordFormType; // Assurez-vous que ce formulaire est importé
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

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('pages/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \Exception('Ne pas oublier de configurer le firewall dans security.yaml.');
    }

    #[Route('/mot-de-passe-oublie', name: 'request_password')]
    public function requestPassword(Request $request, UserRepository $userRepository, 
    JWTService $jwt, MailerService $mailerService ): Response
    {
        // Formulaire pour la demande de réinitialisation (email)
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de l'utilisateur via son email
            $user = $userRepository->findOneByEmail($form->get('email')->getData());

            if ($user) {
                // Génération du token JWT
                $header = ["alg" => "HS256", "typ" => "JWT"];
                $payload = ['user_id' => $user->getId()];
                $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

                // Génération de l'URL de réinitialisation
                $url = $this->generateUrl('reset_password', ['token' => $token], 
                UrlGeneratorInterface::ABSOLUTE_URL);

                // Envoi de l'e-mail
                $mailerService->sendConfirmationEmail(
                    'no-reply@diossotourisme.com',
                    $user->getEmail(),
                    'Récupération de mot de passe sur le site Diosso Tourisme',
                    'password_reset',
                    compact('user', 'url')
                );

                $this->addFlash("success", "Email envoyé avec succès");
                return $this->redirectToRoute('app_login');
            }

            // Utilisateur non trouvé
            $this->addFlash('danger', 'Un problème est survenu');
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
        // Vérification de la validité du token
        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))){
            
            $payload = $jwt->getPayload($token);
            $user = $userRepository->find($payload['user_id']);

            if ($user) {
                // Créer et gérer le formulaire pour réinitialiser le mot de passe
                $form = $this->createForm(ResetPasswordFormType::class);

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    // Mettre à jour le mot de passe
                    $user->setPassword(
                        $passwordHasher->hashPassword($user, $form->get('password')->getData())
                    );

                    $em->flush();

                    $this->addFlash('success', 'Votre mot de passe a été changé avec succès.');

                    return $this->redirectToRoute('app_login');
                }

                return $this->render('pages/security/reset_password.html.twig', [
                    'PassForm' => $form->createView(),
                ]);
            }
        }

        $this->addFlash('danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('app_login');
    }
}
