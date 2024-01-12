<?php

namespace App\Controller;

use App\Entity\User;
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

class UserController extends AbstractController
{
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
}
