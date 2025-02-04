<?php

namespace App\DataFixtures\Articles;

use App\Entity\Articles\Activity;
use App\Entity\Articles\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ActivityFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Créer une instance de Faker pour générer des données aléatoires
        $faker = Factory::create('fr_FR');

        // Récupérer la référence de la catégorie
        $category = $this->getReference('category_activities_0', Category::class);

        // Liste des états possibles
        $states = ['active', 'inactive', 'pending'];

        // Créer 50 activités
        for ($i = 0; $i < 50; $i++) {
            // Créer une nouvelle activité
            $activity = new Activity();
            $activity->setName($faker->sentence(3)) // Nom aléatoire
                     ->setDescription($faker->paragraph) // Description aléatoire
                     ->setCategory($category)
                     ->setState($states[array_rand($states)])
                     ->setSlug($faker->unique()->slug);
                     
            // Persister l'activité
            $manager->persist($activity);
        }

        // Enregistrer en base de données
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}