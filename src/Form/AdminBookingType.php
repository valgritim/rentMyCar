<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Advert;
use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class AdminBookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text'
            ])            
            ->add('comment')
            ->add('booker', EntityType::class, [
                'class' => User::class,
                'choice_label' => function($user){
                    return $user->getFirstName() . " " . strtoupper($user->getLastName());
                }
            ])
            //EntityType permet d'aller chercher les infos sur le user car on ne peut pas avoir le booker directement: relation entre les entités.Idem pour Advert
            ->add('advert', EntityType::class, [
                'class' => Advert::class,
                'choice_label' => 'title'
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
