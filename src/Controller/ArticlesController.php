<?php

namespace App\Controller;

use App\Entity\Articles\Circuit;
use App\Entity\Articles\Activity;
use App\Repository\Articles\ActivityRepository;
use App\Repository\Articles\CircuitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticlesController extends AbstractController
{
    #[Route('/articles/circuits', name: 'app_articles_circuits', methods: ['GET'])]
    public function circuits(CircuitRepository $circuitRepository, Request $request): Response
    {
        return $this->render('pages/articles/circuits.html.twig', [
            'circuits' => $circuitRepository->findPublished($request->query->getInt('page', 1))
        ]);
    }

    #[Route('/articles/activities', name: 'app_articles_activities', methods: ['GET'])]
    public function activities(ActivityRepository $activityRepository, Request $request): Response
    {
        return $this->render('pages/articles/activities.html.twig', [
            'activities' => $activityRepository->findPublished($request->query->getInt('page', 1))
        ]);
    }

    // Vue unique des articles
    #[Route('/activity/{slug}', name: 'app_articles_showActivity', methods: ['GET'])]
    public function showActivity(string $slug, ActivityRepository $activityRepository): Response
    {
        $activity = $activityRepository->findOneBy(['slug' => $slug]);

        if (!$activity) {
            throw $this->createNotFoundException('Activity not found');
        }

        return $this->render('pages/articles/showActivity.html.twig', [
            'activity' => $activity
        ]);
    }

    #[Route('/circuit/{slug}', name: 'app_articles_showCircuit', methods: ['GET'])]
    public function showCircuit(string $slug, CircuitRepository $circuitRepository): Response
    {
        $circuit = $circuitRepository->findOneBy(['slug' => $slug]);

        if (!$circuit) {
            throw $this->createNotFoundException('Circuit not found');
        }

        return $this->render('pages/articles/showCircuit.html.twig', [
            'circuit' => $circuit
        ]);
    }
}