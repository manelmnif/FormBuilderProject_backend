<?php

namespace App\Controller;

use App\Entity\ConstraintValidationElement;
use App\Repository\ElementTypeRepository;
use App\Entity\Element;
use App\Repository\ConstraintValidationElementRepository;
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



    public function __construct(SectionRepository $sectionRepository, EntityManagerInterface $entityManager)
    {

        $this->sectionRepository = $sectionRepository;
        $this->entityManager = $entityManager;
    }



    /**
     * @Route(name="createElement", path="/createelement", options={"expose"=true}, methods="POST")
     */
    public function createElement(Request $request, SectionRepository $sectionRepository, ElementTypeRepository $elementTypeRepository, ElementRepository $elementRepository, constraintValidationRepository $constraintValidationRepository)
    {
        
        $elementExist = $request->request->get('elementExist');
        $name = $request->request->get('name');
        $section = $sectionRepository->findOneBy([
            'name' => $request->request->get('section'),
        ]);
        $elementType = $elementTypeRepository->findOneBy([
            'id' => $request->request->get('elementType'),
        ]);
        $order = $request->request->get('order');
        $label = $request->request->get('label');
        $placeholder = $request->request->get('placeholder');
        $class = $request->request->get('class');

       
        $elements = $elementRepository->findAll();
        $elementExist = 'false';
        //verifier si elmt existe
        foreach ($elements as $e) {
            if ($e->getName() == $name) {
                $elementExist = 'true';
                break;
            }
        }

        if ($elementExist == 'false') {


            $element = new Element();
            $element->setName($name);
            $element->setSection($section);
            $element->setElementType($elementType);
            $element->setOrdre($order);
            $element->setLabel($label);
            $element->setPlaceholder($placeholder);
            $element->setClasse($class);

            $this->entityManager->persist($element);

            
            $constraints = $constraintValidationRepository->findConstraintByElementTypeTest($elementType);
            foreach($constraints as $constraint){
          if($constraint->getId()!=4){
         
                $constraintValidationElement = new ConstraintValidationElement();
                $constraintValidationElement->setElement($element);
                $constraintValidationElement->setConstraintValidation($constraint);
                $constraintValidationElement->setValue("");
                $constraintValidationElement->setMessage("Test");
                $this->entityManager->persist($constraintValidationElement);
            }
            }

            $sectionId = $element->getSection()->getId();
            $elements = $elementRepository->getElementsBySectionOrder($sectionId, $order);


            foreach ($elements as $elmt) {
                $elmt->setOrdre($elmt->getOrdre() + 1);
                $this->entityManager->persist($elmt);
            }
            $this->entityManager->flush();
        } 
        //if elmt exist traitement de l'ordre 
        else {
            $finalOrderElementSort = $request->get('finalOrderElementSort');
            $initialOrderElementSort = $request->get('initialOrderElementSort');
            $sortedElement = $request->get('sortedElement');
            $sortedElement = $elementRepository->findOneBy([
                'name' => $sortedElement,
            ]);

            $sectionId = $sortedElement->getSection()->getId();
            $elements = $elementRepository->getElementBySectionId($sectionId);

            // top
            if ($initialOrderElementSort > $finalOrderElementSort) {
                foreach ($elements as $element) {

                    if ($element->getOrdre() < $initialOrderElementSort && $element->getOrdre() >= $finalOrderElementSort) {
                        $element->setOrdre($element->getOrdre() + 1);
                        $this->entityManager->persist($element);
                    }
                }
            }
            //down
            else {
                foreach ($elements as $element) {
                    if ($element->getOrdre() > $initialOrderElementSort && $element->getOrdre() <= $finalOrderElementSort) {
                        $element->setOrdre($element->getOrdre() - 1);
                        $this->entityManager->persist($element);
                    }
                }
            }

            $sortedElement->setOrdre($finalOrderElementSort);

            
           
            $this->entityManager->persist($sortedElement);
            $this->getDoctrine()->getManager()->flush();
          
        }

        return new JsonResponse([
            'message' => "ok",
        ]);
    }


   

       /**
     * @Route(name="updateSettingsElement", path="/updatesettingselement", options={"expose"=true}, methods="POST")
     */
    public function updateSettingsElement(Request $request, ElementRepository $elementRepository, ElementTypeRepository $elementTypeRepository, constraintValidationRepository $constraintValidationRepository, ConstraintValidationElementRepository $constraintValidationElementRepository)
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
       // $this->entityManager->flush();

        $elementType = $elementTypeRepository->findOneBy([
            'id' => $request->request->get('elementType'),
        ]);
        $constraints = $constraintValidationRepository->findConstraintByElementTypeTest($elementType);
        $values = $request->request->get('value');



        $constraintValidationElements = $constraintValidationElementRepository->findAll();

        foreach ($constraintValidationElements as $constraintValidationElement) {
            if ($constraintValidationElement->getElement() == $element) {
                $this->getDoctrine()->getManager()->remove($constraintValidationElement);
            }
        }


        $exist = false;
  if($values != []){
        foreach ($values as $value) {
            if (array_key_exists($value['name'], $constraints)) {

                if ($value['name'] == 4)
                    $exist = true;

                $constraintValidationElement = new ConstraintValidationElement();
                $constraintValidationElement->setElement($element);
                $constraintValidationElement->setConstraintValidation($constraints[$value['name']]);
                $constraintValidationElement->setValue($value['value']);
                $constraintValidationElement->setMessage('test');

                

                $this->entityManager->persist($constraintValidationElement);
            }
        }
    }
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => "settings updated",

        ]);
    }

    // update order after deleting element or sorting element 

    /**
     * @Route(name="updateElementOrder", path="/updateorderelement", options={"expose"=true}, methods="POST")
     */
    public function updateOrder(Request $request, ElementRepository $elementRepository, SectionRepository $sectionRepository, ElementTypeRepository $elementTypeRepository)
    {
        $isDelete = $request->get('isDelete');


        if ($isDelete == "true") {
            $order = $request->get('orderElement');
            $element = $request->get('name');
            $element = $elementRepository->findOneBy([
                'name' => $element,
            ]);


            $this->getDoctrine()->getManager()->remove($element);
            $sectionId = $element->getSection()->getId();
            $elements = $elementRepository->getElementBySectionOrder($sectionId, $order);


            foreach ($elements as $element) {
                $element->setOrdre($element->getOrdre() - 1);
                $this->entityManager->persist($element);
            }

            $this->getDoctrine()->getManager()->flush();
        }
      

        return new JsonResponse([
            'message' => "ok",

        ]);
    }

    /**
     * @Route(name="getElements", path="/getElements", options={"expose"=true}, methods="GET")
     */
    public function getElements( ElementRepository $elementRepository)
    {
    
     
        $elements = $elementRepository->getElements();

        $json = json_encode(array(
            'elements' => $elements,
          
        ));

        $response = new JsonResponse();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($json);
        return $response;
    

    
    }
}
