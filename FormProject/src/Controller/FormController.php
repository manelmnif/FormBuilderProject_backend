<?php

namespace App\Controller;


use App\Repository\FormRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;




class FormController extends AbstractFOSRestController
{ 


  
    /**
     * @var FormRepository
     */
    private $formRepository;

     /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    

    public function __construct(FormRepository $formRepository, EntityManagerInterface $entityManager)
    {
        $this->formRepository = $formRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(name="getForms", path="/getForms")
     */
    public function getForm(FormRepository $formRepository)
    {
        $id = 1;
        $forms = $formRepository->getForms($id);

        return $this->render('form/indexForm.html.twig',array
        ('forms' => $forms));

    }



  
    
  
}