<?php

namespace App\Controller;

use App\Entity\Sector;
use App\Form\SectorType;
use App\Repository\SectorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sector')]
class SectorController extends AbstractController
{
    #[Route('/', name: 'sector.index', methods: ['GET'])]
    public function index(SectorRepository $sectorRepository): Response
    {
        return $this->render('pages/admin/sector/index.html.twig', [
            'sectors' => $sectorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'sector.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sector = new Sector();
        $form = $this->createForm(SectorType::class, $sector);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sector);
            $entityManager->flush();

            return $this->redirectToRoute('sector.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/admin/sector/new.html.twig', [
            'sector' => $sector,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'sector.show', methods: ['GET'])]
    public function show(Sector $sector): Response
    {
        return $this->render('pages/admin/sector/show.html.twig', [
            'sector' => $sector,
        ]);
    }

    #[Route('/{id}/edit', name: 'sector.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sector $sector, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SectorType::class, $sector);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('sector.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/admin/sector/edit.html.twig', [
            'sector' => $sector,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'sector.delete', methods: ['POST'])]
    public function delete(Request $request, Sector $sector, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sector->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sector);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sector.index', [], Response::HTTP_SEE_OTHER);
    }
}
