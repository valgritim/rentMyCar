<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Form\AdvertType;
use App\Repository\AdvertRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/adverts", name="admin_adverts")
     */
    public function index(AdvertRepository $repo)
    {
        return $this->render('admin/advert/index.html.twig', [
            'adverts' => $repo->findAll()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     * @Route("admin/adverts/{id}/edit", name="admin_adverts_edit")
     * @param Advert $advert
     * @return Response
     */
    public function edit(Advert $advert, Request $request, ObjectManager $manager){

        $form = $this->createForm(AdvertType::class, $advert);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($advert);
            $manager->flush();

            $this->addFlash("success", "L'annonce <strong>{$advert->getTitle()}</strong> a bien été enregistrée ! ");
        };

        return $this->render('admin/advert/edit.html.twig', [
            'advert' => $advert,
            'form' => $form->createView()
        ]);
    }
}
