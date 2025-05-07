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
        $post = $this->create($dto);
        $this->om->persist($post);
        $this->om->flush();

        return [
            'id' => $post->getId(),
        ];
    }

    public function updatePost(Post $post, CreatePostDto $dto)
    {
        $post->update($dto->getTitle(), $dto->getContent());
        $this->om->flush();

        return $post;
    }

    public function deletePost(Post $post)
    {
        $this->om->remove($post);
        $this->om->flush();

        return $post;
    }

    public function getPosts()
    {
        $qb = $this->em->getRepository(Post::class)->createQueryBuilder();
        $qb->select('p', 'u')
            ->from(Post::class, 'p')
            ->join('p.user', 'u');

        $posts = $qb->getQuery()->getResult();
    }

    private function create(CreatePostDto $dto)
    {
        return new Post(
            $dto->getUser(),
            $dto->getTitle(),
            $dto->getContent()
        );
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