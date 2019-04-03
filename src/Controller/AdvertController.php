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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @IsGranted("ROLE_USER")
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
            foreach($advert->getImages() as $image){
                $image->setAdvert($advert);
                $manager->persist($image);
            }

            $advert->setAuthor($this->getUser()); //l'annonce doit etre liée au user qui est connecté
            
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
     * @Route("/adverts/{slug}/edit", name= "adverts_edit")
     * @Security("is_granted('ROLE_USER') and user == advert.getAuthor()")
     * 
     * @return Response
     */

     public function edit(Advert $advert, Request $request, ObjectManager $manager){             

        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // $manager = $this->getDoctrine()->getManager();j'ai mis en arg le manager et importé le package donc plus besoin de faire l'appel
            foreach($advert->getImages() as $image){
                $image->setAdvert($advert);
                $manager->persist($image);
            }
            
            $manager->persist($advert);
            $manager->flush();
           
            
            $this->addFlash('success', "L'annonce <strong>{$advert->getTitle()}</strong> a bien été modifiée! ");

            return $this->redirectToRoute('adverts_show', [
                'slug' => $advert->getSlug()
            ]);
        }

        return $this->render('advert/edit.html.twig',
                [
                    'form' => $form->createView(),
                    'advert' => $advert
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

    /**
     * Permet de supprimer une annonce
     * 
     * @Route("/adverts/{slug}/delete", name="adverts_delete")
     * @Security("is_granted('ROLE_USER') and user == advert.getAuthor()")
     * 
     * @return Response
     */
    
    public function delete(Advert $advert, ObjectManager $manager){
        $manager->remove($advert);
        $manager->flush();

        $this->addFlash("success", "L'annonce a bien été supprimée !");
        return $this->redirectToRoute("adverts_index");

    }

}
