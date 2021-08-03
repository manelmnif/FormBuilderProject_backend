<?php

namespace App\Controller;

use App\Entity\Form;
use App\Entity\FormData;
use App\Repository\ConstraintValidationRepository;
use App\Repository\ElementRepository;
use App\Repository\SectionRepository;
use App\Repository\ElementTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Section;
use App\Form\SectionType;
use App\Repository\FormRepository;
use Proxies\__CG__\App\Entity\Form as EntityForm;

class ElementTypeController extends AbstractFOSRestController
{


    /**
     * @var SectionRepository
     */
    private $sectionRepository;

    /**
     * @var ElementTypeRepository
     */
    private $elementTypeRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;



    public function __construct(ElementTypeRepository $elementTypeRepository, EntityManagerInterface $entityManager)
    {
        $this->elementTypeRepository = $elementTypeRepository;
        $this->entityManager = $entityManager;
    }

    // getElementType &  getSectionByForm
    /**
     * @Route("/form/{url}", name="indexArray")
     */
    public function getElementType(ElementTypeRepository $elementTypeRepository, SectionRepository $sectionRepository, string $url, FormRepository $formRepository)
    {
        //$this->denyAccessUnlessGranted('[UPDATE_ELEMENTS]', $form);
        /*if ($form->getStatus() == '1') {
            throw $this->createAccessDeniedException();
        }*/
       // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $elementTypes = $elementTypeRepository->findAll();
        $sections = $sectionRepository->getSectionByForm($url);
        $form = $formRepository->findOneBy([
            'url' => $url]);
    

        return $this->render('index.html.twig', array(
            'sections' => $sections,
            'elementTypes' => $elementTypes,
            'form' => $form,
            
        ));
    }

}
