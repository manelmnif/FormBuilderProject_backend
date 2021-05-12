<?php

namespace App\Controller;

use App\Repository\SectionRepository;
use App\Repository\ElementTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;




class ElementTypeController extends AbstractFOSRestController
{ 


  /**
     * @var SectionRepository
     */
    private $sectionRepository;

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

    // getElementType &  getSectionByForm
    /**
     * @Route("/form/{url}", name="indexArray")
     */
    public function getElementType(ElementTypeRepository $elementTypeRepository, SectionRepository $sectionRepository, string $url )
    {
        $elementTypes = $elementTypeRepository->findAll();
       
        $sections = $sectionRepository->getSectionByForm($url);
        
        return $this->render('index.html.twig',array
        ('sections' => $sections,
        'elementTypes' => $elementTypes));
        

    }



  
    
  
}
