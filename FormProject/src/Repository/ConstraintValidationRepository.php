<?php

namespace App\Repository;

use App\Entity\ConstraintValidation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConstraintValidation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConstraintValidation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConstraintValidation[]    findAll()
 * @method ConstraintValidation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConstraintValidationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConstraintValidation::class);
    }

  /*  public function findParentAndChildsList($idsUser){
        $query = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.id in (:idUser)')
            ->orWhere('u.creator in (:idUser)')
            ->setParameter('idUser',$idsUser);
        ;
        return $query->getQuery()->getResult();
    }*/

    public function findConstraintByElementType($type)  {
        
        $q = $this->createQueryBuilder('validation')
        ->select('validation')
        ->join('validation.elementTypes', 'elementType')
        ->where("elementType.id = :type")
        ->setParameter('type', $type);
        

        return  $q->getQuery()->getArrayResult();
    }

    public function findConstraintByElementTypeName($type)  {
        
        $q = $this->createQueryBuilder('validation')
        ->select('validation')
        ->join('validation.elementTypes', 'elementType')
        ->where("elementType.type = :type")
        ->setParameter('type', $type);
        

        return  $q->getQuery()->getArrayResult();
    }

    public function findConstraintByElementTypeTest($type)  {
        
        $q = $this->createQueryBuilder('validation', 'validation.id')
        ->select('validation')
        ->join('validation.elementTypes', 'elementType')
        ->where("elementType.id = :type")
        ->setParameter('type', $type);
        

        return  $q->getQuery()->getResult();
    }

    // /**
    //  * @return ConstraintValidation[] Returns an array of ConstraintValidation objects
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
    public function findOneBySomeField($value): ?ConstraintValidation
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
