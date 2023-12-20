<?php

namespace App\Controller;

use App\Entity\PakingList;
use App\Entity\Trip;
use App\Entity\TripPackingListItem;
use App\Form\PakingListType;

use App\Repository\PakingListRepository;
use App\Repository\TripPackingListItemRepository;
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
    #[Route('/mytrip/{id}/new', name: 'app_packingItem_new', methods: ['GET', 'POST'])]
    public function new(Trip $trip, Request $request, EntityManagerInterface $entityManager): Response
    {
        $pakingList = new PakingList();
        $addingPakingListItem = new TripPackingListItem();
        $pakingList->setIsPredefined(false); // Set the default value here
        $customFilter = 'custom_' . $trip->getId();
        $pakingList->setSeasonFilter($customFilter);

        $form = $this->createForm(PakingListType::class, $pakingList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pakingList);
            $entityManager->flush();

            // Ensure the trip exists
            if ($addingPakingListItem) {
                // Associate the packing item with the trip
                $addingPakingListItem->setTrip($trip);
                $addingPakingListItem->setPakingList($pakingList);
                $entityManager->persist($addingPakingListItem);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_trips_packinglist', [
                'id' => $trip->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('paking_list/new.html.twig', [
            'paking_list' => $pakingList,
            'form' => $form->createView(),
            'tripId' => $trip->getId(),
        ]);
    }
    #[Route('/mytrip/{id}', name: 'app_trips_packinglist', methods: ['GET', 'POST'])]
    public function userTrip(PakingListRepository $packingListRepository, Trip $trip, Request $request, EntityManagerInterface $entityManager): Response
    {
        $customFilter = 'custom_' . $trip->getId();

        $tripDateBegin = $trip->getDateBegin();
        $tripDateEnd = $trip->getDateEnd();

        $tripDateBeginMonth = (int)$tripDateBegin->format('n');
        $tripDateEndMonth = (int)$tripDateEnd->format('n');

        $summerStartMonth = 5;
        $summerEndMonth = 10;

        $winterStartMonth = 11;
        $winterEndMonth = 4;

        if ($tripDateBeginMonth >= $summerStartMonth && $tripDateEndMonth <= $summerEndMonth) {
            $season = 'summer';
        } elseif ($tripDateBeginMonth >= $winterStartMonth || $tripDateEndMonth <= $winterEndMonth) {
            $season = 'winter';
        }

        $criteria = ['season_filter' => [
            $season,
            'any',
            $customFilter,
        ]];

        $packing_items = $packingListRepository->findBy($criteria);

        if ($request->get("packingItemAdded")) {
            $packingItemIds = $request->get('packingItemId');

            // Ensure $packingItemIds is an array
            $packingItemIds = is_array($packingItemIds) ? $packingItemIds : explode(',', $packingItemIds);

            foreach ($packingItemIds as $packingItemId) {
                $packingItem = $packingListRepository->find($packingItemId);

                if ($packingItem) {
                    // Create an instance of TripPackingListItem and associate it with Trip and PakingList
                    $tripPackingListItem = new TripPackingListItem();
                    $tripPackingListItem->setTrip($trip);
                    $tripPackingListItem->setPakingList($packingItem);
                    $tripPackingListItem->setCount(1); // Set the count value

                    $entityManager->persist($tripPackingListItem);
                }
            }
            $entityManager->flush();

            return $this->redirectToRoute("app_trips_packinglist", ["id" => $trip->getId()]);
        }

        $userPackingItems = [];
        $tripId = $trip->getId();
        $userPackingItems = $entityManager->getRepository(TripPackingListItem::class)
            ->createQueryBuilder('tpli')
            ->select('tpli', 'p', 't') // Include the associated PakingList and Trip entities
            ->leftJoin('tpli.pakingList', 'p')
            ->leftJoin('tpli.trip', 't')
            ->where('t.id = :tripId')
            ->setParameter('tripId', $tripId)
            ->getQuery()
            ->getResult();

        $addPackingItem = $this->generateUrl('app_trips_packinglist', [
            "packingItemAdded" => true,
            'id' => $trip->getId()
        ], UrlGeneratorInterface::ABSOLUTE_PATH);

        return $this->render('paking_list/index.html.twig', [
            'packing_items' => $packing_items,
            'userPackingItems' => $userPackingItems,
            'add_packing_item' => $addPackingItem,
            /*'season' => 'custom',*/
            'id' => $tripId,
            'tripName' => $trip->getName(),
            'tripDestination' => $trip->getDestination(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_paking_list_edit', methods: ['GET', 'POST'])]
    public function edit(PakingList $pakingList, Request $request, EntityManagerInterface $entityManager, TripPackingListItemRepository $tripPackingListItemRepository): Response
    {
        $trips = $tripPackingListItemRepository->findBy(["pakingList" => $pakingList]);
        if (is_array($trips)) {
            foreach ($trips as $trip) {
                if ($trip->getTrip()->getFkUser() == $this->getUser()) {
                    $tripId = $trip->getTrip()->getId();
                }
            }
        }


        $form = $this->createForm(PakingListType::class, $pakingList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_trips_packinglist', [
                'id' => $tripId
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('paking_list/edit.html.twig', [
            'paking_list' => $pakingList,
            'form' => $form,
            'id' => $tripId

        ]);
    }

    #[Route('/{id}', name: 'app_paking_list_delete', methods: ['POST'])]
    public function delete(Request $request, PakingList $pakingList, EntityManagerInterface $entityManager, TripPackingListItemRepository $tripPackingListItemRepository): Response
    {
        $trips = $tripPackingListItemRepository->findBy(["pakingList" => $pakingList]);
        if (is_array($trips)) {
            foreach ($trips as $trip) {
                if ($trip->getTrip()->getFkUser() == $this->getUser()) {
                    $tripId = $trip->getTrip()->getId();
                }
            }
        }

        if ($this->isCsrfTokenValid('delete' . $pakingList->getId(), $request->request->get('_token'))) {

            $entityManager->remove($pakingList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_trips_packinglist', [
            'id' => $tripId,
        ], Response::HTTP_SEE_OTHER);
    }

    // increase and decrease the count value in TripPackingListItem
    #[Route('/increase-count/{id}', name: 'app_increase_count', methods: ['GET', 'POST'])]
    public function increaseCount(TripPackingListItem $tripPackingListItem, EntityManagerInterface $entityManager): Response
    {
        if ($tripPackingListItem) {
            $tripPackingListItem->setCount($tripPackingListItem->getCount() + 1);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_trips_packinglist', [
            'id' => $tripPackingListItem->getTrip()->getId()
        ]);
    }

    #[Route('/decrease-count/{id}', name: 'app_decrease_count', methods: ['GET', 'POST'])]
    public function decreaseCount(TripPackingListItem $tripPackingListItem, EntityManagerInterface $entityManager, TripPackingListItemRepository $tripPackingListItemRepository): Response
    {

        if ($tripPackingListItem && $tripPackingListItem->getCount() > 1) {
            $tripPackingListItem->setCount($tripPackingListItem->getCount() - 1);
            $entityManager->flush();
        } elseif ($tripPackingListItem && $tripPackingListItem->getCount() == 1) {
            // delete item from TripPackingListItem entity
            $entityManager->remove($tripPackingListItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_trips_packinglist', [
            'id' => $tripPackingListItem->getTrip()->getId()
        ]);
    }
}
