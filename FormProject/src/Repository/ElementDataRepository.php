<?php

namespace App\Repository;

use App\Entity\ElementData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ElementData|null find($id, $lockMode = null, $lockVersion = null)
 * @method ElementData|null findOneBy(array $criteria, array $orderBy = null)
 * @method ElementData[]    findAll()
 * @method ElementData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ElementDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ElementData::class);
    }

    // /**
    //  * @return ElementData[] Returns an array of ElementData objects
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
    public function findOneBySomeField($value): ?ElementData
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
