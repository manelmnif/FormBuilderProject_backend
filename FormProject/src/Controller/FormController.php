<?php

namespace App\Controller;

use App\Entity\Model;
use App\Entity\Element;
use App\Repository\ModelRepository;
use App\Repository\UserRepository;
use App\Repository\ElementRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;


class FormController extends AbstractFOSRestController
{
    /**
     * @var ModelRepository
     */
    private $modelRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, ModelRepository $modelRepository)
    {
        $this->modelRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/create")
     * @param Request $request
     * @return View
     */
    public function create(Request $request, UserRepository $userRepository):view
    {
        $name = $request->get('name');
        $user = $userRepository->find(1);
        $model = new Model();
        $model->setName($name);
        $model->setUser($user);
        
        $this->entityManager->persist($model);
        $this->entityManager->flush();

        return $this->view($model, Response::HTTP_CREATED)->setContext((new Context())->setGroups(['public']));
        

    }

    /**
     * @Route("/createElement")
     * @param Request $request
     * @return View
     */
    public function createElement(Request $request, ModelRepository $modelRepository):view
    {
        $label = $request->get('label');
        $placeholder = $request->get('placeholder');
        $isrequired = $request->get('isrequired');
        $name = $request->get('name');
        $model = $request->get('model');


        
        $model = $modelRepository->findOneBy([
            'name' => $model,
        ]);
        $element = new Element();
        $element->setLabel($label);
        $element->setPlaceholder($placeholder);
        $element->setIsrequired($isrequired);
        $element->setName($name);
        $element->setModel($model);

        
        $this->entityManager->persist($element);
        $this->entityManager->flush();

        return $this->view($model, Response::HTTP_CREATED)->setContext((new Context())->setGroups(['public']));
        

    }

    /**
     * @Route("/deleteElement")
     * @param Request $request
     */
    public function deleteElement(Request $request,ElementRepository $elementRepository)
    {
        $element = $request->get('name');
        $element = $elementRepository->findOneBy([
            'name' => $element,
        ]);

        
        $this->getDoctrine()->getManager()->remove($element);
        $this->getDoctrine()->getManager()->flush();
        
        return "ok";
    }

    /**
     * @Route("/editElement")
     * @param Request $request
     * @return View
     */
    public function editElement(Request $request, ElementRepository $elementRepository):view
    {
        $name = $request->get('name');
        $label = $request->get('label');
        $placeholder = $request->get('placeholder');
        $isrequired = $request->get('isrequired');
        //$model = $request->get('model');
        
        
        $element = $elementRepository->findOneBy([
            'name' => $name,
        ]);
       /* $model = $modelRepository->findOneBy([
            'id' => $model,
        ]);*/

        //$element = new Element($name);
        $element->setLabel($label);
        $element->setPlaceholder($placeholder);
        $element->setIsrequired($isrequired);
        $element->setName($name);
        //$element->setModel($model);

        $this->entityManager->persist($element);
        $this->entityManager->flush();

        return $this->view($element, Response::HTTP_CREATED)->setContext((new Context())->setGroups(['public']));
        

    }

    /**
     * @Route("/getForm")
     * @param Request $request
     * @return View
     */
    public function getForm(Request $request, ElementRepository $elementRepository)
    {
       
        $name = $request->get('name');
        /*$model = $modelRepository->findOneBy([
            'name' => $name,
        ]);*/

            $form = $elementRepository->findForm($name);
    
            return ($form);
        
    }

    
}