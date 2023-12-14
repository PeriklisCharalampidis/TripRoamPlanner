<?php

namespace App\Controller;

use App\Entity\PakingList;
use App\Form\PakingListType;
use App\Repository\ActivitiesRepository;
use App\Repository\PakingListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pakinglist')]
class PakingListController extends AbstractController
{
    #[Route('/', name: 'app_paking_list_index', methods: ['GET'])]
    public function index(PakingListRepository $pakingListRepository): Response
    {
        return $this->render('paking_list/index.html.twig', [
            'paking_lists' => $pakingListRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_paking_list_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pakingList = new PakingList();
        $pakingList->setIsPredefined(false); // Set the default value here

        $form = $this->createForm(PakingListType::class, $pakingList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pakingList);
            $entityManager->flush();

            return $this->redirectToRoute('app_paking_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('paking_list/new.html.twig', [
            'paking_list' => $pakingList,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{season}', name: 'app_trips_packinglist', methods: ['GET'])]
    public function user(PakingListRepository $pakingListRepository, Request $request): Response
    {
        $trip_season = $request->get('season');

        $packing_items = $pakingListRepository->findBy(['season_filter' => $trip_season]);
        return $this->render('paking_list/index.html.twig', [
            'packing_items' => $packing_items,
        ]);
    }
    #[Route('/{id}', name: 'app_paking_list_show', methods: ['GET'])]
    public function show(PakingList $pakingList): Response
    {
        return $this->render('paking_list/show.html.twig', [
            'paking_list' => $pakingList,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_paking_list_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PakingList $pakingList, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PakingListType::class, $pakingList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_paking_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('paking_list/edit.html.twig', [
            'paking_list' => $pakingList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_paking_list_delete', methods: ['POST'])]
    public function delete(Request $request, PakingList $pakingList, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pakingList->getId(), $request->request->get('_token'))) {
            $entityManager->remove($pakingList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_paking_list_index', [], Response::HTTP_SEE_OTHER);
    }
}
