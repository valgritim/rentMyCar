<?php

namespace App\Form;
use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType{

    protected function getConfiguration($label, $placeholder, $options = []){
        //array merge permet de merger l array label+attr avec l array d'options facultatif. voir slug
        return array_merge([
            'label'=> $label,
            'attr' => [
                'placeholder' => $placeholder
                 ]
            ], $options);
    }
}