<?php

namespace AppBundle\Service;

use AppBundle\Controller\Post\dto\CreatePostDto;
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

    public function writePost(CreatePostDto $dto)
    {
        $post = $this->createPost($dto);
        $this->om->persist($post);
        $this->om->flush();

        return [
            'id' => $post->getId(),
        ];
    }

    private function createPost(CreatePostDto $dto)
    {
        return new Post(
            $dto->getUser(),
            $dto->getTitle(),
            $dto->getContent()
        );
    }
}