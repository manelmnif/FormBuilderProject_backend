<?php

namespace App\Controller;


use App\Repository\FormRepository;
use App\Repository\ElementTypeRepository;
use App\Entity\Section;
use App\Repository\SectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;



class SectionController extends AbstractFOSRestController
{ 


  
    /**
     * @var SectionRepository
     */
    private $sectionRepository;

     /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    
    /**
     * @var ElementTypeRepository
     */
    private $elementTypeRepository;

    

    public function __construct(SectionRepository $sectionRepository, FormRepository $formRepository, EntityManagerInterface $entityManager)
    {
        $this->formRepository = $formRepository;
        $this->sectionRepository = $sectionRepository;
        $this->entityManager = $entityManager;
    }

    
 
  /**
     * @Route(name="createSection", path="/createsection", options={"expose"=true}, methods="POST")
     */
    public function createSection( Request $request, FormRepository $formRepository)
    {
        
        $name = $request->request->get('name');
        $form = $formRepository->findOneBy([
            'url' => $request->request->get('form'),
        ]);
        $order = $request->request->get('order');
      
        $section = new Section();
        $section->setName($name);
        $section->setForm($form);
        $section->setOrdre($order);

        $this->entityManager->persist($section);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => "ok",
           
        ]);
        

    }

     /**
     * @Route(name="deleteSection", path="/deletesection", options={"expose"=true}, methods="POST" )
     */
    public function deleteSection(Request $request, SectionRepository $sectionRepository)
    {
        $section = $request->get('name');
        $section = $sectionRepository->findOneBy([
            'name' => $section,
        ]);

        
        $this->getDoctrine()->getManager()->remove($section);
        $this->getDoctrine()->getManager()->flush();
        
        return new JsonResponse([
            'message' => "ok",
           
        ]);
    }

    /**
     * @Route(name="updateOrder", path="/updateorder", options={"expose"=true}, methods="POST")
     */
    public function updateOrder(Request $request, SectionRepository $sectionRepository)
    {
        $name = $request->get('name');
        $order = $request->get('order');
       
        $section = $sectionRepository->findOneBy([
            'name' => $name,
        ]);
       
        $section->setOrdre($order);
        
        $this->entityManager->persist($section);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => "ok",
           
        ]);
        

    }


   

   
 
 
    }


    



  
    
  
