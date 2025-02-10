<?php

namespace App\Controller;

use App\Entity\Articles\Category;
use App\Form\SearchByCategoryType;
use App\Repository\Articles\ActivityRepository;
use App\Repository\Articles\CategoryRepository;
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
            throw $this->createNotFoundException('Circuit non trouvé.');
        }

        return $this->render('pages/articles/showCircuit.html.twig', [
            'circuit' => $circuit,
        ]);
    }


    #[Route('/articles/search-by-category', name: 'app_articles_search_by_category', methods: ['GET', 'POST'])]
    public function searchByCategory(
        Request $request,
        CategoryRepository $categoryRepository,
        ActivityRepository $activityRepository,
        CircuitRepository $circuitRepository
    ): Response {
        // Créer le formulaire de recherche
        $form = $this->createForm(SearchByCategoryType::class);
        $form->handleRequest($request);

        $activities = [];
        $circuits = [];
        $category = null;

        // Traiter la soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->get('category')->getData();

            if ($category instanceof Category) {
                // Utiliser les méthodes personnalisées pour récupérer les activités et les circuits
                $activities = $activityRepository->findByCategoryWithJoins($category);
                $circuits = $circuitRepository->findByCategoryWithJoins($category);
            }
        }

        // Afficher la page avec le formulaire et les résultats
        return $this->render('pages/articles/search_by_category.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
            'activities' => $activities,
            'circuits' => $circuits,
        ]);
    }

    #[Route('/categories/{slug}', name: 'category.list', methods: ['GET'])]
public function listCategories(
    CategoryRepository $categoryRepository,
    CircuitRepository $circuitRepository,
    ActivityRepository $activityRepository,
    Request $request,
    string $slug = null // Slug de la catégorie
): Response {
    // Récupérer toutes les catégories pour le dropdown
    $allCategories = $categoryRepository->findAll();
    $allCategories = array_filter($allCategories, function($category) {
        return !empty($category->getSlug());
    });

    // Si un slug est fourni, récupérer la catégorie correspondante
    $currentCategory = null;
    if ($slug) {
        $currentCategory = $categoryRepository->findOneBy(['slug' => $slug]);
    }

    // Filtrer les circuits et activités par catégorie si une catégorie est sélectionnée
    if ($currentCategory) {
        $circuits = $circuitRepository->findPublishedByCategory($currentCategory, $request->query->getInt('page', 1));
        $activities = $activityRepository->findPublishedByCategory($currentCategory, $request->query->getInt('page', 1));
    } else {
        // Sinon, afficher tous les circuits et activités publiés
        $circuits = $circuitRepository->findPublished($request->query->getInt('page', 1));
        $activities = $activityRepository->findPublished($request->query->getInt('page', 1));
    }

    // Exemple de logique pour décider vers quelle vue rediriger
    $route = $request->query->get('view', 'circuits'); // Vous pouvez envoyer un paramètre pour choisir la vue

    if ($route === 'activities') {
        // Si la vue demandée est 'activities', rediriger vers la page des activités
        return $this->render('pages/articles/activities.html.twig', [
            'allCategories' => $allCategories,
            'activities' => $activities,
            'currentCategory' => $currentCategory, // Passer la catégorie actuelle au template
        ]);
    } else {
        // Par défaut, afficher la page des circuits
        return $this->render('pages/articles/circuits.html.twig', [
            'allCategories' => $allCategories,
            'circuits' => $circuits,
            'currentCategory' => $currentCategory, // Passer la catégorie actuelle au template
        ]);
    }
}
    
}