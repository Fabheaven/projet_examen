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
        $faker = Factory::create();

        // Récupérer la référence de la catégorie
        $category = $this->getReference('category_activities_0', Category::class);

        // Liste des états possibles
        $states = ['active', 'inactive', 'pending'];

        // Sélectionner un état aléatoire
        $randomState = $states[array_rand($states)];

        // Créer une nouvelle activité
        $activity = new Activity();
        $activity->setName('Randonnée en montagne')
                 ->setCategory($category)
                 ->setState($randomState); // Assigner un état aléatoire

        // Persister l'activité
        $manager->persist($activity);

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
