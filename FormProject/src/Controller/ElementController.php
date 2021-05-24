<?php

namespace App\Controller;

use App\Entity\ConstraintValidationElement;
use App\Repository\ElementTypeRepository;
use App\Entity\Element;
use App\Repository\ConstraintValidationRepository;
use App\Repository\ElementRepository;
use App\Repository\SectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;




class ElementController extends AbstractFOSRestController
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



    public function __construct( SectionRepository $sectionRepository, EntityManagerInterface $entityManager)
    {
        
        $this->sectionRepository = $sectionRepository;
        $this->entityManager = $entityManager;
    }



    /**
     * @Route(name="createElement", path="/createelement", options={"expose"=true}, methods="POST")
     */
    public function createElement(Request $request, SectionRepository $sectionRepository, ElementTypeRepository $elementTypeRepository)
    {

        $name = $request->request->get('name');
        $section = $sectionRepository->findOneBy([
            'id' => $request->request->get('section'),
        ]);
        $elementType = $elementTypeRepository->findOneBy([
            'id' => $request->request->get('elementType'),
        ]);
        $order = $request->request->get('order');
        $label = $request->request->get('label');
        $placeholder = $request->request->get('placeholder');
        $class = $request->request->get('class');
     


        $element = new Element();
        $element->setName($name);
        $element->setSection($section);
        $element->setElementType($elementType);
        $element->setOrdre($order);
        $element->setLabel($label);
        $element->setPlaceholder($placeholder);
        $element->setClasse($class);

        
        $this->entityManager->persist($element);
        $this->entityManager->flush();

        

        return new JsonResponse([
            'id' => $element->getId(),
            'message' => "ok",
           

        ]);
    }


    /**
     * @Route(name="updateSettingsElement", path="/updatesettingselement", options={"expose"=true}, methods="POST")
     */
    public function updateSettingsElement(Request $request, ElementRepository $elementRepository ,ElementTypeRepository $elementTypeRepository, ConstraintValidationRepository $constraintValidationRepository)
    {
        $element = $request->get('elementField');
            $element = $elementRepository->findOneBy([
                'name' => $element,
            ]);
        $label = $request->get('label');
        $placeholder = $request->get('placeholder');

        $element->setLabel($label);
        $element->setPlaceholder($placeholder);

        $this->entityManager->persist($element);
        $this->entityManager->flush();

        $elementType = $elementTypeRepository->findOneBy([
            'id' => $request->request->get('elementType'),
        ]);
        $constraints = $constraintValidationRepository->findConstraintByElementTypeTest($elementType);
        $value = $request->request->get('value');
        dump($value);
       
        $i = 0;
         foreach ($constraints as $constraint   ) 
           {
               if($value[$i] != null){
            $constraintValidationElement = new ConstraintValidationElement();
            $constraintValidationElement->setElement($element);
            $constraintValidationElement->setConstraintValidation($constraint);
            $constraintValidationElement->setValue($value[$i]);
            $constraintValidationElement->setMessage('test');

            $this->entityManager->persist($constraintValidationElement);
            $this->entityManager->flush();

            $i++;

        }
           }



        return new JsonResponse([
            'message' => "settings updated",

        ]);


    }

   
}
