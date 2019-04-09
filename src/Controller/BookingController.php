<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Entity\Booking;
use App\Form\BookingType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;


class BookingController extends AbstractController
{
    /**
     * @Route("/adverts/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     */
    public function book(Advert $advert, Request $request, ObjectManager $manager)
    {   
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        //je dis au formulaire de regarder la requete que je lui passe

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();

            $booking->setBooker($user)
                    ->setAdvert($advert);
            //Si les dates ne sont pas dispo, message d'erreur
            
            if(!$booking->isBookableDates()){
                $this->addFlash('warning', 'Les dates que vous avez choisies sont déjà prises ! ');
            } else { 
                //sinon enregistrement et redirection
                        

            $manager->persist($booking);
            $manager->flush();

            return $this->redirectToRoute('booking_show', ['id' => $booking->getId(), 'success' => true]);
            //success va passer en parametre en GET ex /booking/180?success=true
            }
        }
       

        return $this->render('booking/book.html.twig', [
            'advert' => $advert,
            'form' => $form->createView()
        ]);
    }
    /**
     * Permet d'afficher la page d'une réservation
     *
     * @Route("/booking/{id}", name="booking_show")
     * @param Booking $booking
     * @return Response
     */
    public function show(Booking $booking){
        return $this->render('booking/show.html.twig', [
            'booking' => $booking
        ]);
    }
}
