<?php

namespace App\Controller;


use App\Repository\FormRepository;
use App\Repository\ElementTypeRepository;
use App\Entity\Section;
use App\Repository\SectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;



class SectionController extends AbstractFOSRestController
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



    public function __construct(SectionRepository $sectionRepository, FormRepository $formRepository, EntityManagerInterface $entityManager)
    {
        $this->formRepository = $formRepository;
        $this->sectionRepository = $sectionRepository;
        $this->entityManager = $entityManager;
    }



    /**
     * @Route(name="createSection", path="/createsection", options={"expose"=true}, methods="POST")
     */
    public function createSection(Request $request, FormRepository $formRepository)
    {

        $name = $request->request->get('name');
        $form = $formRepository->findOneBy([
            'url' => $request->request->get('form'),
        ]);
        $order = $request->request->get('order');

        $section = new Section();
        $section->setName($name);
        $section->setForm($form);
        $section->setOrdre($order);

        $this->entityManager->persist($section);
        $this->entityManager->flush();

        return new JsonResponse([
            'id' => $section->getId(),
            'message' => "ok",

        ]);
    }

    /**
     * @Route(name="deleteSection", path="/deletesection", options={"expose"=true}, methods="POST" )
     */
    public function deleteSection(Request $request, SectionRepository $sectionRepository)
    {
        $section = $request->get('name');
        $section = $sectionRepository->findOneBy([
            'name' => $section,
        ]);


        $this->getDoctrine()->getManager()->remove($section);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            'message' => "ok",

        ]);
    }

    /**
     * @Route(name="updateOrder", path="/updateorder", options={"expose"=true}, methods="POST")
     */
    public function updateOrder(Request $request, SectionRepository $sectionRepository, LoggerInterface $logger)
    {
        $logger->info('I just got the logger');
        $isDelete = $request->get('isDelete');


        if ($isDelete == "true") {
            $order = $request->get('ordersection');
            $section = $request->get('name');
            $section = $sectionRepository->findOneBy([
                'name' => $section,
            ]);


            $this->getDoctrine()->getManager()->remove($section);
            $formId = $section->getForm()->getId();
            $sections = $sectionRepository->getSectionsByFormOrder($formId, $order);


            foreach ($sections as $section) {
                $section->setOrdre($section->getOrdre() - 1);
                $this->entityManager->persist($section);
            }

            $this->getDoctrine()->getManager()->flush();
        }
        // order section after sort
        else {
            $finalOrderSectionSort = $request->get('finalOrderSectionSort');
            $initialOrderSectionSort = $request->get('initialOrderSectionSort');
            $sortedSection = $request->get('sortedSection');
            $sortedSection = $sectionRepository->findOneBy([
                'name' => $sortedSection,
            ]);


            //$sections = $sectionRepository->findAll();
            $formId = $sortedSection->getForm()->getId();
            $sections = $sectionRepository->getSectionByFormId($formId);
            // top
            if ($initialOrderSectionSort > $finalOrderSectionSort) {
                foreach ($sections as $section) {

                    if ($section->getOrdre() < $initialOrderSectionSort && $section->getOrdre() >= $finalOrderSectionSort) {
                        $section->setOrdre($section->getOrdre() + 1);
                        $this->entityManager->persist($section);
                    }
                }
            }
            //down
            else {
                foreach ($sections as $section) {
                    if ($section->getOrdre() > $initialOrderSectionSort && $section->getOrdre() <= $finalOrderSectionSort) {
                        $section->setOrdre($section->getOrdre() - 1);
                        $this->entityManager->persist($section);
                    }
                }
            }

            $sortedSection->setOrdre($finalOrderSectionSort);
            $this->entityManager->persist($sortedSection);
            $this->getDoctrine()->getManager()->flush();
        }

        return new JsonResponse([
            'message' => "ok",

        ]);
    }
}
