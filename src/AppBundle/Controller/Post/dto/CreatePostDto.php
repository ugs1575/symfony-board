<?php

namespace AppBundle\Controller\Post\dto;

use AppBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class CreatePostDto
{
    /**
     * @var User
     */
    public $user;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(
     *       min = 1,
     *       max = 50,
     *  )
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(
     *       min = 1,
     *       max = 60000,
     *  )
     */
    private $content;

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }


}