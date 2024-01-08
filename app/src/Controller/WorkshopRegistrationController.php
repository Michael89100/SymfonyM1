<?php

namespace App\Controller;

use App\Repository\WorkshopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/workshop-registration', name: 'workshop-registration')]
class WorkshopRegistrationController extends AbstractController
{
  /**
   * Cette route permet d'afficher la liste des ateliers dont l'édition est sur l'année en cours ou sur l'année passée en paramètre dans l'url
   * @Route("/{year}", name="workshop-registration", methods={"GET"})
   * @param WorkshopRepository $workshopRepository
   * @param Request $request
   * @return Response
   */
  #[Route('/', name: '.index', methods: ['GET'])]
  public function index(WorkshopRepository $workshopRepository, #[MapQueryParameter] int $year = null): Response
  {
    // récupération de l'année dans l'url ou de l'année en cours
    $year = $year ?? Date('Y');

    // récupération des workshops dont l'année correspond à l'année dans l'url ou à l'année en cours
    $workshops = $workshopRepository->findWorkshopsByYear($year);
    return $this->render('pages/workshopRegistration/index.html.twig', [
      'workshops' => $workshops,
      'year' => $year,
      'years' => ['2023', '2024'],
      'opened' => $year == Date('Y') ? true : false
    ]);
  }

}
