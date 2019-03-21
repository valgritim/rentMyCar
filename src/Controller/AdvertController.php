<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Advert;
use App\Form\AdvertType;
use App\Repository\AdvertRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
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

     public function create(Request $request, ObjectManager $manager){ //Request $request recupere les données du formulaire apres submit

        $advert = new Advert();               

        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);//le form va récuperer les infos grace à request et les lier à $advert; donc il connait les champs 
        //verification du submit et des données avant envoi par Doctrine à la BD
        
               
        if($form->isSubmitted() && $form->isValid()){
            // $manager = $this->getDoctrine()->getManager();j'ai mis en arg le manager et importé le package donc plus besoin de faire l'appel
            $manager->persist($advert);
            $manager->flush();
           
            
            $this->addFlash('success', "L'annonce <strong>{$advert->getTitle()}</strong> a bien été enregistrée! ");

            return $this->redirectToRoute('adverts_show', [
                'slug' => $advert->getSlug()
            ]);
        }
        
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
