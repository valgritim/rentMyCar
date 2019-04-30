<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class StatsService {

    private $manager;

    public function __construct(ObjectManager $manager){
        $this->manager = $manager;
    }

    function getStats()
{
        $users = $this->getUsersCount();
        $adverts = $this->getAdvertsCount();
        $bookings = $this->getBookingsCount();
        $comments = $this->getCommentsCount();

        return compact('users', 'adverts', 'bookings', 'comments');
}
    public function getUsersCount(){
     return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();

    }

    public function getAdvertsCount(){
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Advert a')->getSingleScalarResult();

    }

    public function getBookingsCount(){
        return $this->manager->createQuery('SELECT COUNT(b) FROM App\Entity\Booking b')->getSingleScalarResult();

    }
     public function getCommentsCount(){
         return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();

     }

     public function getAdvertsStats($direction){
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
            FROM App\Entity\Comment c
            JOIN c.advert a
            JOIN a.author u
            GROUP BY a
            ORDER BY note ' . $direction
        )
        ->setMaxResults(5)
        ->getResult();

     }  


}