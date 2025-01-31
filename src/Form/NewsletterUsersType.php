<?php

namespace App\Form;

use App\Entity\Newsletter\Categories;
use App\Entity\Newsletter\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\IsTrue;

class NewsletterUsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez saisir un e-mail']),
                    new Assert\Email(['message' => 'Veuillez saisir un e-mail valide']),
                    new Assert\Length([
                        'max' => 180,
                        'maxMessage' => 'Votre e-mail ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('isRgpd', CheckboxType::class, [
                'constraints' => [
                    new IsTrue([
                        'message' => "Vous avez lu notre <span><a href='#' target='_blank'>politique de confidentialité</a></span> et consentez à recevoir des communications marketing.",
                    ]),
                ],
                'label' => "Vous avez lu notre <span><a href='#' target='_blank'>politique de confidentialité</a></span> et consentez à recevoir des communications marketing",
                'label_html' => true, // Permet le rendu HTML dans le label
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
            'data_class' => Users::class,
        ]);
    }
}
