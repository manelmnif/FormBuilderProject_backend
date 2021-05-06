<?php

namespace App\Controller;


use App\Repository\ConstraintValidationRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;



class ConstraintValidationController extends AbstractFOSRestController
{ 


  
    /**
     * @var ConstraintValidationRepository
     */
    private $constraintValidationRepository;

     /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    

    public function __construct(ConstraintValidationRepository $constraintValidationRepository, EntityManagerInterface $entityManager)
    {
        $this->constraintValidationRepository = $constraintValidationRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(name="getConstraintByElementType", path="/", options={"expose"=true}, methods="POST")
     */
   

    public function getConstraintByElementType(ConstraintValidationRepository $constraintValidationRepository, Request $type)
    {   
        $type = $type->request->get('id');
     
        $constraint = $constraintValidationRepository->findConstraintByElementType($type);

        $json = json_encode(array(
            'constraint' => $constraint,
          
        ));
        $response = new JsonResponse();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($json);
        return $response;

    }



  
    
  
}
