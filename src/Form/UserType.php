<?php

namespace App\Form;

use App\Entity\Articles\Activity;
use App\Entity\Articles\Cart;
use App\Entity\Articles\Circuit;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id')
            ->add('userInitial')
            ->add('isVerified')
            ->add('email')
            ->add('lastName')
            ->add('firstName')
            ->add('phone')
            ->add('address')
            ->add('roles')
            ->add('password')
            ->add('createdAt', null, [
                'widget' => 'single_text'
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text'
            ])
            ->add('cart', EntityType::class, [
                'class' => Cart::class,
'choice_label' => 'id',
            ])
            ->add('activities', EntityType::class, [
                'class' => Activity::class,
'choice_label' => 'id',
'multiple' => true,
            ])
            ->add('circuits', EntityType::class, [
                'class' => Circuit::class,
'choice_label' => 'id',
'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
