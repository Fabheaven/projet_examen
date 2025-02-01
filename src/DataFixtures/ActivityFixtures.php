<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Articles\Activity;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ActivityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $slugger = new AsciiSlugger(); // Pour générer un slug unique
    
        for ($i = 0; $i < 20; $i++) {
            $name = $faker->words(4, true); // Génère un titre
            $activity = new Activity();
            $activity->setName($name)
                ->setSlug($slugger->slug($name . '-' . uniqid())->lower()) // Génère un slug unique
                ->setDescription($faker->realText(200))
                ->setPrice($faker->numberBetween(15, 30))
                ->setState(mt_rand(0, 1) ? Activity::STATES[0] : Activity::STATES[1]);
    
            $manager->persist($activity);
        }
    
        $manager->flush();
    }
}

