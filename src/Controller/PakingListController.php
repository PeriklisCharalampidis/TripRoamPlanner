<?php

namespace App\Controller;

use App\Entity\PakingList;
use App\Entity\Trip;
use App\Form\PakingListType;

use App\Repository\PakingListRepository;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/pakinglist')]
class PakingListController extends AbstractController
{
    #[Route('/mytrip/{tripId}/new', name: 'app_packingItem_new', methods: ['GET', 'POST'])]
    public function new(TripRepository $tripRepository,Request $request, EntityManagerInterface $entityManager): Response
    {
        /*$trip_season = $request->get('season');*/
        $tripId = $request->get('tripId');
        $pakingList = new PakingList();
        $pakingList->setIsPredefined(false); // Set the default value here
        $customFilter = 'custom_' . $tripId;
        $pakingList->setSeasonFilter($customFilter);
        /*$pakingList->setSeasonFilter('custom');*/

        $form = $this->createForm(PakingListType::class, $pakingList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pakingList);
            $entityManager->flush();

            $trip = $tripRepository->find($tripId);

            // Ensure the trip exists
            if ($trip) {
                // Associate the packing item with the trip
                $trip->addFkPakingList($pakingList);
                $entityManager->persist($trip);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_trips_packinglist', [
                /*'season' => $trip_season,*/
                'tripId' => $tripId,
                ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('paking_list/new.html.twig', [
            'paking_list' => $pakingList,
            'form' => $form->createView(),
            /*'season' => $trip_season,*/
            'tripId' => $tripId,
        ]);
    }
    #[Route('/mytrip/{tripId}', name: 'app_trips_packinglist', methods: ['GET', 'POST'])]
    public function user(
        PakingListRepository $packingListRepository,
        TripRepository $tripRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $tripId = $request->get('tripId');
        $trip = $tripRepository->findOneBy(['id' => $tripId]);

        $customFilter = 'custom_' . $tripId;

        $tripDateBegin = $trip->getDateBegin();
        $tripDateEnd = $trip->getDateEnd();

        $tripDateBeginMonth = (int)$tripDateBegin->format('n');
        $tripDateEndMonth = (int)$tripDateEnd->format('n');

        $summerStartMonth = 5;
        $summerEndMonth = 10;

        $winterStartMonth = 11;
        $winterEndMonth = 4;

        /*$season = 'any';*/

        if ($tripDateBeginMonth >= $summerStartMonth && $tripDateEndMonth <= $summerEndMonth) {
            $season = 'summer';
        } elseif ($tripDateBeginMonth >= $winterStartMonth || $tripDateEndMonth <= $winterEndMonth) {
            $season = 'winter';
        }

        $criteria = ['season_filter' => [
            $season,
            /*'any',*/
            $customFilter,
            ]];
        /*dd($criteria);*/

        $packing_items = $packingListRepository->findBy($criteria);
        /*$packing_items = $packingListRepository->findAll();*/

        if($request->get("packingItemAdded")){
            $packingItemIds = $request->get('packingItemId');

            $packingItem = $packingListRepository->find($packingItemIds);

            if($packingItem) {
                $trip->addFkPakingList($packingItem);
                $entityManager->persist($trip);
                $entityManager->flush();
            }
        }

        $userPackingItems = [];
        $tripId = $trip->getId();
        $userPackingItems = $packingListRepository->createQueryBuilder('a')
            ->innerJoin('a.fk_trips','t')
            ->where('t.id = :tripId')
            ->setParameter('tripId', $tripId)
            ->getQuery()
            ->getResult();

        $userId = $this->getUser()->getUserIdentifier();

        $addPackingItem = $this->generateUrl('app_trips_packinglist',[
            "packingItemAdded" => true,
            'userId' => $userId,
            'tripId' => $tripId,
            'packing_items' => $packing_items,
        ],UrlGeneratorInterface::ABSOLUTE_PATH);

        foreach ($packing_items as $packing_item) {
            $deleteForm = $this->renderView('paking_list/_delete_form.html.twig', [
                'packing_item' => $packing_item,
                'tripId' => $tripId,
            ]);

            $packing_item->deleteForm = $deleteForm;
        }

        return $this->render('paking_list/index.html.twig', [
            'packing_items' => $packing_items,
            'userPackingItems' => $userPackingItems,
            'add_packing_item' => $addPackingItem,
            'season' => 'custom',
            'tripId' => $tripId,
            'tripName' => $trip->getName(),
            'tripDestination' => $trip->getDestination(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_paking_list_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PakingList $pakingList, EntityManagerInterface $entityManager): Response
    {
        /*$trip_season = $request->get('season');*/

        $form = $this->createForm(PakingListType::class, $pakingList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_trips_packinglist', [
                /*'season' => $trip_season,*/
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('paking_list/edit.html.twig', [
            'paking_list' => $pakingList,
            'form' => $form,
            /*'season' => $trip_season,*/
        ]);
    }

    #[Route('/{id}', name: 'app_paking_list_delete', methods: ['POST'])]
    public function delete(Request $request, PakingList $pakingList, EntityManagerInterface $entityManager): Response
    {
        $tripId = $request->get('tripId');

        if ($this->isCsrfTokenValid('delete'.$pakingList->getId(), $request->request->get('_token'))) {
            $entityManager->remove($pakingList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_trips_packinglist', [
            'tripId' => $tripId,
        ], Response::HTTP_SEE_OTHER);
    }
}
