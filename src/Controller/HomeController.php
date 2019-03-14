<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller{
    /**
      * @Route("/", name="homepage")
      */
 
  public function home(){
   
    $prenoms = ["Benjamin" => 45, "Alexis" => 22, "Arnaud" => 20];
     return $this->render('home.html.twig',
      [ 'title' => 'Bonjour!',
      'age' => 12,
      'tableau' => $prenoms]);
   }
   /**
    * Montre la page qui dit bonjour
    * @Route("/hello/{prenom}/{age}", name="hello")
    *
    */
   public function hello($prenom = "anonyme",$age = 0){
    return $this->render('hello.html.twig', [ 'prenom' => $prenom, 'age' => $age]);

   }

 }