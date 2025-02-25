<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isAdmin = $options['is_admin']; // Récupération du paramètre

        $builder
            ->add('first_name', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'required' => false,
            ])
            ->add('address', TextareaType::class, [
                'label' => 'Adresse',
                'required' => false,
            ]);

        // Afficher uniquement pour les admins
        if ($isAdmin) {
            $builder
                ->add('email', EmailType::class, [
                    'label' => 'Email',
                    'attr' => ['class' => 'form-input'],
                ])
                ->add('plainPassword', PasswordType::class, [
                    'label' => 'Nouveau mot de passe (laisser vide si inchangé)',
                    'required' => false,
                    'attr' => ['class' => 'form-input'],
                ]);
        }

        $builder->add('submit', SubmitType::class, [
            'label' => 'Mettre à jour le profil',
            'attr' => ['class' => 'hidden'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'is_admin' => false, // Par défaut, ce n'est pas un admin
        ]);
    }
}
