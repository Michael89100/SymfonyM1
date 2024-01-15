<?php

namespace App\Controller\Api;

use App\Repository\WorkshopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * API (sans authentification) pour récupérer le nombre d’inscrit par atelier et créneau horaire
 */
#[Route('/api/v1', name: 'api')]
class ApiController extends AbstractController
{
    #[Route('/', name: '.index')]
    public function index(WorkshopRepository $workshop): Response
    {
        // Récupère la liste des ateliers
        $workshops = $workshop->findAll();
        $workshopsWithNbInscrits = [];
        // ajout du nombre d'inscrits
        foreach ($workshops as $workshop) {
            $workshopsWithNbInscrits[] = [
                'id' => $workshop->getId(),
                'name' => $workshop->getName(),
                'dateStart' => $workshop->getStartAt(),
                'nbInscrits' => count($workshop->getStudents())
            ];
        }

        // retourne en json le nombre d'inscrit par atelier et créneau horaire
        return $this->json($workshopsWithNbInscrits, 200, [], ['groups' => 'workshop:read']);
    }
}
