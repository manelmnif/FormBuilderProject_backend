<?php

namespace App\Repository;

use App\Entity\Form;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Form|null find($id, $lockMode = null, $lockVersion = null)
 * @method Form|null findOneBy(array $criteria, array $orderBy = null)
 * @method Form[]    findAll()
 * @method Form[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Form::class);
    }


    public function getForms($id)  {
        
        $q = $this->createQueryBuilder('f')
        ->join('f.user', 'user')
        ->where("user.id = :id")
        ->andWhere("f.status = 0")
        ->orderBy('f.id' ,'DESC')
        ->setParameter('id', $id);
        

        return  $q->getQuery()->getResult();
    }

    public function getPublishedForms($id)  {
        
        $q = $this->createQueryBuilder('f')
        ->join('f.user', 'user')
        ->where("user.id = :id")
        ->andWhere("f.status = 1")
        ->orderBy('f.id' ,'DESC')
        ->setParameter('id', $id);
        

        return  $q->getQuery()->getResult();
    }

    public function search($item)  {
        
        $q = $this->createQueryBuilder('form')
        ->where("form.name LIKE :name")
        ->setParameter('name', '%'.$item.'%')
        ->getQuery()
        ->execute();

       
    }




    // /**
    //  * @return Form[] Returns an array of Form objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Form
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
