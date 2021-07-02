<?php

namespace App\Controller;


use App\Repository\ElementTypeRepository;
use App\Entity\MultipleElement;
use App\Repository\ElementRepository;
use App\Repository\MultipleElementRepository;
use App\Repository\SectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;




class MultipleElementController extends AbstractFOSRestController
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



    public function __construct(SectionRepository $sectionRepository, EntityManagerInterface $entityManager)
    {

        $this->sectionRepository = $sectionRepository;
        $this->entityManager = $entityManager;
    }




     /**
     * @Route(name="createMultipleElement", path="/createmultipleelement", options={"expose"=true}, methods="POST")
     */
    public function createMultipleElement(Request $request, ElementRepository $elementRepository, MultipleElementRepository $multipleElementRepository)
    {

        $values = $request->request->get('value');
        $elementField = $request->request->get('elementField');
        $element = $elementRepository->findOneBy([
            'name' => $elementField,
        ]);
        $dropdownFields = $request->request->get('dropdownField');
        
        $multipleElements = $multipleElementRepository->findAll();

        foreach ($multipleElements as $multipleElement) {
            
            if ( $multipleElement->getElement() == $element) {
                $this->getDoctrine()->getManager()->remove($multipleElement);
        
        }
    }
    if($dropdownFields != null){
        foreach (array_combine($values, $dropdownFields) as $value => $dropdownField)
            {
                $multipleElement = new MultipleElement();
                $multipleElement->setElement($element);
                $multipleElement->setName($dropdownField);
                $multipleElement->setValue($value);
                $this->entityManager->persist($multipleElement);
            }
        }
 
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => "settings updated",

        ]);
    }

    
    /**
     * @Route(name="deleteMultipleElement", path="/deletemultipleelement", options={"expose"=true}, methods="POST")
     */
    public function deleteMultipleElement(Request $request, MultipleElementRepository $multipleElementRepository)
    {
        

        $dropdownField = $request->request->get('dropdownField');

        $multipleElement = $multipleElementRepository->findOneBy([
            'name' => $dropdownField,
        ]);
        $multipleElements =$multipleElementRepository->findAll();
        $exist = "false";
        foreach($multipleElements as $multipleElmt){
            if($multipleElmt == $multipleElement){
                $exist ="true";
                break;
            }
        }
        
        if($exist=='true'){
        $this->getDoctrine()->getManager()->remove($multipleElement);
        $this->getDoctrine()->getManager()->flush();
    }
        return new JsonResponse([
            'message' => "multiple element removed",
        ]);
    }


   

      
}
