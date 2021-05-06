<?php

namespace App\Repository;

use App\Entity\ConstraintValidationElement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConstraintValidationElement|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConstraintValidationElement|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConstraintValidationElement[]    findAll()
 * @method ConstraintValidationElement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConstraintValidationElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConstraintValidationElement::class);
    }

    // /**
    //  * @return ConstraintValidationElement[] Returns an array of ConstraintValidationElement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ConstraintValidationElement
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
