<?php

namespace App\Repository;

use App\Entity\Section;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Section|null find($id, $lockMode = null, $lockVersion = null)
 * @method Section|null findOneBy(array $criteria, array $orderBy = null)
 * @method Section[]    findAll()
 * @method Section[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Section::class);
    }

    // /**
    //  * @return Section[] Returns an array of Section objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Section
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getSectionByForm($url)  {
        
        $q = $this->createQueryBuilder('section')
        ->join('section.form', 'form')
        ->where("form.url = :url")
        ->orderBy('section.ordre')
        ->setParameter('url', $url);
        

        return  $q->getQuery()->getArrayResult();
    }

    public function getSectionByFormId($id)  {
        
        $q = $this->createQueryBuilder('section')
        ->join('section.form', 'form')
        ->where("form.id = :id")
        ->orderBy('section.ordre')
        ->setParameter('id', $id);
        

        return  $q->getQuery()->getResult();
    }


    public function getSectionsByFormOrder($id, $order)  {
        
        $q = $this->createQueryBuilder('section')
        ->join('section.form', 'form')
        ->where("form.id = :id ")
        ->andWhere("section.ordre > :order")
        ->setParameter('id', $id)
        ->setParameter('order', $order);
        
        return  $q->getQuery()->getResult();
    }
}
