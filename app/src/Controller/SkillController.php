<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Form\SkillType;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/skill')]
class SkillController extends AbstractController
{
    #[Route('/', name: 'skill.index', methods: ['GET'])]
    public function index(SkillRepository $skillRepository): Response
    {
        // seulement des administrateurs peuvent accéder à cette page
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('pages/admin/skill/index.html.twig', [
            'skills' => $skillRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'skill.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // seulement des administrateurs peuvent accéder à cette page
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $skill = new Skill();
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($skill);
            $entityManager->flush();

            return $this->redirectToRoute('skill.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/admin/skill/new.html.twig', [
            'skill' => $skill,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'skill.show', methods: ['GET'])]
    public function show(Skill $skill): Response
    {
        // seulement des administrateurs peuvent accéder à cette page
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('pages/admin/skill/show.html.twig', [
            'skill' => $skill,
        ]);
    }

    #[Route('/{id}/edit', name: 'skill.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Skill $skill, EntityManagerInterface $entityManager): Response
    {
        // seulement des administrateurs peuvent accéder à cette page
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('skill.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/admin/skill/edit.html.twig', [
            'skill' => $skill,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'skill.delete', methods: ['POST'])]
    public function delete(Request $request, Skill $skill, EntityManagerInterface $entityManager): Response
    {
        // seulement des administrateurs peuvent accéder à cette page
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete' . $skill->getId(), $request->request->get('_token'))) {
            $entityManager->remove($skill);
            $entityManager->flush();
        }

        return $this->redirectToRoute('skill.index', [], Response::HTTP_SEE_OTHER);
    }
}
