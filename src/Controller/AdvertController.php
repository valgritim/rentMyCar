<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Repository\AdvertRepository;
use Symfony\Component\Routing\Annotation\Route;
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
     * Permet d'afficher une seule annonce
     * 
     * @Route("/adverts/{slug}", name= "adverts_show")
     * 
     * @return Response
     */

    public function show($slug, AdvertRepository $repo){
        //je récupère l'annonce qui correspond au slug
        $advert = $repo->findOneBySlug($slug);              
        

        return $this->render('advert/show.html.twig',
            [
                'advert' => $advert
        ]);

    }
}
