<?php

namespace App\Repository;

use App\Entity\TripUserLove;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TripUserLove|null find($id, $lockMode = null, $lockVersion = null)
 * @method TripUserLove|null findOneBy(array $criteria, array $orderBy = null)
 * @method TripUserLove[]    findAll()
 * @method TripUserLove[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripUserLoveRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TripUserLove::class);
    }

    // /**
    //  * @return TripUserLove[] Returns an array of TripUserLove objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TripUserLove
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
