<?php

namespace App\Controller;

use App\Entity\Activities;
use App\Entity\Trip;
use App\Form\ActivitiesType;
use App\Repository\ActivitiesRepository;
use App\Repository\TripRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/activities')]
class ActivitiesController extends AbstractController
{
    #[Route('/', name: 'app_activities_index', methods: ['GET'])]
    public function index(ActivitiesRepository $activitiesRepository): Response
    {
        $userActivities = [];

        return $this->render('activities/index.html.twig', [
            'activities' => $activitiesRepository->findAll(),
            'userActivities' => $userActivities,
        ]);
    }

    #[Route('/new', name: 'app_activities_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        ActivitiesRepository $activitiesRepository,
        TripRepository $tripRepository
    ): Response {
        $activity = new Activities();
        $form = $this->createForm(ActivitiesType::class, $activity);
        $form->handleRequest($request);
        $tripDestination = $request->query->get('destination');
        $trip = $tripRepository->findOneBy(['destination' => $tripDestination]);

        // generating random date within trip date period for custom activities - not woeking but giving current date(now)
        $dateBegin = new DateTime($request->query->get('date_begin'));
        $dateEnd = new DateTime($request->query->get('date_end'));
        $dateInterval = new DateInterval('P1D');
        $dateRange = new DatePeriod($dateBegin, $dateInterval, $dateEnd->modify('+1 day')); // Adding 1 day to include the end date

        $tripDates = [];
        foreach ($dateRange as $date) {
            $tripDates[] = $date->format('Y-m-d');
        }

        if (!empty($tripDates)) {
            $randomIndex = array_rand($tripDates);
            $randomDate = new DateTime($tripDates[$randomIndex]);
            $activity->setDate($randomDate);
        } else {
            $defaultDate = new DateTime('now');
            $activity->setDate($defaultDate);
        }
        // end of setting predefinded date

        if ($form->isSubmitted() && $form->isValid()) {
            $isPredefined = $activity->isIsPredefined();
            $trip = $tripRepository->findOneBy(['destination' => $tripDestination]);

            if (!$isPredefined && $trip) {
                $trip->addFkActivity($activity);
                $activity->setIsPredefined(0);
                $activity->setDestinationFilter($tripDestination);
            }

            $entityManager->persist($activity);
            $entityManager->flush();

            if (!$isPredefined && $trip) {
                $userActivities = $activitiesRepository->findBy(['destination_filter' => $tripDestination]);
                return $this->redirectToRoute('app_trip', ['destination' => $tripDestination]);
            }
        }

        $userActivities = $activitiesRepository->findBy(['destination_filter' => $tripDestination]);

        return $this->render('activities/new.html.twig', [
            'activity' => $activity,
            'form' => $form->createView(),
            'userActivities' => $userActivities,
            'tripDestination' => $tripDestination,
            'tripName' => $trip->getName(),
        ]);
    }

    #[Route('/mytrip/{destination}', name: 'app_trip', methods: ['GET', 'POST'])]
    public function user(
        ActivitiesRepository $activitiesRepository,
        TripRepository $tripRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $tripDestination = $request->get('destination');
        $trip = $tripRepository->findOneBy(['destination' => $tripDestination]);
        $activities = $activitiesRepository->findBy(['destination_filter' => $tripDestination, 'isPredefined' => 1]);


        if ($request->get("activityAdded")) {
            $activityIds = $request->get('activId');

            $activity = $activitiesRepository->find($activityIds);

            if ($activity) {
                $trip->addFkActivity($activity);
                $entityManager->persist($trip);
                $entityManager->flush();
            }
        }

        $userActivities = [];
        $tripId = $trip->getId();
        $userActivities = $activitiesRepository->createQueryBuilder('a')
            ->innerJoin('a.fk_trips', 't')
            ->where('t.id = :tripId')
            ->setParameter('tripId', $tripId)
            ->getQuery()
            ->getResult();


        $userId = $this->getUser()->getUserIdentifier();

        $addActivity = $this->generateUrl('app_trip', [
            "activityAdded" => true,
            'destination' => $trip->getDestination(),
            'userId' => $userId,
            'tripId' => $trip->getId(),
            'activities' => $activities,
        ], UrlGeneratorInterface::ABSOLUTE_PATH);

        return $this->render('activities/index.html.twig', [
            'activities' => $activities,
            'userActivities' => $userActivities,
            'add_activity' => $addActivity,
            "tripDestination" => $tripDestination,
            'trip' => $trip,
            'tripName' => $trip->getName(),
        ]);
    }

    #[Route('/{id}', name: 'app_activities_show', methods: ['GET'])]
    public function show(Activities $activity): Response
    {
        return $this->render('activities/show.html.twig', [
            'activity' => $activity,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_activities_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activities $activity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActivitiesType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_activities_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('activities/edit.html.twig', [
            'activity' => $activity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activities_delete', methods: ['POST'])]
    public function delete(Request $request, Activities $activity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $activity->getId(), $request->request->get('_token'))) {
            $entityManager->remove($activity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_activities_index', [], Response::HTTP_SEE_OTHER);
    }
}
