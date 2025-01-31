<?php

namespace App\Tests\Functional\Articles;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticlesTest extends WebTestCase
{
    public function testDeLaPage(): void
    {
        // Crée un client de test
        $client = static::createClient();

        // Envoie une requête GET à la page d'accueil
        $client->request(Request::METHOD_GET, '/articles/circuits');

        // Vérifie que la réponse est OK (code 200)
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Vérifie que l'élément <h1> existe sur la page
        $this->assertSelectorExists('h1');

        // Vérifie que l'élément <h1> contient le texte 'activités'
        $this->assertSelectorTextContains('h1', 'activités');
    }
}
