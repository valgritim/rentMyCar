<?php

namespace App\Form;

use App\Entity\Advert;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdvertType extends AbstractType
{
 
    /**
     * Permet d'avoir la configuration de base d'un champ
     *
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    private function getConfiguration($label, $placeholder){

        return [
            'label'=> $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
            ];
    }
    public function buildForm(FormBuilderInterface $builder, array $options) //dc pas besoin de la methode createFormBuilder()
    {
        $builder
            ->add(
                'title', 
                TextType::class, 
                $this->getConfiguration('Titre', 'Tapez un super titre pour votre annonce!'))            
            ->add(
                'slug', 
                TextType::class, 
                $this->getConfiguration('Adresse web', "Tapez l'adresse web (automatique)"))
            ->add(
                'coverImage', 
                UrlType::class, 
                $this->getConfiguration('Url de l\'image principale', 'Donnez l\'url d\'une image qui donne envie!'))            
            ->add(
                'introduction', 
                TextType::class, 
                $this->getConfiguration('Introduction', 'Donnez une description globale de l\'annonce'))
            ->add(
                'content', 
                TextareaType::class, 
                $this->getConfiguration('Description de l\'annonce', 'Entrez votre annonce' ))           
            ->add(
                'seats', 
                IntegerType::class, 
                $this->getConfiguration('Nombre de places', 'Donnez le nombre de places disponibles'))
            ->add(
                'price', 
                MoneyType::class, 
                $this->getConfiguration('Prix', 'Entrez un tarif journalier'))
            ->add(
                'images',
                CollectionType::class,// on veut répéter un formulaire en entier dans notre grand formulaire
                [
                    'entry_type' => ImageType::class,//chaque entrée de la collection
                    'allow_add' => true
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advert::class
        ]);
    }
}