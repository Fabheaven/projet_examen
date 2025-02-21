<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use App\Validator\Capitalized;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('first_name', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Veuillez saisir un prénom.']),
                new Length([
                    'min' => 3,
                    'max' => 50,
                    'minMessage' => 'Votre prénom doit comporter au moins {{ limit }} caractères.',
                    'maxMessage' => 'Votre prénom ne doit pas dépasser {{ limit }} caractères.',
                ]),
                new Capitalized(), // Ajout de la contrainte personnalisée
            ],
        ])
        ->add('last_name', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Veuillez saisir un nom.']),
                new Length([
                    'min' => 3,
                    'max' => 20,
                    'minMessage' => 'Votre nom doit comporter au moins {{ limit }} caractères.',
                    'maxMessage' => 'Votre nom ne doit pas dépasser {{ limit }} caractères.',
                ]),
                new Capitalized(), // Ajout de la contrainte personnalisée
            ],
        ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut être vide.']),
                    new Email([
                        'message' => 'L\'adresse email "{{ value }}" n\'est pas valide. Elle doit être au format nom@nom.nom.',
                        'mode' => 'strict', 
                    ]),
                    new Length([
                        'min' => 4,
                        'max' => 255,
                        'minMessage' => 'Votre email doit comporter au minimum {{ limit }} caractères.',
                        'maxMessage' => 'Votre email doit comporter au maximum {{ limit }} caractères.',
                    ])
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs du mot de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmez le mot de passe'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un mot de passe.']),
                    new Length([
                        'min' => 8,
                        'max' => 255,
                        'minMessage' => 'Votre mot de passe doit comporter au minimum {{ limit }} caractères.',
                        'maxMessage' => 'Votre mot de passe doit comporter au maximum {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/',
                        'message' => 'Votre mot de passe doit contenir au moins une lettre minuscule, une majuscule, un chiffre, un caractère spécial (@, #, $, etc.) et un minimum de 8 caractères.',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue(['message' => "Vous devez accepter nos conditions."]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
