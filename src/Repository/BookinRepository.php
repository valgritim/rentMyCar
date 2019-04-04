<?php

namespace App\Repository;

use App\Entity\Bookin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Bookin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bookin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bookin[]    findAll()
 * @method Bookin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookinRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Bookin::class);
    }

    // /**
    //  * @return Bookin[] Returns an array of Bookin objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bookin
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
