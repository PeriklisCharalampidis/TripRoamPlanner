<?php

namespace App\Controller;

use App\Entity\Activities;
use App\Form\ActivitiesType;
use App\Repository\ActivitiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activities')]
class ActivitiesController extends AbstractController
{
    #[Route('/', name: 'app_activities_index', methods: ['GET'])]
    public function index(ActivitiesRepository $activitiesRepository): Response
    {
        return $this->render('activities/index.html.twig', [
            'activities' => $activitiesRepository->findBy('fk_user'),
        ]);
    }
    #[Route('/{destination}', name: 'app_trip', methods: ['GET'])]
    public function user(ActivitiesRepository $activitiesRepository, Request $request): Response
    {
        $trip_destination = $request->get('destination');

        $activities = $activitiesRepository->findBy(['destination_filter' => $trip_destination]);
        return $this->render('activities/index.html.twig', [
            'activities' => $activities,

        ]);
    }
    #[Route('/new', name: 'app_activities_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $activity = new Activities();
        $form = $this->createForm(ActivitiesType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($activity);
            $entityManager->flush();

            return $this->redirectToRoute('app_activities_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('activities/new.html.twig', [
            'activity' => $activity,
            'form' => $form,
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
        if ($this->isCsrfTokenValid('delete'.$activity->getId(), $request->request->get('_token'))) {
            $entityManager->remove($activity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_activities_index', [], Response::HTTP_SEE_OTHER);
    }
}
