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
use App\Repository\ElementRepository;
use App\Repository\UserRepository;
use Proxies\__CG__\App\Entity\Form as EntityForm;
use Symfony\Component\Form\Form as FormForm;
use Symfony\Component\HttpFoundation\JsonResponse;


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
    public function getForm(FormRepository $formRepository, Request $request, UserRepository $userRepository, SectionRepository $sectionRepository)
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

  
   /**
     * @Route(name="visualiseForm", path="/visualiseForm/{url}", options={"expose"=true}, methods="GET")
     */
    public function visualiseForm( ElementRepository $elementRepository, SectionRepository $sectionRepository, string $url,  FormRepository $formRepository)
    {
    
        $form = $formRepository->findOneBy(array('url' => $url));
        $sections = $sectionRepository->getSectionByForm($url);
        $elements = $elementRepository->findAll();

        return $this->render('formulaire/visualiseForm.html.twig', array(
            'sections' => $sections,
            'form' => $form,
            'elements' => $elements
        ));
    }

     /**
     * @Route(name="publishForm", path="/publishForm", options={"expose"=true}, methods="POST")
     */
    public function publishForm(  Request $request,  FormRepository $formRepository)
    {
        $id = $request->request->get('id');
        $form = $formRepository->findOneBy(array('id' => $id));
        $form->setStatus('1');

        $this->entityManager->persist($form);
        $this->entityManager->flush();


        return new JsonResponse([
            'message' => "form publié",
            ]);
    }

      /**
     * @Route(name="deleteForm", path="/deleteForm", options={"expose"=true}, methods="POST")
     */
    public function deleteForm(Request $request,  FormRepository $formRepository)
    {
       $id = $request->request->get('id');
       $form = $formRepository->findOneBy(array('id' => $id));
       $this->getDoctrine()->getManager()->remove($form);
       $this->entityManager->flush();

       return new JsonResponse([
        'message' => "form deleted",
        ]);


    }

    /**
     * @Route(name="updateForm", path="/updateForm", options={"expose"=true}, methods="POST")
     */
    public function updateForm(Request $request,  FormRepository $formRepository)
    {

       $id = $request->request->get('id');
       $name = $request->request->get('name');
       $decription = $request->request->get('description');

       $form = $formRepository->findOneBy(array('id' => $id));
       $form->setName($name);
       $form->setDescription($decription);
       
       $this->entityManager->persist($form);
       $this->entityManager->flush();
       
       return new JsonResponse([
        'message' => "form updated",
        ]);


    }
     /**
     * @Route(name="getPublishedForms", path="/formspublished/list")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function getpublishedForms( FormRepository $formRepository, Request $request, UserRepository $userRepository, SectionRepository $sectionRepository)
    {
        //getFormsByUser
        $id = 1;
        $forms = $formRepository->getForms($id);
      

        return $this->render('formulaire/publishedForm.html.twig',array
        ('forms' => $forms,
       ));

    }

  
 
    }


    



  
    
  
