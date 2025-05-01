<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Post
 *
 * @ORM\Table(name="posts")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Serializer\Groups({"post_detail"})
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @Serializer\Groups({"post_detail"})
     */
    private $user;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Groups({"post_detail"})
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Groups({"post_detail"})
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"post_detail"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"post_detail"})
     */
    private $updatedAt;

    /**
     * @param User $user
     * @param $title
     * @param $content
     */
    public function __construct(User $user, $title, $content)
    {
        $this->user = $user;
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}

