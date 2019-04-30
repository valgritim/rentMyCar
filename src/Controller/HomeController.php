<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Repository\AdvertRepository;
use App\Repository\UserRepository;

class HomeController extends Controller{
    /**
      * @Route("/", name="homepage")
      */
 
    public function home(AdvertRepository $advertRepo, UserRepository $userRepo){
      return $this->render('home.html.twig', [
          'adverts' => $advertRepo->findBestAdverts(3),
          'users' => $userRepo->findBestUsers(2)
      ]
  
    );
    
   }
   
 }