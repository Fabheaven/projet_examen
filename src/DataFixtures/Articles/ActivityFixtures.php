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
        $faker = Factory::create('fr_FR');
        $states = ['active', 'inactive', 'pending'];

        $categoryReferences = [];

        // Charger les références des catégories d'activités
        for ($i = 0; $i < 8; $i++) {
            $referenceKey = "category_activities_$i";
            if ($this->hasReference($referenceKey, Category::class)) { // Ajoutez Category::class comme deuxième argument
                $categoryReferences[] = $this->getReference($referenceKey, Category::class); // Ajoutez Category::class comme deuxième argument
            }
        }

        // Charger les références des catégories de circuits
        for ($i = 0; $i < 6; $i++) {
            $referenceKey = "category_circuits_$i";
            if ($this->hasReference($referenceKey, Category::class)) { // Ajoutez Category::class comme deuxième argument
                $categoryReferences[] = $this->getReference($referenceKey, Category::class); // Ajoutez Category::class comme deuxième argument
            }
        }

        if (empty($categoryReferences)) {
            throw new \RuntimeException('Aucune catégorie disponible pour associer aux activités.');
        }

        // Créer 50 activités
        for ($i = 0; $i < 50; $i++) {
            $activity = new Activity();
            $activity->setName($faker->sentence(3))
                ->setDescription($faker->paragraph)
                ->setState($states[array_rand($states)])
                ->setSlug($faker->unique()->slug);

            // Associer aléatoirement entre 1 et 3 catégories
            $randomCategories = $faker->randomElements($categoryReferences, rand(1, 3));
            foreach ($randomCategories as $category) {
                $activity->addCategory($category);
            }

            $manager->persist($activity);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }
}