<?php

namespace App\Controller;

use App\Entity\Articles\Circuit;
use App\Entity\Articles\Activity;
use App\Repository\Articles\ActivityRepository;
use App\Repository\Articles\CircuitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticlesController extends AbstractController
{
    #[Route('/articles/circuits', name: 'app_articles_circuits')]
    public function circuits(CircuitRepository $circuitRepository): Response
    {
        $circuits = $circuitRepository->findPublished();

        return $this->render('pages/articles/circuits.html.twig', [
            'circuits' => $circuits,
        ]);
    }

    #[Route('/articles/activities', name: 'app_articles_activities')]
    public function activities(ActivityRepository $activitiesRepository): Response
    {
        $activities = $activitiesRepository->findPublished();

        return $this->render('pages/articles/activities.html.twig', [
            'activities' => $activities,
        ]);
    }

   
}
