<?php

namespace Tests\Controller\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testUserPost()
    {
        $client = static::createClient();

        $formData = [
            'name' => 'Fabien',
            'email' => 'fabien@gmail.com',
            'password' => '123456',
        ];

        $client->request(
            'POST',
            '/users',
            $formData
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode(), 'Response content: ' . $client->getResponse()->getContent());
    }
}
