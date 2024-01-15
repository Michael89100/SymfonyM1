<?php

namespace App\Controller;

use App\Entity\UserAnswer;
use App\Form\UserAnswerType;
use App\Repository\UserAnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\QuestionRepository;

#[Route('/user/answer')]
class UserAnswerController extends AbstractController
{
    #[Route('/', name: 'user_answer.index', methods: ['GET'])]
    public function index(UserAnswerRepository $userAnswerRepository, QuestionRepository $questionRepository): Response
    {
        return $this->render('pages/user_answer/index.html.twig', [
            'user_answers' => $userAnswerRepository->findAll(),
            'questions' => $userAnswerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'user_answer.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, QuestionRepository $questionRepository): Response
    {
        $quizId = $request->query->get('quizId');
        $questions = $questionRepository->findBy(['quiz' => $quizId]);
        $userAnswer = new UserAnswer();
        $form = $this->createForm(UserAnswerType::class, $userAnswer, [
            'quizId' => $quizId,
            'questions' => $questions,
        ]);
        $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        if ($form->isSubmitted()) {
            $userAnswer = $form->getData();
            $userAnswer->setUser($this->getUser());
            $entityManager->persist($userAnswer);
            $entityManager->flush();

            $this->addFlash('success', 'Votre questionaire a bien été enregistrer !');

            return $this->redirectToRoute('user_answer.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/user_answer/new.html.twig', [
            'user_answer' => $userAnswer,
            'questions' => $questions,
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'user_answer.show', methods: ['GET'])]
    public function show(UserAnswer $userAnswer): Response
    {
        return $this->render('pages/admin/user_answer/show.html.twig', [
            'user_answer' => $userAnswer,
        ]);
    }

    #[Route('/{id}/edit', name: 'user_answer.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserAnswer $userAnswer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserAnswerType::class, $userAnswer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('user_answer.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/admin/user_answer/edit.html.twig', [
            'user_answer' => $userAnswer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'user_answer.delete', methods: ['POST'])]
    public function delete(Request $request, UserAnswer $userAnswer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userAnswer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($userAnswer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_answer.index', [], Response::HTTP_SEE_OTHER);
    }
}
