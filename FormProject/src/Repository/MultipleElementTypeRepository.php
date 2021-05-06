<?php

namespace App\Repository;

use App\Entity\MultipleElementType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MultipleElementType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MultipleElementType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MultipleElementType[]    findAll()
 * @method MultipleElementType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MultipleElementTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MultipleElementType::class);
    }

    // /**
    //  * @return MultipleElementType[] Returns an array of MultipleElementType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MultipleElementType
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
