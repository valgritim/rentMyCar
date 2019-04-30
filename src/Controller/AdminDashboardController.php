<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use App\Service\StatsService;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(ObjectManager $manager, StatsService $statsService)
    {
        $stats = $statsService->getStats();
        
        $bestAdverts = $statsService->getAdvertsStats('DESC');

        $worstAdverts = $statsService->getAdvertsStats('ASC');
        

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'bestAdverts' => $bestAdverts,
            'worstAdverts' => $worstAdverts
            //ou fonction php: compact('users', 'adverts', 'bookings', 'comments') va créer un tableau qui a les clés indiquées et les variables du même nom
        ]);
    }
}
