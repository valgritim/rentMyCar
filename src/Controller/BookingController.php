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
                

            $manager->persist($booking);
            $manager->flush();

            return $this->redirectToRoute('booking_success', ['id' => $booking->getId()]);

        }
       

        return $this->render('booking/book.html.twig', [
            'advert' => $advert,
            'form' => $form->createView()
        ]);
    }
}
