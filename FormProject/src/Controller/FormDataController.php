<?php

namespace App\Controller;

use App\Entity\ElementData;
use App\Repository\FormRepository;
use App\Repository\SectionRepository;
use App\Entity\Form;
use App\Entity\FormData;
use App\Repository\ElementDataRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ElementRepository;
use App\Repository\FormDataRepository;
use App\Repository\MultipleElementRepository;

class FormDataController extends AbstractFOSRestController
{

  /**
   * @var EntityManagerInterface
   */
  private $entityManager;

  public function __construct(SectionRepository $sectionRepository, EntityManagerInterface $entityManager)
  {

    $this->sectionRepository = $sectionRepository;
    $this->entityManager = $entityManager;
  }
  //page contain form created to add data

  /**
   * @Route(name="createData", path="/data/create/{url}", options={"expose"=true}, methods="Get")
   */
  public function createData(Form $form, ElementRepository $elementRepository, SectionRepository $sectionRepository, string $url,  FormRepository $formRepository)
  {

    $form = $formRepository->findOneBy(array('url' => $url));
    $sections = $sectionRepository->getSectionByForm($url);
    $elements = $elementRepository->findAll();

    return $this->render('formulaire/DataForm.html.twig', array(
      'sections' => $sections,
      'form' => $form,
      'elements' => $elements
    ));
  }

  /**
   * @Route(name="setElementsValues", path="/setElementsValues/{url}", options={"expose"=true}, methods="post")
   */
  public function setElementsValues(Request $request, ElementRepository $elementRepository, FormRepository $formRepository, SectionRepository $sectionRepository, string $url, MultipleElementRepository $multipleElementRepository)
  {
    $e = [];

    $sections = $sectionRepository->getSectionByForm($url);
    foreach ($sections as $section) {
      $elements = $elementRepository->getElementBySection($section->getId());
      $e = array_merge($e, $elements);
    }

    $formData = new FormData();
    $form = $formRepository->findOneBy(array('url' => $url));
    $formData->setName('null');
    $formData->setRefForm($form->getId());
    $form->setNumber($form->getNumber()+1);
    $this->entityManager->persist($formData);
    $this->entityManager->persist($form);
    $this->entityManager->flush();
    $formData->setName($form->getName() . $formData->getId());


    if ($request->getMethod() == Request::METHOD_POST) {
      foreach ($e as $element) {
        //get input value request by name html
        $value = $request->request->get($element->getId());
        $valuesMultiple = $multipleElementRepository->findBy(array('element' => $element));
     
        if ($element->getElementType()->getType() == "Multiple Checkbox") {
          //get input value request by name html
   
          foreach ($valuesMultiple as $valueMultiple) {
            $values = $request->request->get($valueMultiple->getId());
            $elementData = new ElementData();
            //$elementData->setValue($value);
           
            if ($values == null){
              $elementData->setValue("");
            }
            else{
              $elementData->setValue($valueMultiple->getValue());
            }
            $elementData->setElement($element);
            $elementData->setFormData($formData);
            $this->entityManager->persist($elementData);
            $this->entityManager->flush();
          }
         
        }
        
        else if ($element->getElementType()->getType() == "Checkbox") {
          $elementData = new ElementData();
          $elementData->setElement($element);
          $elementData->setFormData($formData);
          if ($value == null) {
            $elementData->setValue('Non');
          } else {
            $elementData->setValue('Oui');
          }
        } 
       
       /* else if ($element->getElementType()->getType() == "Dropdown List") {
          $elementData = new ElementData();
          $elementData->setElement($element);
          $elementData->setFormData($formData);
          $valueId= $multipleElementRepository->findIdByMultipleValue($element, $value);
          $elementData->setValue($valueId[0]->getId());
    
        } */

        else if ($element->getElementType()->getType() == "Dropdown List") {
          $value = $request->request->get($element->getId());
          /*dump($value);
          die();*/
          $elementData = new ElementData();
          $elementData->setElement($element);
          $elementData->setFormData($formData);
          $valueId= $multipleElementRepository->findIdByMultipleValue($element, $value);
          $elementData->setValue($valueId[0]->getId());
    
        }
    
      
        else {
          $elementData = new ElementData();
          
          $elementData->setValue($value);
          $elementData->setElement($element);
          $elementData->setFormData($formData);
        }
        $this->entityManager->persist($elementData);
      }

      $this->entityManager->flush();
    }

    $this->addFlash('success', 'La saisie des données a été crée avec succès!');
    return $this->redirectToRoute('getData', ['url' => $url]);
  }

  /**
   * @Route(name="getData", path="/data/list/{url}", options={"expose"=true}, methods="Get")
   */
  public function getData(Form $form, ElementRepository $elementRepository, SectionRepository $sectionRepository, string $url, FormRepository $formRepository, ElementDataRepository $elementDataRepository, FormDataRepository $formDataRepository, MultipleElementRepository $multipleElementRepository)
  {
   /* if ($form->getStatus() != '1') {
      throw $this->createAccessDeniedException();
    }*/
    $form = $formRepository->findOneBy(array('url' => $url));
    $e = [];

    $sections = $sectionRepository->getSectionByForm($url);
    foreach ($sections as $section) {
      $elements = $elementRepository->getElementBySection($section->getId());
      $e = array_merge($e, $elements);
    }
    $dataElements = $elementDataRepository->findAll();
    $dataForms = $formDataRepository->getFormsDataByForm($form->getId());

  
    $multipleElements = $multipleElementRepository->findAll();
 /*$multipleValues=[];
       foreach($dataElements as $dataElement){
         $value = $dataElement->getValue();
         $multipleValue = $multipleElementRepository->findOneBy(array('id' => $value));
         array_push($multipleValues, $multipleValue);
       }*/

    return $this->render('formulaire/getData.html.twig', array(
      'elements' => $e,
      'form' => $form,
      'dataElements' => $dataElements,
      'dataForms' => $dataForms,
      'multipleElements' => $multipleElements

    ));
  }

  /**
   * @Route(name="deleteFormData", path="/deleteformdata/{id}")
   */
  public function deleteFormData(int $id, FormDataRepository $formDataRepository, FormRepository $formRepository)
  {

    $formData = $formDataRepository->findOneBy(array('id' => $id));
    $idForm = $formData->getRefForm();
    $url = $formRepository->findOneBy(array('id' => $idForm))->getUrl();
    $this->getDoctrine()->getManager()->remove($formData);
    $this->entityManager->flush();

    $this->addFlash('success', 'La saisie des données a été supprimée avec succès!');
    return $this->redirectToRoute('getData', ['url' => $url]);
  }


  //redirection to edit form
  /**
   * @Route(name="editData", path="/data/edit/{url}/{idFormData}", options={"expose"=true}, methods="Get")
   */
  public function editData(Form $form, ElementRepository $elementRepository, SectionRepository $sectionRepository, string $idFormData, string $url,  FormRepository $formRepository)
  {

    $form = $formRepository->findOneBy(array('url' => $url));
    //$formData = $formRepository->findOneBy(array('idFormData' => $id));
    $sections = $sectionRepository->getSectionByForm($url);
    $elements = $elementRepository->findAll();

    return $this->render('formulaire/editFormData.html.twig', array(
      'sections' => $sections,
      'form' => $form,
      'elements' => $elements

    ));
  }


 

  /**
   * @Route(name="editElementsValues", path="/editElementsValues/{url}/{idFormData}", options={"expose"=true}, methods="post")
   */
  public function editElementsValues(Request $request, ElementRepository $elementRepository, MultipleElementRepository $multipleElementRepository, SectionRepository $sectionRepository, string $url, string $idFormData, FormDataRepository $formDataRepository, ElementDataRepository $elementDataRepository)
  {
    $e = [];

    $sections = $sectionRepository->getSectionByForm($url);
    foreach ($sections as $section) {
      $elements = $elementRepository->getElementBySection($section->getId());
      $e = array_merge($e, $elements);
    }

    $formData = $formDataRepository->findOneBy(array('id' => $idFormData));




    if ($request->getMethod() == Request::METHOD_POST) {
      $elementsData = $elementDataRepository->findAll();
      foreach ($elementsData as $elementData) {

        if ($elementData->getFormData() == $formData) {
          $this->getDoctrine()->getManager()->remove($elementData);
        }
      }
      foreach ($e as $element) {

        //get input value request by name html
        $value = $request->request->get($element->getId());
        $valuesMultiple = $multipleElementRepository->findBy(array('element' => $element));
        $elementData = new ElementData();
        if ($element->getElementType()->getType() == "Multiple Checkbox") {
          //get input value request by name html
          
          foreach ($valuesMultiple as $valueMultiple) {
            $elementData = new ElementData();
            $values = $request->request->get($valueMultiple->getId());
           
            if ($values == null){
              $elementData->setValue("");
            }
            else{
              $elementData->setValue($valueMultiple->getValue());
            }
            $elementData->setElement($element);
            $elementData->setFormData($formData);
            $this->entityManager->persist($elementData);
            $this->entityManager->flush();
          }
         
        }
        
        elseif ($element->getElementType()->getType() == "Checkbox") {
          $elementData->setElement($element);
          $elementData->setFormData($formData);
          if ($value == null) {
            $elementData->setValue('Non');
          } else {
            $elementData->setValue('Oui');
          }
        }
        elseif ($element->getElementType()->getType() == "Dropdown List") {
          
          $elementData->setElement($element);
          $elementData->setFormData($formData);
          $valueId= $multipleElementRepository->findIdByMultipleValue($element, $value);
          $elementData->setValue($valueId[0]->getId());
        
        }
       /* elseif ($element->getElementType()->getType() == "Dropdown List") {
          
          foreach ($valuesMultiple as $valueMultiple) {
            $elementData = new ElementData();
            $values = $request->request->get($valueMultiple->getId());
           
            if ($values == null){
              $elementData->setValue("");
            }
            else{
              $elementData->setValue($valueMultiple->getValue());
            }
            $elementData->setElement($element);
            $elementData->setFormData($formData);
            $this->entityManager->persist($elementData);
            $this->entityManager->flush();
          }
        
        }*/
        else {
          $elementData->setValue($value);
          $elementData->setElement($element);
          $elementData->setFormData($formData);
        }
        $this->entityManager->persist($elementData);
      }

      $this->entityManager->flush();
    }

    $this->addFlash('success', 'La saisie des données a été modifiée avec succès!');
    return $this->redirectToRoute('getData', ['url' => $url]);
  }
}
