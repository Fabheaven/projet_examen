<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Articles\Activity;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ActivityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
           $activity = new Activity();
            $activity->setName($faker->words(4, true)) // Génère un titre de 4 mots
                ->setDescription($faker->realText(200)) // Description plus courte
                ->setPrice($faker->numberBetween(15, 30)) // Prix entre 15€ et 30€
                ->setState(mt_rand(0, 1) ? Activity::STATES[0] : Activity::STATES[1]);
            
            $manager->persist($activity);
        }

        $manager->flush();
    }
}

