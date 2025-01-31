<?php

namespace App\Controller;

use App\Entity\Newsletter\Newsletters;
use App\Entity\Newsletter\Users;
use App\Form\NewsletterUsersType;
use App\Form\NewsletterType;
use App\Repository\Newsletter\NewslettersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class NewsletterController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/newsletter', name: 'app_newsletter')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $user = new Users();
        $form = $this->createForm(NewsletterUsersType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $validationToken = hash('sha256', uniqid());

            $user->setValidationToken($validationToken);
            $user->setValid(false);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $email = (new TemplatedEmail())
                ->from('newsletter@diossotourisme.fr')
                ->to($user->getEmail())
                ->subject('Confirmez votre inscription à notre newsletter')
                ->htmlTemplate('emails/registration.html.twig')
                ->context([
                    'user' => $user,
                    'validationToken' => $validationToken,
                ]);

            $mailer->send($email);

            $this->addFlash('success', 'Un e-mail de confirmation vous a été envoyé. Veuillez vérifier votre boîte mail.');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pages/newsletter/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/confirm/{id}/{token}', name: 'app_confirm')]
    public function confirm(Users $user = null, string $token): Response
    {
        if (!$user || $user->getValidationToken() !== $token) {
            throw $this->createNotFoundException('Lien de confirmation invalide ou expiré.');
        }

        $user->setValid(true); // L'utilisateur devient valide
        // Le token reste en place pour une utilisation ultérieure
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'Votre abonnement a été confirmé. Bienvenue dans notre communauté !');

        return $this->redirectToRoute('app_home');
    }


    #[Route('newsletter/prepareNewsletter', name: 'app_prepareNewsletter')]
    public function prepareNewsletter(Request $request): Response
    {
        $newsletter = new Newsletters();
        $form = $this->createForm(NewsletterType::class, $newsletter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($newsletter);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_newsletterList');
        }

        return $this->render('/pages/newsletter/prepareNewsletter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('newsletter/newsletterList', name: 'app_newsletterList')]
    public function newsletterList(NewslettersRepository $newsletterRepository): Response
    {
        // Récupérer toutes les newsletters depuis le repository
        $newsletters = $newsletterRepository->findAll();
    
        // Rendre la vue avec les newsletters
        return $this->render('/pages/newsletter/newsletterList.html.twig', [
            'newsletters' => $newsletters,
        ]);
    }
    

    #[Route('newsletter/newsletterSend/{id}', name: 'app_newsletterSend', methods: ['GET'])]
    public function sendNewsletter(
        int $id,
        NewslettersRepository $newslettersRepository,
        MailerInterface $mailer
    ): Response {
        $newsletter = $newslettersRepository->find($id);

        if (!$newsletter) {
            throw $this->createNotFoundException('La newsletter demandée est introuvable.');
        }

        $categories = $newsletter->getCategories();
        if (!$categories || $categories->getUsers()->isEmpty()) {
            $this->addFlash('error', 'Aucun utilisateur associé à cette catégorie.');
            return $this->redirectToRoute('app_newsletterList');
        }

        foreach ($categories->getUsers() as $user) {
            if ($user->getIsValid()) {
                $email = (new TemplatedEmail())
                    ->from('newsletter@diossotourisme.fr')
                    ->to($user->getEmail())
                    ->subject($newsletter->getName())
                    ->htmlTemplate('emails/newsletterSend.html.twig')
                    ->context([
                        'newsletter' => $newsletter,
                        'user' => $user,
                    ]);

                $mailer->send($email);
            }
        }

        $this->addFlash('success', 'La newsletter a été envoyée avec succès.');

        $newsletter->setSent(true);
        $this->entityManager->persist($newsletter);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_newsletterList');
    }

    #[Route('/unsubscribe/{id}/{newsletter}/{token}', name: 'app_unsubscribe')]
    public function unsubscribe(Users $user, Newsletters $newsletter, $token, EntityManagerInterface $em): Response
    {
        // Vérification si le token de validation de l'utilisateur est valide
        if ($user->getValidationToken() !== $token) {
            throw $this->createNotFoundException('Le lien de désinscription est invalide ou expiré.');
        }
    
        // Vérification du nombre de catégories associées à l'utilisateur
        if (count($user->getCategories()) > 1) {
            // Retirer la catégorie liée à la newsletter
            $user->removeCategory($newsletter->getCategories());
            $em->persist($user);
        } else {
            // Si l'utilisateur n'a plus que cette catégorie, on le supprime
            $em->remove($user);
        }
    
        // Appliquer les changements dans la base de données
        $em->flush();
    
        // Ajouter un message flash pour indiquer le succès de la désinscription
        $this->addFlash('success', 'Votre désinscription a été confirmée.');
    
        // Rediriger vers la page d'accueil ou une autre page
        return $this->redirectToRoute('app_home');
    }
    
}
