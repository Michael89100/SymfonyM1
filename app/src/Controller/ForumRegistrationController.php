<?php

namespace App\Controller;

use App\Repository\WorkshopRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/forum-subscription', name: 'forum-subscription')]
class ForumRegistrationController extends AbstractController
{
  #[Route('/', name: '.index')]
  public function index(WorkshopRepository $workshopRepository, PaginatorInterface $paginator, Request $request)
  {
    // récupération des workshops dont l'édition est sur l'année en cours
    $workshops = $workshopRepository->findWorkshopsByYear(Date('Y'));
    return $this->render('pages/forumRegistration/index.html.twig', [
      'workshops' => $workshops,
      'year' => Date('Y'),
    ]);
  }
}
