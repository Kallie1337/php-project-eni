<?php

namespace App\Repository;

use App\Entity\Location;
use App\Entity\Trip;
use App\Entity\User;
use App\Form\LocationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    /**
     * @param string|null $champ
     * @param string|null $date_begin
     * @param int|null $location_id
     * @return mixed
     */
    public function recherche(?string $champ, ?string $date_begin, ?string $location_id)
    {
        $query = $this->createQueryBuilder('t')
            ->where('t.name LIKE :champ')
            ->andWhere('t.beginDateTime LIKE :date_begin')
            ->andWhere('t.location_id = decision')
            ->setParameter('champ', "%" . $champ . "%")
            ->setParameter('date_begin', "%" . $date_begin . "%")
            ->setParameter('decision', "%" . $location_id . "%");


        return $query->getQuery()->getResult();

    }


    // /**
    //  * @return Trip[] Returns an array of Trip objects
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
    public function findOneBySomeField($value): ?Trip
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
