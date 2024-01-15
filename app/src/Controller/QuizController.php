<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\Question;
use App\Form\QuizType;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quiz')]
class QuizController extends AbstractController
{
    #[Route('/', name: 'quiz.index', methods: ['GET'])]
    public function index(QuizRepository $quizRepository): Response
    {
        return $this->render('pages/admin/quiz/index.html.twig', [
            'quizzes' => $quizRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'quiz.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute('quiz.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/admin/quiz/new.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'quiz.show', methods: ['GET'])]
    public function show(Quiz $quiz): Response
    {
        return $this->render('pages/admin/quiz/show.html.twig', [
            'quiz' => $quiz,
        ]);
    }

    #[Route('/{id}/edit', name: 'quiz.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('quiz.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/admin/quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'quiz.delete', methods: ['POST'])]
    public function delete(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quiz->getId(), $request->request->get('_token'))) {
            $entityManager->remove($quiz);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quiz.index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{quizId}/question/{questionId}/remove', name: 'quiz.remove_question', methods: ['POST'])]
    public function removeQuestion(Request $request, EntityManagerInterface $entityManager, $quizId, $questionId): Response
    {
        $question = $entityManager->getRepository(Question::class)->find($questionId);
        $quiz = $entityManager->getRepository(Quiz::class)->find($quizId);

        if (!$question) {
            throw $this->createNotFoundException('Question not found');
        }

        $quiz->removeQuestion($question);
        $entityManager->flush();

        return $this->redirectToRoute('quiz.show', ['id' => $quiz->getId()], Response::HTTP_SEE_OTHER);
    }
}
