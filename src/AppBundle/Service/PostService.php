<?php

namespace AppBundle\Service;

use AppBundle\Entity\Post;
use Doctrine\Common\Persistence\ObjectManager;

class PostService
{
    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function writePost(Post $post)
    {
        var_dump($post);
        exit;
        $post = $this->createPost($post);
        $this->om->persist($post);
        $this->om->flush();

        return [
            'id' => $post->getId(),
        ];
    }

    private function createPost(Post $post)
    {
        return new Post(
            $post->getUser(),
            $post->getTitle(),
            $post->getContent()
        );
    }
}