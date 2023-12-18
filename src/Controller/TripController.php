<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use App\Entity\Activities;
use App\Entity\User;
use App\Repository\TripRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/trip')]
class TripController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'app_all_trips_index', methods: ['GET'])]
    public function index2(TripRepository $tripRepository, Request $request): Response
    {
        return $this->render('trip/index.html.twig', [
            'trips' => $tripRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_trip_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $trip = new Trip();
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('image')->getData();
            if ($photo) {
                $photoName = $fileUploader->upload($photo);
            } else {
                $photoName = "default.trip.png";
            }
            $trip->setImage($photoName);

            $user = $this->getUser();
            $trip->setFkUser($user);
            $entityManager->persist($trip);
            $entityManager->flush();

            $destination = $trip->getDestination();

            $activities = $this->entityManager->getRepository(Activities::class)
                ->findBy(['destination_filter' => $destination]);

            return $this->redirectToRoute('app_trip_new', [
                'trips' => $trip,
                'activities' => $activities,
            ], Response::HTTP_SEE_OTHER);

            // Render a template to display the activities
            //return $this->render('trip/index.html.twig', [
            //'trips' => $trip,
            //'activities' => $activities,
            //]);
        }

        // Render the form template for creating a new trip
        return $this->render('trip/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_trip_index', methods: ['GET'])]
    public function index(TripRepository $tripRepository, Request $request): Response
    {
        $user_id = $request->get('id');

        $trips = $tripRepository->findBy(['fk_user' => $user_id]);

        return $this->render('trip/index.html.twig', [
            'trips' => $trips,
        ]);
    }

    #[Route('/{id}/show', name: 'app_trip_show', methods: ['GET'])]
    public function show(Trip $trip): Response
    {

        return $this->render('trip/show.html.twig', [
            'trip' => $trip,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_trip_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trip $trip, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_trip_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trip/edit.html.twig', [
            'trip' => $trip,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_trip_delete', methods: ['POST'])]
    public function delete(Request $request, Trip $trip, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trip->getId(), $request->request->get('_token'))) {
            $entityManager->remove($trip);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_trip_index', [], Response::HTTP_SEE_OTHER);
    }
}
