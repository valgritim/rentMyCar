<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Form\AdvertType;
use App\Repository\AdvertRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdvertController extends AbstractController
{
    /**
     * @Route("/adverts", name="adverts_index")
     */
    public function index(AdvertRepository $repo)
    {
        //$repo = $this->getDoctrine()->getRepository(Advert::class);
        $adverts = $repo->findAll();

        return $this->render('advert/index.html.twig', [
            'adverts' => $adverts
        ]);
    }

     /**
     * Permet de créer une annonce
     * 
     * @Route("/adverts/new", name="adverts_create")
     * 
     * @return Response
     */

     public function create(){

        $advert = new Advert();

        $form = $this->createForm(AdvertType::class, $advert);

        return $this->render('advert/new.html.twig',
            [
                'form' => $form->createView()
            ]);
     }

    /**
     * Permet d'afficher une seule annonce
     * 
     * @Route("/adverts/{slug}", name= "adverts_show")
     * 
     * @return Response
     */

    public function show($slug, Advert $advert){ //AdvertRepository $repo
        //je récupère l'annonce qui correspond au slug
        //$advert = $repo->findOneBySlug($slug);              
        

        return $this->render('advert/show.html.twig',
            [
                'advert' => $advert
        ]);

    }
   

}
