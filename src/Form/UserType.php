<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ de vérification de l'email
            ->add('isVerified', CheckboxType::class, [
                'required' => false
            ])
            // Champ pour l'email
            ->add('email')
            // Champs pour les informations personnelles
            ->add('lastName')
            ->add('firstName')
            ->add('phone')
            ->add('address')
            // Modification des rôles (choix multiples)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Gestionnaire' => 'ROLE_GESTIONNAIRE',
                    'Utilisateur' => 'ROLE_USER',
                ],
                'multiple' => true, // Autorise la sélection de plusieurs rôles
                'expanded' => true, // Affiche sous forme de cases à cocher
            ])
            // Champ de mot de passe (utilisé si modification du mot de passe)
            ->add('password', PasswordType::class, [
                'required' => false, // Si le mot de passe n'est pas requis
            ]);

        // Ajout des dates seulement si l'utilisateur existe déjà
        if (!$options['is_new']) {
            $builder
                ->add('createdAt', DateType::class, [
                    'widget' => 'single_text',
                    'attr' => ['readonly' => true]
                ])
                ->add('updatedAt', DateType::class, [
                    'widget' => 'single_text',
                    'attr' => ['readonly' => true]
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_new' => true, // Défaut à "true" pour éviter les erreurs si non défini
        ]);
    }
}
