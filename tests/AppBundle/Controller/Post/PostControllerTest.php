<?php

namespace Tests\AppBundle\Controller\Post;

use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PostControllerTest extends WebTestCase
{
    private static $em;

    private static $user;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::bootKernel();
        self::$em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $user = new User();
        self::$em->persist($user);
        self::$em->flush();

        self::$user = $user;
    }

    public function tearDown()
    {
        self::$em->createQuery('DELETE FROM AppBundle:Post')->execute();
    }

    public function testPostAction()
    {
        //when
        $client = static::createClient();

        $client->request('POST', '/posts', [
            'title' => 'test title',
            'content' => 'hello world',
            'user' => self::$user->getId()
        ]);

        //then
        $clientResponse = $client->getResponse();
        $response = json_decode($clientResponse->getContent(), true);

        $this->assertEquals(Response::HTTP_CREATED, $clientResponse->getStatusCode());

        $this->assertArrayHasKey('id', $response);

        $post = self::$em->getRepository('AppBundle:Post')->find($response['id']);
        $this->assertEquals('test title', $post->getTitle());
        $this->assertEquals('hello world', $post->getContent());
        $this->assertEquals(self::$user->getId(), $post->getUser()->getId());
    }

    public function testPutAction()
    {
        //given
        $user = self::$em->getRepository(User::class)->find(self::$user->getId());
        $post = new Post($user, "test title", "hello world");
        self::$em->persist($post);
        self::$em->flush();

        //when
        $client = static::createClient();

        $client->request('PUT', '/posts/' . $post->getId(), [
            'title' => 'test title changed',
            'content' => 'hello world changed',
            'user' => $user->getId()
        ]);

        //then
        $clientResponse = $client->getResponse();
        $response = json_decode($clientResponse->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $clientResponse->getStatusCode());

        $this->assertEquals($post->getId(), $response['id']);
        $this->assertEquals('test title changed', $response['title']);
        $this->assertEquals('hello world changed', $response['content']);
        $this->assertEquals($user->getId(), $response['user']['id']);
    }

    public function testGetAction()
    {
        //given
        $user = self::$em->getRepository(User::class)->find(self::$user->getId());
        $post = new Post($user, "test title", "hello world");
        self::$em->persist($post);
        self::$em->flush();

        //when
        $client = static::createClient();

        $client->request('GET', '/posts/' . $post->getId());

        //then
        $clientResponse = $client->getResponse();
        $response = json_decode($clientResponse->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $clientResponse->getStatusCode());

        $this->assertEquals($post->getId(), $response['id']);
        $this->assertEquals('test title', $response['title']);
        $this->assertEquals('hello world', $response['content']);
        $this->assertEquals($user->getId(), $response['user']['id']);
    }

    public function testDeleteAction()
    {
        //given
        $user = self::$em->getRepository(User::class)->find(self::$user->getId());
        $post = new Post($user, "test title", "hello world");
        self::$em->persist($post);
        self::$em->flush();

        //when
        $client = static::createClient();

        $client->request('DELETE', '/posts/' . $post->getId());

        //then
        $clientResponse = $client->getResponse();
        $response = json_decode($clientResponse->getContent(), true);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $clientResponse->getStatusCode());

        $posts = self::$em->getRepository('AppBundle:Post')->findAll();
        $this->assertEmpty($posts);
    }

}
