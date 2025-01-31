<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ContactFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Génération de 5 contacts
        for ($i = 0; $i < 5; $i++) {
            $contact = new Contact();
            $contact->setFirstName($faker->firstName())
                    ->setLastName($faker->lastName())
                    ->setEmail($faker->unique()->safeEmail()) // Assure l'unicité
                    ->setSubject('Demande n°' . ($i + 1))
                    ->setMessage($faker->text());

            // Vérifier si l'entité a une date de création
            if (method_exists($contact, 'setCreatedAt')) {
                $contact->setCreatedAt(new \DateTimeImmutable());
            }

            // Persister l'objet Contact
            $manager->persist($contact);
        }

        // Enregistrement en base de données
        $manager->flush();
    }
}
