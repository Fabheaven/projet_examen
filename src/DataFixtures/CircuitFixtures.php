<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\Articles\Circuit;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CircuitFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $circuit = new Circuit();
            $circuit->setName($faker->words(4, true)) // Génère un titre de 4 mots
                ->setDescription($faker->realText(200)) // Description plus courte
                ->setPrice($faker->numberBetween(10, 50)) // Prix entre 10€ et 50€
                ->setDuration($this->generateDuration($faker)) // Durée en heures ou jours
                ->setAvailability(mt_rand(0, 1) ? Circuit::AVAILABLES[0] : Circuit::AVAILABLES[1])
                ->setState(mt_rand(0, 1) ? Circuit::STATES[0] : Circuit::STATES[1]);
            
            $manager->persist($circuit);
        }

        $manager->flush();
    }

    private function generateDuration($faker): string
    {
        $type = mt_rand(0, 1); // 0 = heures, 1 = jours
        if ($type === 0) {
            return $faker->numberBetween(1, 12) . ' heures'; // Durée entre 1 et 12 heures
        } else {
            return $faker->numberBetween(1, 10) . ' jours'; // Durée entre 1 et 10 jours
        }
    }
}
