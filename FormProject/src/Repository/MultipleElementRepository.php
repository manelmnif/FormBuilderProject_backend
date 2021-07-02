<?php

namespace App\Repository;

use App\Entity\MultipleElement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MultipleElement|null find($id, $lockMode = null, $lockVersion = null)
 * @method MultipleElement|null findOneBy(array $criteria, array $orderBy = null)
 * @method MultipleElement[]    findAll()
 * @method MultipleElement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MultipleElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MultipleElement::class);
    }

    // /**
    //  * @return MultipleElement[] Returns an array of MultipleElement objects
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
    public function findOneBySomeField($value): ?MultipleElement
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
