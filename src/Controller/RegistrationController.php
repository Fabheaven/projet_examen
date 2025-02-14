<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use App\Service\JWTService;
use App\Service\MailerService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        Security $security,
        EntityManagerInterface $entityManager,
        JWTService $jwt,
        MailerService $mailerService
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le mot de passe depuis le champ "password"
            $password = $form->get('password')->getData();

            // Encoder le mot de passe
            $user->setPassword($userPasswordHasher->hashPassword($user, $password));

            // Enregistrer l'utilisateur en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Générer le token JWT
            $header = ["alg" => "HS256", "typ" => "JWT"];
            $payload = ['user_id' => $user->getId()];
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // Envoi de l'e-mail de confirmation
            $mailerService->sendConfirmationEmail(
                'no-reply@diossotourisme.com',
                $user->getEmail(),
                'Activation de votre compte sur le site Diosso Tourisme',
                'confirmation',
                compact('user', 'token')
            );

            $this->addFlash("success", "🎉 Félicitations ! Votre inscription est réussie. Pour activer votre compte, veuillez cliquer sur le lien de confirmation envoyé à votre adresse e-mail. 📩✨");


            // Connecter l'utilisateur automatiquement après l'inscription
            return $security->login($user, UserAuthenticator::class, 'main');
        }

        // Afficher le formulaire d'inscription
        return $this->render('pages/registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    #[Route('check/{token}', name: 'check_user')]
    public function checkUser($token, JWTService $jwt, 
    UserRepository $userRepository, EntityManagerInterface $em) : Response 
    {
        // Vérification de la validité du  token et pas expiré
        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))){
            
            // Le token  est valide
            // On récupère les données
            $payload = $jwt->getPayload($token);

            // On récupère un user
            $user = $userRepository->find($payload['user_id']);

            // vérifie qu'on un user et qu'il n'est pas déjà activé
            if($user && !$user->getIsVerified()){
                $user->setIsVerified(true);
                $em->flush();

                $this->addFlash('success', '✅ Félicitations ! Votre compte a été activé avec succès. Vous pouvez maintenant vous connecter.');
                
                return $this->redirectToRoute('app_home');
            }
        }

        $this->addFlash('danger', 'Le token est invalide ou a expiré');
                
        return $this->redirectToRoute('app_login');
    }
}
