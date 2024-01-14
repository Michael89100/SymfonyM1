<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Entity\User;
use App\Entity\Workshop;
use App\Form\ResourceType;
use App\Form\UserPasswordType;
use App\Form\UserType;
use App\Repository\WorkshopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Cette classe permet de gérer les routes liées à l'utilisateur
 * 
 * @Route("/user")
 * @author Olivier Perdrix
 */
class UserController extends AbstractController
{

  private $security;

  public function __construct(Security $security)
  {
    $this->security = $security;
  }

  /**
   * Cette route permet d'afficher le formulaire d'édition du profil utilisateur
   * 
   * @Route("/user/{id}/edit", name="user.edit")
   * @param User $user
   * @param Request $request
   * @param EntityManagerInterface $manager
   * @return Response
   */
  #[Route('/user/{id}/edit', name: 'user.edit')]
  public function index(
    User $user,
    Request $request,
    EntityManagerInterface $manager,
    UserPasswordHasherInterface $hasher
  ): Response {
    // Interdire la route aux personnes non connectées
    if (!$this->getUser()) {
      return $this->redirectToRoute('security.login');
    }
    // Interdire la route aux personnes qui ne sont pas le propriétaire du compte
    if ($this->getUser() !== $user) {
      return $this->redirectToRoute('home.index');
    }

    $form = $this->createForm(UserType::class, $user);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      if ($hasher->isPasswordValid($user, $form->getData()->getPlainPassword())) {
        $user = $form->getData();
        $user->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($user);
        $manager->flush();

        $this->addFlash('success', 'Votre profil a bien été modifié.');

        return $this->redirectToRoute('home.index');
      } else {
        $this->addFlash('warning', 'Le mot de passe actuel est incorrect.');
      }

      if ($isNewUser) {
        // Envoi de l'email
        $email = (new TemplatedEmail())
            ->from('Yms.michael89') 
            ->to($user->getEmail())
            ->subject('Bienvenue sur le site!')
            ->htmlTemplate('registration.html.twig') // Assurez-vous que ce template existe
            ->context([
                'user' => $user
            ]);

        $mailer->send($email);
    }
    }

    return $this->render('pages/user/edit.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  /**
   * Cette route permet d'afficher le formulaire de modification du mot de passe
   * 
   * @Route("/user/{id}/change-password", name="user.edit.password", methods={"GET", "POST"})
   * @param User $user
   * @param Request $request
   * @param EntityManagerInterface $manager
   * @return Response
   */
  #[Route('/user/{id}/change-password', name: 'user.edit.password', methods: ['GET', 'POST'])]
  public function editPassword(
    User $user,
    Request $request,
    EntityManagerInterface $manager,
    UserPasswordHasherInterface $hasher
  ): Response {
    // Interdire la route aux personnes non connectées
    if (!$this->getUser()) {
      return $this->redirectToRoute('security.login');
    }
    // Interdire la route aux personnes qui ne sont pas le propriétaire du compte
    if ($this->getUser() !== $user) {
      return $this->redirectToRoute('home.index');
    }

    $form = $this->createForm(UserPasswordType::class);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      if ($hasher->isPasswordValid($user, $form->getData()['plainPassword'])) {
        $user->setUpdatedAt(new \DateTimeImmutable());
        $user->setPlainPassword(
          $form->getData()['newPassword']
        );
        $manager->persist($user);
        $manager->flush();

        $this->addFlash('success', 'Votre mot de passe a bien été modifié.');

        return $this->redirectToRoute('home.index');
      } else {
        $this->addFlash('warning', 'Le mot de passe actuel est incorrect.');
      }
    }

    return $this->render('pages/user/edit.password.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  /**
   * Cette route permet d'afficher les ateliers de l'utilisateur
   * 
   * @Route("/user/{id}/workshops", name="user.workshops")
   */
  #[Route('/user/{id}/workshops', name: 'user.workshops', methods: ['GET'])]
  public function workshops(User $user, WorkshopRepository $workshopRepository, #[MapQueryParameter] int $year = null): Response
  {
    $year = $year ?? Date('Y');

    // Interdire la route aux personnes non connectées
    if (!$this->getUser()) {
      return $this->redirectToRoute('security.login');
    }
    // Interdire la route aux personnes qui ne sont pas le propriétaire du compte
    if ($this->getUser() !== $user) {
      return $this->redirectToRoute('home.index');
    }

    $workshops = $workshopRepository->findByUserAndYear($user, $year);
    $workshopsWithEnrollment = [];
    foreach ($workshops as $workshop) {
      $isFinished = $workshop->getEndAt() < new \DateTimeImmutable();
      $studentCount = $workshop->getStudents()->count();
      $roomCapacity = $workshop->getRoom()->getCapacityMaximum();
      $isFull = $studentCount >= $roomCapacity;
      $workshopsWithEnrollment[] = [
        'workshop' => $workshop,
        'isFinished' => $isFinished,
        'studentCount' => $studentCount,
        'roomCapacity' => $roomCapacity,
        'isFull' => $isFull,
      ];
    }

    return $this->render('pages/user/workshops.html.twig', [
      'user' => $user,
      'workshopsWithEnrollment' => $workshopsWithEnrollment,
      'year' => $year,
      'years' => ['2023', '2024'],
      'current_menu' => 'user.workshops',
    ]);
  }

  #[Route('/user/{id}/speakers/workspace', name: 'user.speakers.workspace', methods: ['GET'])]
  public function speakersWorkspace(User $user, WorkshopRepository $workshopRepository): Response
  {
    // Interdire la route aux personnes non connectées
    if (!$this->getUser()) {
      $this->addFlash('warning', 'Vous devez être connecté pour accéder à cette page.');
      return $this->redirectToRoute('security.login');
    }

    // Interdire la route aux personnes qui n'ont pas le role 'ROLE_SPEAKER'
    if (!$this->security->isGranted('ROLE_SPEAKER')) {
      $this->addFlash('warning', 'Vous n\'avez pas accès à cette page.');
      return $this->redirectToRoute('home.index');
    }

    $workshops = $workshopRepository->findBySpeaker($user);
    $workshopsWithEnrollment = [];
    foreach ($workshops as $workshop) {
      $isFinished = $workshop->getEndAt() < new \DateTimeImmutable();
      $studentCount = $workshop->getStudents()->count();
      $roomCapacity = $workshop->getRoom()->getCapacityMaximum();
      $isFull = $studentCount >= $roomCapacity;
      $workshopsWithEnrollment[] = [
        'workshop' => $workshop,
        'isFinished' => $isFinished,
        'studentCount' => $studentCount,
        'roomCapacity' => $roomCapacity,
        'isFull' => $isFull,
      ];
    }

    return $this->render('pages/user/speakers/workspace.html.twig', [
      'user' => $user,
      'workshopsWithEnrollment' => $workshopsWithEnrollment,
      'current_menu' => 'user.speakers.workspace',

    ]);
  }

  /**
   * Cette route permet d'afficher la page d'un atelier auquel participe l'intervenant
   * 
   * @Route("/user/{user}/speakers/workshop/{workshop}", name="user.speakers.workshop")
   * @param User $user
   * @param Workshop $workshop
   * @param Request $request
   * @param EntityManagerInterface $manager
   * @return Response
   */
  #[Route('/user/{user}/speakers/workshop/{workshop}', name: 'user.speakers.workshop', methods: ['GET', 'POST'])]
  public function speakerWorkshopShow(User $user, Workshop $workshop, Request $request, EntityManagerInterface $manager): Response
  {
    // Interdire la route aux personnes non connectées
    if (!$this->getUser()) {
      $this->addFlash('warning', 'Vous devez être connecté pour accéder à cette page.');
      return $this->redirectToRoute('security.login');
    }

    // Interdire la route aux personnes qui n'ont pas le role 'ROLE_SPEAKER'
    if (!$this->security->isGranted('ROLE_SPEAKER')) {
      $this->addFlash('warning', 'Vous n\'avez pas accès à cette page.');
      return $this->redirectToRoute('home.index');
    }

    // Interdire la route aux personnes qui ne sont pas intervenant sur l'atelier
    if (!$this->isWorkshopSpeaker($workshop, $user)) {
      $this->addFlash('warning', 'Vous n\'êtes pas intervenant sur cet atelier.');
      return $this->redirectToRoute('user.speakers.workspace', [
        'id' => $user->getId(),
      ]);
    }

    // Formulaire d'ajout d'une ressource
    $form = $this->createForm(ResourceType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $resource = $form->getData();
      $resource->setWorkshop($workshop);
      $manager->persist($resource);
      $manager->flush();

      $this->addFlash('success', 'La ressource a bien été ajoutée.');

      $redirect = $this->generateUrl('user.speakers.workshop', [
        'user' => $user->getId(),
        'workshop' => $workshop->getId(),
      ]) . '#resources';
      return $this->redirect($redirect);
    }

    return $this->render('pages/user/speakers/workshop.html.twig', [
      'user' => $user,
      'workshop' => $workshop,
      'isOpened' => $this->isWorkshopOpen($workshop),
      'full' => $this->isWorkshopFull($workshop),
      'year' => $workshop->getEdition()->getYear(),
      'form' => $form->createView(),
    ]);
  }

  // suppression d'une ressource
  #[Route('/user/{user}/speakers/workshop/{workshop}/resource/{resource}/delete', name: 'user.speakers.workshop.resource.delete', methods: ['GET'])]
  public function speakerWorkshopResourceDelete(User $user, Workshop $workshop, Resource $resource, Request $request, EntityManagerInterface $manager): Response
  {
    // Interdire la route aux personnes non connectées
    if (!$this->getUser()) {
      $this->addFlash('warning', 'Vous devez être connecté pour accéder à cette page.');
      return $this->redirectToRoute('security.login');
    }

    // Interdire la route aux personnes qui n'ont pas le role 'ROLE_SPEAKER'
    if (!$this->security->isGranted('ROLE_SPEAKER')) {
      $this->addFlash('warning', 'Vous n\'avez pas accès à cette page.');
      return $this->redirectToRoute('home.index');
    }

    // Interdire la route aux personnes qui ne sont pas intervenant sur l'atelier
    if (!$this->isWorkshopSpeaker($workshop, $user)) {
      $this->addFlash('warning', 'Vous n\'êtes pas intervenant sur cet atelier.');
      return $this->redirectToRoute('user.speakers.workspace', [
        'id' => $user->getId(),
      ]);
    }

    // Vérifie si la ressource appartient bien à l'atelier
    if ($resource->getWorkshop() !== $workshop) {
      $this->addFlash('warning', 'La ressource n\'appartient pas à cet atelier.');
      return $this->redirectToRoute('user.speakers.workspace', [
        'id' => $user->getId(),
      ]);
    }

    $manager->remove($resource);
    $manager->flush();

    $this->addFlash('success', 'La ressource a bien été supprimée.');
    $redirect = $this->generateUrl('user.speakers.workshop', [
      'user' => $user->getId(),
      'workshop' => $workshop->getId(),
    ]) . '#resources';
    return $this->redirect($redirect);
  }

  // -----------------
  // Fonctions privées
  // -----------------

  /**
   * cette fonction permet de savoir si un utilisateur est inscrit à un atelier
   * @param Workshop $workshop l'atelier
   * @param User|null $user l'utilisateur connecté
   * @return bool true si l'utilisateur est inscrit à l'atelier, false sinon
   */
  private function isWorkshopSpeaker(Workshop $workshop, User $user = null): bool
  {
    $userId = $user ? $user->getId() : null;
    // Si l'utilisateur est déjà inscrit à l'atelier
    $isWorkshopSpeaker = false; // Flag pour suivre si l'utilisateur est inscrit

    if ($userId) {
      foreach ($workshop->getSpeakers() as $speaker) {
        if ($speaker->getUserId() == $userId) {
          $isWorkshopSpeaker = true;
          break; // Arrête la boucle si l'utilisateur est trouvé
        }
      }
    }
    return $isWorkshopSpeaker;
  }

  /**
   * Cette fonction permet de savoir si l'atelier est complet
   * @param Workshop $workshop
   * @return bool
   */
  private function isWorkshopFull(Workshop $workshop): bool
  {
    return $workshop->getStudents()->count() >= $workshop->getRoom()->getCapacityMaximum();
  }

  /**
   * Cette fonction permet de savoir si l'atelier est ouvert en fonction de l'année de l'édition
   * @param Workshop $workshop
   * @return bool
   */
  private function isWorkshopOpen(Workshop $workshop): bool
  {
    return $workshop->getEdition()->getYear() == Date('Y');
  }
}
