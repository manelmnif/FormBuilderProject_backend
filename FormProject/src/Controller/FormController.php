<?php

namespace App\Controller;


use App\Repository\FormRepository;
use App\Repository\SectionRepository;
use App\Entity\Form;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\FormType;
use App\Repository\UserRepository;


class FormController extends AbstractFOSRestController
{ 


  
    /**
     * @var FormRepository
     */
    private $formRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

     /**
     * @var EntityManagerInterface
     */
    private $entityManager;

     /**
     * @var SectionRepository
     */
    private $sectionRepository;

    

    public function __construct(UserRepository $userRepository, FormRepository $formRepository, EntityManagerInterface $entityManager)
    {
        $this->formRepository = $formRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(name="getForms", path="/forms/list")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function getForm( FormRepository $formRepository, Request $request, UserRepository $userRepository, SectionRepository $sectionRepository)
    {
        //getFormsByUser
        $id = 1;
        $forms = $formRepository->getForms($id);

        // add new form
        $formulaire = new Form();
        $form = $this->createForm(FormType::class, $formulaire);
            
            $user = $userRepository->findOneBy(array('id' => $id));
            $formulaire->setUser($user);
          
          
 
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
 
            $em = $this->getDoctrine()->getManager();
            $em->persist($formulaire);
            $em->flush();

            $this->addFlash('success', 'formulaire enregistré');
            return $this->redirectToRoute('getForms');
        }
       
        
       

        return $this->render('formulaire/indexForm.html.twig',array
        ('forms' => $forms,
        'form' => $form->createView()));

    }

  
 
 
    }


    



  
    
  
