<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\User;
use App\Entity\Workshop;
use App\Form\StudentType;
use App\Repository\WorkshopRepository;
use Doctrine\ORM\EntityManagerInterface;
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
      'opened' => $this->isWorkshopOpen($workshop),
      'full' => $this->isWorkshopFull($workshop),
      'year' => $workshop->getEdition()->getYear(),
      'urlReferer' => $urlReferer,
      'isUserEnrolled' => $this->getUser() ? $this->isUserEnrolled($workshop, $this->getUser()) : false,
    ]);
  }

  /**
   * Cette route permet d'inscrire un utilisateur à un atelier
   * @Route("/{year}/{id}/register", name="workshop-registration", methods={"POST"})
   * @param Workshop $workshop
   * @return Response
   */
  #[Route('/{id}/register', name: '.register', methods: ['GET', 'POST'])]
  public function register(Workshop $workshop, Request $request, EntityManagerInterface $manager): Response
  {
    // Si l'atelier n'existe pas, on renvoie une erreur 404
    if (!$workshop) {
      throw $this->createNotFoundException('L\'atelier n\'existe pas');
    }

    // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
    if (!$this->getUser()) {
      return $this->redirectToRoute('security.login');
    }

    if ($this->isUserEnrolled($workshop, $this->getUser())) {
      $this->addFlash('warning', 'Vous êtes déjà inscrit à l\'atelier ' . $workshop->getName());
      return $this->redirectToRoute('workshop-registration.show', ['id' => $workshop->getId()]);
    }

    // Si l'atelier est complet, on le redirige vers la page de l'atelier
    if ($workshop->getStudents()->count() >= $workshop->getRoom()->getCapacityMaximum()) {
      return $this->redirectToRoute('workshop-registration.show', ['id' => $workshop->getId()]);
    }

    // On ajoute l'utilisateur à l'atelier
    // on affiche d'abord le formulaire d'inscription
    $student = new Student();
    $form = $this->createForm(StudentType::class, $student, [
      'action' => $this->generateUrl('workshop-registration.register', ['id' => $workshop->getId()]),
      'method' => 'POST',
    ]);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $student = $form->getData();
      $student->setUser($this->getUser());
      $student->addWorkshop($workshop);

      $manager->persist($student);
      $manager->flush();

      $this->addFlash('success', 'Vous êtes inscrit à l\'atelier ' . $workshop->getName());

      return $this->redirectToRoute('workshop-registration.show', ['id' => $workshop->getId()]);
    }

    // On redirige l'utilisateur vers la page de l'atelier
    return $this->render('pages/workshopRegistration/register.html.twig', [
      'workshop' => $workshop,
      'form' => $form->createView(),
    ]);
  }

  /**
   * Cette route permet de désinscrire un utilisateur à un atelier
   * @Route("/{year}/{id}/unregister", name="workshop-registration", methods={"POST"})
   * @param Workshop $workshop
   * @return Response
   */
  #[Route('/{id}/unregister', name: '.unregister', methods: ['GET', 'POST'])]
  public function unregister(Workshop $workshop, Request $request, EntityManagerInterface $manager): Response
  {
    // Si l'atelier n'existe pas, on renvoie une erreur 404
    if (!$workshop) {
      throw $this->createNotFoundException('L\'atelier n\'existe pas');
    }

    // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
    if (!$this->getUser()) {
      return $this->redirectToRoute('security.login');
    }

    // Si l'utilisateur n'est pas inscrit à l'atelier, on le redirige vers la page de l'atelier
    if (!$this->isUserEnrolled($workshop, $this->getUser())) {
      $this->addFlash('warning', 'Vous n\'êtes pas inscrit à l\'atelier ' . $workshop->getName());
      return $this->redirectToRoute('workshop-registration.show', ['id' => $workshop->getId()]);
    }

    // On supprime l'utilisateur de l'atelier
    $student = $this->getStudent($workshop, $this->getUser());
    $student->removeWorkshop($workshop);

    $manager->persist($student);
    $manager->flush();

    $this->addFlash('success', 'Vous êtes désinscrit de l\'atelier ' . $workshop->getName());

    // On redirige l'utilisateur vers la page de l'atelier
    return $this->redirectToRoute('workshop-registration.show', ['id' => $workshop->getId()]);
  }

  /**
   * cette fonction permet de savoir si un utilisateur est inscrit à un atelier
   * @param Workshop $workshop l'atelier
   * @param User|null $user l'utilisateur connecté
   * @return bool true si l'utilisateur est inscrit à l'atelier, false sinon
   */
  private function isUserEnrolled(Workshop $workshop, User $user): bool
  {
    $userId = $user ? $user->getId() : null;
    // Si l'utilisateur est déjà inscrit à l'atelier
    $isUserEnrolled = false; // Flag pour suivre si l'utilisateur est inscrit

    if ($userId) {
      foreach ($workshop->getStudents() as $student) {
        if ($student->getUserId() === $userId) {
          $isUserEnrolled = true;
          break; // Arrête la boucle si l'utilisateur est trouvé
        }
      }
    }
    return $isUserEnrolled;
  }

  private function getStudent(Workshop $workshop, User $user): ?Student
  {
    $userId = $user ? $user->getId() : null;
    // Si l'utilisateur est déjà inscrit à l'atelier
    $student = null; // Flag pour suivre si l'utilisateur est inscrit

    if ($userId) {
      foreach ($workshop->getStudents() as $student) {
        if ($student->getUserId() === $userId) {
          $student = $student;
          break; // Arrête la boucle si l'utilisateur est trouvé
        }
      }
    }
    return $student;
  }

  private function isWorkshopFull(Workshop $workshop): bool
  {
    return $workshop->getStudents()->count() >= $workshop->getRoom()->getCapacityMaximum();
  }

  private function isWorkshopOpen(Workshop $workshop): bool
  {
    return $workshop->getEdition()->getYear() == Date('Y');
  }
}
