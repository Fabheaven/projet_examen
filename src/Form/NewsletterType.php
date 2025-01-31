<?php

namespace App\Form;

use App\Entity\Newsletter\Categories;
use App\Entity\Newsletter\Newsletters;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NewsletterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('content', TextareaType::class)
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Créer la Newsletter',
                'attr' => [
                    'class' => 'btn btn-primary mt-4',
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Mettez ici la classe Newsletters au lieu de Users
        $resolver->setDefaults([
            'data_class' => Newsletters::class,
        ]);
    }
}
