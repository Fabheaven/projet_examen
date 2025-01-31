<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Créer un nouvel utilisateur
        $user = new User();
        $user->setEmail('fab@exemple.fr')
             ->setFirstName('Fabrice')
             ->setLastName('Mouk')
             ->setPhone($faker->phoneNumber)
             ->setAddress($faker->address);

        // Définir un mot de passe par défaut et le hasher
        $defaultPassword = 'Bonjour123'; // Choisissez un mot de passe par défaut approprié
        $hashedPassword = $this->hasher->hashPassword($user, $defaultPassword);
        $user->setPassword($hashedPassword);

        $manager->persist($user);

        for ($i = 0; $i < 10; $i++) {
            $user = new User();

            // Définir les informations de l'utilisateur
            $user->setEmail($faker->email)
                ->setLastName($faker->lastName)
                ->setFirstName($faker->firstName)
                ->setPassword(
                    $this->hasher->hashPassword($user, 'password') // Hash le mot de passe
                )
                ->setPhone($faker->phoneNumber) // Génère un numéro de téléphone aléatoire
                ->setAddress($faker->address);  // Génère une adresse aléatoire

            // Persister l'utilisateur dans la base de données
            $manager->persist($user);
        }

        // Sauvegarder les entités en base
        $manager->flush();
    }
}