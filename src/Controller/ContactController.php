<?php
namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $contact = new Contact();

        // Pré-remplir les informations utilisateur si l'utilisateur est connecté
        if ($this->getUser()) {
            $contact->setFirstName($this->getUser()->getFirstName())
                ->setLastName($this->getUser()->getLastName())
                ->setEmail($this->getUser()->getEmail());
        }

        $form = $this->createForm(ContactType::class, $contact);

        // Gestion du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            // Envoi de l'email
            $email = (new TemplatedEmail())
                ->from($contact->getEmail())
                ->to('admin@diossotourisme.com')
                ->subject($contact->getSubject())
                 // path of the Twig template to render
                ->htmlTemplate('emails/contact.html.twig')

                // change locale used in the template, e.g. to match user's locale
                ->locale('de')

                // pass variables (name => value) to the template
                ->context([
                    'contact' => $contact,
                ]);

            $mailer->send($email);

            // Message flash pour informer l'utilisateur
            $this->addFlash('success', 'Votre demande a été envoyée avec succès !');

            return $this->redirectToRoute('app_contact');
        }

        // Passer la clé ReCAPTCHA à la vue
    return $this->render('pages/contact/index.html.twig', [
        'form' => $form->createView(),
        
    ]);
    }
}
