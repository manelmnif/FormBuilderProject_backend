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
//redirect page create data

  /**
     * @Route(name="createData", path="/createData/{url}", options={"expose"=true}, methods="Get")
     */
    public function createData(Form $form, ElementRepository $elementRepository, SectionRepository $sectionRepository, string $url,  FormRepository $formRepository)
    {
      // if status form is not published don't have access to this page
      if ($form->getStatus() != '1') {
        throw $this->createAccessDeniedException();
    }
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
      $this->entityManager->persist($formData);
      $this->entityManager->flush();
      $formData->setName($form->getName().$formData->getId());
      
      
      if ($request->getMethod() == Request::METHOD_POST){
          foreach($e as $element){
            //get input value request by name html
        $value = $request->request->get($element->getLabel());
        
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
     * @Route(name="getData", path="/getData/{url}", options={"expose"=true}, methods="Get")
     */
    public function getData(Form $form, ElementRepository $elementRepository, SectionRepository $sectionRepository, string $url, FormRepository $formRepository, ElementDataRepository $elementDataRepository, FormDataRepository $formDataRepository)
    {
      $form = $formRepository->findOneBy(array('url' => $url));
      $e = [];
      
      $sections = $sectionRepository->getSectionByForm($url);
      foreach ($sections as $section){
        $elements = $elementRepository->getElementBySection($section->getId());
        $e = array_merge($e, $elements);  
      }
      $dataElements = $elementDataRepository->findAll();
      $dataForms = $formDataRepository->findAll();


      return $this->render('formulaire/getData.html.twig', array(
          'elements' => $e,
          'form' => $form,
          'dataElements' => $dataElements,
          'dataForms' => $dataForms
        
      ));
      
    }

    }




    



  
    
  
