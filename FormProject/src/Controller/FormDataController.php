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
use Symfony\Component\HttpFoundation\JsonResponse;

use function PHPSTORM_META\elementType;

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
    public function setElementsValues(Request $request, ElementRepository $elementRepository, FormRepository $formRepository, SectionRepository $sectionRepository, string $url)
    {
      $e = [];
      
      $sections = $sectionRepository->getSectionByForm($url);
      foreach ($sections as $section){
        $elements = $elementRepository->getElementBySection($section->getId());
        $e = array_merge($e, $elements);  
      }
       
      $formData = new FormData();
      $form = $formRepository->findOneBy(array('url' => $url));
      $formData->setName('null');
      $formData->setRefForm($form->getId());
      $this->entityManager->persist($formData);
      $this->entityManager->flush();
      $formData->setName($form->getName().$formData->getId());
      
      
      if ($request->getMethod() == Request::METHOD_POST){
          foreach($e as $element){
            //get input value request by name html
        $value = $request->request->get($element->getId());
        
        $elementData = new ElementData();
        $elementData->setValue($value);
        $elementData->setElement($element);
        $elementData->setFormData($formData);
        $this->entityManager->persist($elementData);
        }
        
        $this->entityManager->flush();
    }

        $this->addFlash('success', 'données enregistrés');
        return $this->redirectToRoute('getData',['url' => $url]);
      
    }

     /**
     * @Route(name="getData", path="/data/list/{url}", options={"expose"=true}, methods="Get")
     */
    public function getData(Form $form, ElementRepository $elementRepository, SectionRepository $sectionRepository, string $url, FormRepository $formRepository, ElementDataRepository $elementDataRepository, FormDataRepository $formDataRepository)
    {
      if ($form->getStatus() != '1') {
        throw $this->createAccessDeniedException();
    }
      $form = $formRepository->findOneBy(array('url' => $url));
      $e = [];
      
      $sections = $sectionRepository->getSectionByForm($url);
      foreach ($sections as $section){
        $elements = $elementRepository->getElementBySection($section->getId());
        $e = array_merge($e, $elements);  
      }
      $dataElements = $elementDataRepository->findAll();
      $dataForms = $formDataRepository->getFormsDataByForm($form->getId());


      return $this->render('formulaire/getData.html.twig', array(
          'elements' => $e,
          'form' => $form,
          'dataElements' => $dataElements,
          'dataForms' => $dataForms
        
      ));
      
    }

       /**
     * @Route(name="deleteFormData", path="/deleteformdata/{id}")
     */
    public function deleteFormData(int $id, FormDataRepository $formDataRepository, FormRepository $formRepository)
    {
    
       $formData = $formDataRepository->findOneBy(array('id' => $id));
       $idForm= $formData->getRefForm();
       $url= $formRepository->findOneBy(array('id' => $idForm))->getUrl();
       $this->getDoctrine()->getManager()->remove($formData);
       $this->entityManager->flush();

       $this->addFlash('success', 'Données supprimées');
       return $this->redirectToRoute('getData',['url' => $url]);
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
    public function editElementsValues(Request $request, ElementRepository $elementRepository, FormRepository $formRepository, SectionRepository $sectionRepository, string $url, string $idFormData, FormDataRepository $formDataRepository, ElementDataRepository $elementDataRepository)
    {
      $e = [];
      
      $sections = $sectionRepository->getSectionByForm($url);
      foreach ($sections as $section){
        $elements = $elementRepository->getElementBySection($section->getId());
        $e = array_merge($e, $elements);  
      }
       
      $formData = $formDataRepository->findOneBy(array('id' => $idFormData));
      
      
      
      
      if ($request->getMethod() == Request::METHOD_POST){
        $elementsData = $elementDataRepository->findAll();
        foreach($elementsData as $elementData){

          if($elementData->getFormData() == $formData ){
            $this->getDoctrine()->getManager()->remove($elementData);
          }


      }
          foreach($e as $element){
            //get input value request by name html
        $value = $request->request->get($element->getId());
        
        $elementData = new ElementData();
        $elementData->setValue($value);
        $elementData->setElement($element);
        $elementData->setFormData($formData);
        $this->entityManager->persist($elementData);
        }
        
        $this->entityManager->flush();
    }

        $this->addFlash('success', 'données modifiées');
        return $this->redirectToRoute('getData',['url' => $url]);
      
    }

    }




    



  
    
  
