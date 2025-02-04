<?php

namespace App\DataFixtures\Articles;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Articles\Category;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categoriesActivites = ['Randonnée', 'Culture', 'Aventure', 'Sports nautiques', 'Gastronomie', 'Bien-être et détente', 'Safari', 'Excursion en vélo'];
        $categoriesCircuits = ['Voyage organisé', 'Road trip', 'Séjour aventure', 'Circuit culturel', 'Escapade en famille'];

        foreach ($categoriesActivites as $index => $cat) {
            $categorie = new Category();
            $categorie->setName($cat);
            $manager->persist($categorie);
            $this->addReference('category_activities_' . $index, $categorie);
        }

        foreach ($categoriesCircuits as $index => $cat) {
            $categorie = new Category();
            $categorie->setName($cat);
            $manager->persist($categorie);
            $this->addReference('category_circuits_' . $index, $categorie);
        }

        $manager->flush();
    }
}
