<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class, [
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez saisir un prénom']),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Votre prénom doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Votre prénom ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez saisir un nom']),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Votre nom doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Votre nom ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez saisir un e-mail']),
                    new Assert\Email(['message' => 'Veuillez saisir un e-mail valide']),
                    new Assert\Length([
                        'max' => 180,
                        'maxMessage' => 'Votre e-mail ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('subject', TextType::class, [
                'label' => "Sujet",
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'attr' => [
                    'minlength' => '2',
                    'maxlength' => '100',
                ],
                'constraints' => [
                    new Assert\Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => "L'objet doit comporter au moins {{ limit }} caractères.",
                        'maxMessage' => "L'objet ne doit pas dépasser {{ limit }} caractères.",
                    ]),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => "Votre message",
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez saisir un message.']),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'btn btn-primary mt-4',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
