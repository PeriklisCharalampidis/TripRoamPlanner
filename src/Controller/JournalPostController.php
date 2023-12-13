<?php

namespace App\Controller;

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
    public function user(JournalPostRepository $journalPostRepository, Request $request): Response
    {
        $fk_trip_id = $request->get('id');
        $posts = $journalPostRepository->findBy(['id' => $fk_trip_id]);
        return $this->render('journal_post/index.html.twig', [
            'journal_posts' => $posts,
            // 'selectedType' => $fk_trip_id,
        ]);
    }

    #[Route('/new', name: 'app_journal_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $journalPost = new JournalPost();
        $form = $this->createForm(JournalPostType::class, $journalPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($journalPost);
            $entityManager->flush();

            return $this->redirectToRoute('app_journal_post_index', [], Response::HTTP_SEE_OTHER);
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
        $form = $this->createForm(JournalPostType::class, $journalPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_journal_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('journal_post/edit.html.twig', [
            'journal_post' => $journalPost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_journal_post_delete', methods: ['POST'])]
    public function delete(Request $request, JournalPost $journalPost, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$journalPost->getId(), $request->request->get('_token'))) {
            $entityManager->remove($journalPost);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_journal_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
