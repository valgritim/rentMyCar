<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Advert;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
     /**
     * Permet d'afficher une seule annonce
     * 
     * @Route("/user/{slug}", name= "user_show")
     * 
     */
   
    public function index(User $user)
    {       
        return $this->render('user/index.html.twig', [
            'user' => $user
        ]);
    }
}