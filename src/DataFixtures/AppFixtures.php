<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Contact
        for ($i = 0; $i < 5; $i++) {
            $contact = new Contact();
            $contact->setFirstName($faker->firstName())  // Utilisation correcte de la méthode setFirstName
                    ->setLastName($faker->lastName())    // Utilisation correcte de la méthode setLastName
                    ->setEmail($faker->email())         // Utilisation correcte de la méthode setEmail
                    ->setSubject('Demande n°' . ($i + 1))
                    ->setMessage($faker->text());       // Utilisation correcte de la méthode setMessage

            // Persister l'objet Contact dans Doctrine
            $manager->persist($contact);
        }

        // Sauvegarde en base de données
        $manager->flush();
    }
}

 