<?php

namespace App\Controller;


use App\Repository\ElementTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;




class ElementTypeController extends AbstractFOSRestController
{ 


  
    /**
     * @var ElementTypeRepository
     */
    private $elementTypeRepository;

     /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    

    public function __construct(ElementTypeRepository $elementTypeRepository, EntityManagerInterface $entityManager)
    {
        $this->elementTypeRepository = $elementTypeRepository;
        $this->entityManager = $entityManager;
    }

    
    public function getElementType(ElementTypeRepository $elementTypeRepository)
    {
        $elementTypes = $elementTypeRepository->findAll();

        return $this->render('index.html.twig', array
        ('elementTypes' => $elementTypes));

    }



  
    
  
}
