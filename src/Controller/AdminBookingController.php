<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings", name="admin_bookings")
     */
    public function index(BookingRepository $repo)
    {
        
        return $this->render('admin/booking/index.html.twig', [
                'bookings' => $repo->findAll()
        ]);
    }

    /**
     * Permet de modifier une réservation
     * 
     * @Route("/admin/bookings/{id}/edit", name="admin_bookings_edit")
     *
     * @return Response
     */
    public function edit(Booking $booking, Request $request, ObjectManager $manager){

        $form = $this->createForm(AdminBookingType::class, $booking);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //$booking->setAmount($booking->getAdvert()->getPrice() * $booking->getDuration()); Voir function prePersist dans booking->preUpdate:le nouveau montant se calcule tout seul
            $booking->setAmount(0);
            $manager->persist($booking);
            $manager->flush();

            $this->addFlash('success', "La réservation {$booking->getId()} a bien été modifiée !");

            return $this->redirectToRoute('admin_bookings');
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }

    /**
     * Permet de supprimer un réservation
     * 
     * @Route("/admin/bookings/{id}/delete", name="admin_bookings_delete")
     *
     * @param Booking $booking
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Booking $booking, ObjectManager $manager){
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash('success', "La réservation a bien été supprimée !");
        return $this->redirectToRoute('admin_bookings');
    }
}
