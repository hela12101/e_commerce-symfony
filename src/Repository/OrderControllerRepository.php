<?php

namespace App\Repository;

use App\Entity\OrderController;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderController|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderController|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderController[]    findAll()
 * @method OrderController[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderControllerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderController::class);
    }

    // /**
    //  * @return OrderController[] Returns an array of OrderController objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderController
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
