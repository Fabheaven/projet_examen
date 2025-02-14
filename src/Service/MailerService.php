<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailerService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Envoie un e-mail de confirmation à un utilisateur.
     *
     * @param string $from L'adresse e-mail de l'expéditeur.
     * @param string $userEmail L'adresse e-mail du destinataire.
     * @param string $subject Le sujet de l'e-mail.
     * @param string $template Le nom du template Twig à utiliser (sans l'extension .html.twig).
     * @param array $context Les variables à passer au template Twig.
     * @throws TransportExceptionInterface Si l'envoi de l'e-mail échoue.
     */
    public function sendConfirmationEmail(
        string $from,
        string $userEmail,
        string $subject,
        string $template,
        array $context = []
    ): void {
        // Validation des paramètres obligatoires
        if (empty($from) || empty($userEmail) || empty($subject) || empty($template)) {
            throw new \InvalidArgumentException('Tous les paramètres obligatoires doivent être fournis.');
        }

        // Création de l'e-mail
        $email = (new TemplatedEmail())
            ->from(new Address($from)) // Utilisation de la classe Address pour l'expéditeur
            ->to(new Address($userEmail)) // Utilisation de la classe Address pour le destinataire
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context);

        // Envoi de l'e-mail
        $this->mailer->send($email);
    }
}