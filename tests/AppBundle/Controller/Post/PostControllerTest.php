<?php

namespace Tests\AppBundle\Controller\Post;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PostControllerTest extends WebTestCase
{
    private $em;

    protected function setUp()
    {
        self::bootKernel();
        $this->em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function tearDown()
    {
        $this->em->createQuery('DELETE FROM AppBundle:Post')->execute();
    }

    public function testPostPostsAction()
    {
        $client = static::createClient();

        $client->request('POST', '/posts', [
            'title' => 'test title',
            'content' => 'hello world',
            'user' => 1
        ]);

        $this->assertEquals(201, $client->getResponse()->getStatusCode(), 'Response content: ' . $client->getResponse()->getContent());
        $response = $client->getResponse();
        print_r($response->getContent());

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $data);
//        $this->assertSame('테스트 제목', $data['title']);
//        $this->assertSame('테스트 제목', $data['title']);
//        $this->assertSame('테스트 내용', $data['content']);
    }

}
