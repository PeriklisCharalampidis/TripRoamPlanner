<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Entity\JournalPost;
use App\Form\JournalPostType;
use App\Repository\JournalPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/journalpost')]
class JournalPostController extends AbstractController
{
    #[Route('/', name: 'app_journal_post_index', methods: ['GET'])]
    public function index(JournalPostRepository $journalPostRepository): Response
    {
        return $this->render('journal_post/index.html.twig', [
            'journal_posts' => $journalPostRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_journal_trip', methods: ['GET'])]
    public function trip(JournalPostRepository $journalPostRepository, Request $request): Response
    {
        $trip_id = $request->get('id');
        $journal_posts = $journalPostRepository->findBy(['fk_trip' => $trip_id]);
        $journal_post = $journalPostRepository->findOneBy(['fk_trip' => $trip_id]);
        $trip = $journal_post->getFkTrip();
        return $this->render('journal_post/index.html.twig', [
            'journal_posts' => $journal_posts,
            'trip' => $trip,
            // 'trip_id' => $trip_id,
            // 'selectedType' => $fk_trip_id,
        ]);
    }

    #[Route('/new/{id}', name: 'app_journal_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fk_trip = $request->get('id');
        $trip = $entityManager->getRepository(Trip::class)->find($fk_trip);
        $journalPost = new JournalPost();

        $form = $this->createForm(JournalPostType::class, $journalPost, [
            'fk_trip_default' => $fk_trip,
        ]);
                
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $journalPost->setFkTrip($trip);
            $entityManager->persist($journalPost);
            $entityManager->flush();

            return $this->redirectToRoute('app_journal_trip', ["id"=>$fk_trip], Response::HTTP_SEE_OTHER);
        }

        return $this->render('journal_post/new.html.twig', [
            'journal_post' => $journalPost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_journal_post_show', methods: ['GET'])]
    public function show(JournalPost $journalPost): Response
    {
        return $this->render('journal_post/show.html.twig', [
            'journal_post' => $journalPost,
            
        ]);
    }

    #[Route('/{id}/edit', name: 'app_journal_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, JournalPost $journalPost, EntityManagerInterface $entityManager): Response
    {
        $fk_trip = $journalPost->getFkTrip()->getId();
        $trip = $journalPost->getFkTrip();


        $form = $this->createForm(JournalPostType::class, $journalPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            // dd($fk_trip);

            // return $this->redirectToRoute('app_journal_post_index', [], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('app_journal_trip', ["id"=>$fk_trip], Response::HTTP_SEE_OTHER);
        }

        return $this->render('journal_post/edit.html.twig', [
            'journal_post' => $journalPost,
            'form' => $form,
            'trip' => $trip,
        ]);
    }

    #[Route('/{id}', name: 'app_journal_post_delete', methods: ['POST'])]
    public function delete(Request $request, JournalPost $journalPost, EntityManagerInterface $entityManager): Response
    {
        $fk_trip = $journalPost->getFkTrip()->getId();

        if ($this->isCsrfTokenValid('delete'.$journalPost->getId(), $request->request->get('_token'))) {
            $entityManager->remove($journalPost);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_journal_trip', ["id"=>$fk_trip], Response::HTTP_SEE_OTHER);
    }
}
