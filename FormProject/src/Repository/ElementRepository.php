<?php

namespace App\Repository;

use App\Entity\Element;
use App\Entity\Model;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Element|null find($id, $lockMode = null, $lockVersion = null)
 * @method Element|null findOneBy(array $criteria, array $orderBy = null)
 * @method Element[]    findAll()
 * @method Element[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Element::class);
    }

    // /**
    //  * @return Element[] Returns an array of Element objects
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
    public function findOneBySomeField($value): ?Element
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    // appel delete 
    public function getElementBySectionOrder($id, $order)  {
        
        $q = $this->createQueryBuilder('element')
        ->join('element.section', 'section')
        ->where("section.id = :id ")
        ->andWhere("element.ordre > :order")
        ->setParameter('id', $id)
        ->setParameter('order', $order);
        
        return  $q->getQuery()->getResult();
    }

     // appel create
     public function getElementsBySectionOrder($id, $order)  {
        
        $q = $this->createQueryBuilder('element')
        ->join('element.section', 'section')
        ->where("section.id = :id ")
        ->andWhere("element.ordre >= :order")
        ->setParameter('id', $id)
        ->setParameter('order', $order);
        
        return  $q->getQuery()->getResult();
    }

    public function getElementBySectionId($id)  {
        
        $q = $this->createQueryBuilder('element')
        ->join('element.section', 'section')
        ->where("section.id = :id")
        ->orderBy('element.ordre')
        ->setParameter('id', $id);
        

        return  $q->getQuery()->getResult();
    }

    public function getElementsBySection($idSection)  {
        
        $q = $this->createQueryBuilder('element')
        ->join('element.section', 'section')
        ->where("section.id = :idSection")
        ->orderBy('element.ordre')
        ->setParameter('idSection', $idSection);
        

        return  $q->getQuery()->getArrayResult();
    }

    

    

   
}
