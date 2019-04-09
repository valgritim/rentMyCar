<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;

class BookingType extends ApplicationType
{

    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer){
         $this->transformer = $transformer;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', TextType::class, $this->getConfiguration("Date de récupération du véhicule", "Date à partir de laquelle vous souhaitez louer le véhicule"))
            ->add('endDate', TextType::class, $this->getConfiguration("Date de fin de location", "Date à laquelle vous restituez le véhicule"))
            ->add('comment', TextareaType::class, $this->getConfiguration(false, "Si vous souhaitez laisser un commentaire...", ['required' => false])); 
        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
