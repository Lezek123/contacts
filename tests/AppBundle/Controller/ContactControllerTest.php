<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ContactControllerTest
 * Functional test for ContactController
 */
class ContactControllerTest extends WebTestCase
{
    /**
     * Complete test scenario
     */
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();
        $crawler = $client->request('GET', '/en/contact');
        $response = $client->getResponse();
        /*
        if (!$response->isSuccessful()) {
            $block = $crawler->filter('div.text-exception');
            if ($block->count()) {
                $error = $block->text();
                echo $error; die;
            }
        }

        // Create a new entry in the database
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /en/contact/");
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'contact[firstname]' => 'Test',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Edit')->form(array(
            'contact[firstname]' => 'Foo',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="Foo"]')->count(), 'Missing element [value="Foo"]');
        */
    }
}
