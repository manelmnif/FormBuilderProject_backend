<?php

namespace App\Repository;

use App\Entity\ElementDataMultiple;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ElementDataMultiple|null find($id, $lockMode = null, $lockVersion = null)
 * @method ElementDataMultiple|null findOneBy(array $criteria, array $orderBy = null)
 * @method ElementDataMultiple[]    findAll()
 * @method ElementDataMultiple[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ElementDataMultipleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ElementDataMultiple::class);
    }

    // /**
    //  * @return ElementDataMultiple[] Returns an array of ElementDataMultiple objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ElementDataMultiple
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
   


}
