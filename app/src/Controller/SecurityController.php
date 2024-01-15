<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
  /**
   * Cette route permet de se connecter
   *
   * @Route("/connexion", name="security.login")
   * @param AuthenticationUtils $auth
   * @return Response
   */
  #[Route('/login', name: 'security.login', methods: ['GET', 'POST'])]
  public function login(AuthenticationUtils $auth): Response
  {
    // Interdire la route aux personnes déjà connectées
    if ($this->getUser()) {
      return $this->redirectToRoute('home.index');
    }

    $error = $auth->getLastAuthenticationError();
    $errorKey = $error ? 'security.login.error' : '';

    return $this->render('pages/security/login.html.twig', [
      'last_username' => $auth->getLastUsername(),
      'error' => $errorKey,
    ]);
  }

  /**
   * Cette route permet de se déconnecter
   *
   * @Route("/deconnexion", name="security.logout")
   * @return void
   */
  #[Route('/logout', name: 'security.logout', methods: ['GET'])]
  public function logout()
  {
    throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
  }

  /**
   * Cette route permet de créer un compte utilisateur
   *
   * @Route("/inscription", name="security.registration")
   * @param Request $request
   * @param EntityManagerInterface $manager
   * @return Response
   */
  #[Route('/signin', name: 'security.registration', methods: ['GET', 'POST'])]
  public function registration(Request $request, EntityManagerInterface $manager): Response
  {
    // Interdire la route aux personnes déjà connectées
    if ($this->getUser()) {
      return $this->redirectToRoute('home.index');
    }

    $user = new User();
    $user->setRoles(['ROLE_USER']);
    $form = $this->createForm(RegistrationType::class, $user);

    // Handle form
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      // Encode password
      $user = $form->getData();
      $manager->persist($user);
      $manager->flush();

      $this->addFlash('success', 'Votre compte a bien été créé !');

      return $this->redirectToRoute('security.login');
    }

    return $this->render('pages/security/registration.html.twig', [
      'form' => $form->createView(),
    ]);
  }
}
