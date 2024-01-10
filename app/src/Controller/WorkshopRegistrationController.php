<?php

namespace App\Controller;

use App\Entity\Workshop;
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

  /**
   * Cette route permet d'afficher un atelier spécifique
   * @Route("/{year}/{id}", name="workshop-registration", methods={"GET"})
   * @param Workshop $workshop
   * @return Response
   */
  #[Route('/{id}', name: '.show', methods: ['GET'])]
  public function show(Workshop $workshop, Request $request): Response
  {
    // Si l'atelier n'existe pas, on renvoie une erreur 404
    if (!$workshop) {
      throw $this->createNotFoundException('L\'atelier n\'existe pas');
    }
    
    // Récupérer l'URL de la page appelante
    $urlReferer = $request->headers->get('referer');

    return $this->render('pages/workshopRegistration/show.html.twig', [
      'workshop' => $workshop,
      'opened' => $workshop->getEdition()->getYear() == Date('Y') ? true : false,
      'year' => $workshop->getEdition()->getYear(),
      'urlReferer' => $urlReferer,
    ]);
  }

  /**
   * Cette route permet d'inscrire un utilisateur à un atelier
   * @Route("/{year}/{id}/register", name="workshop-registration", methods={"POST"})
   * @param Workshop $workshop
   * @return Response
   */
  // #[Route('/{id}/register', name: '.register', methods: ['GET','POST'])]
  // public function register(Workshop $workshop): Response
  // {
  //   // Si l'atelier n'existe pas, on renvoie une erreur 404
  //   if (!$workshop) {
  //     throw $this->createNotFoundException('L\'atelier n\'existe pas');
  //   }

  //   // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
  //   if (!$this->getUser()) {
  //     return $this->redirectToRoute('app_login');
  //   }

  //   // Si l'utilisateur est déjà inscrit à l'atelier, on le redirige vers la page de l'atelier
  //   // Vérifie si un des étudiants de l'atelier a le même id que l'utilisateur connecté
  //   if ($workshop->getStudents()->exists(function ($key, $element) {
  //     return $element->getId() == $this->getUser()->get
  //   })) {
  //     return $this->redirectToRoute('workshop-registration.show', ['id' => $workshop->getId()]);
  //   }

  //   // Si l'atelier est complet, on le redirige vers la page de l'atelier
  //   if ($workshop->getStudents()->count() >= $workshop->getMaxStudents()) {
  //     return $this->redirectToRoute('workshop-registration.show', ['id' => $workshop->getId()]);
  //   }

  //   // On ajoute l'utilisateur à l'atelier
  //   $workshop->addStudent($this->getUser()->getStudent());
  //   $this->getDoctrine()->getManager()->flush();

  //   // On redirige l'utilisateur vers la page de l'atelier
  //   return $this->redirectToRoute('workshop-registration.show', ['id' => $workshop->getId()]);
  // }

}
