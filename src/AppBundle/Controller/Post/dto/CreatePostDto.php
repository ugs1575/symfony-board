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
     * @Assert\NotBlank(message = "제목을 입력해주세요")
     * @Assert\Length(
     *       min = 2,
     *       max = 50,
     *       minMessage = "제목을 최소 {{ limit }}자 이상 입력해주세요.",
     *       maxMessage = "제목은 최대 {{ limit }}자 까지 입력가능합니다."
     *  )
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank(message = "내용을 입력해주세요")
     * @Assert\Length(
     *       min = 2,
     *       max = 60000,
     *       minMessage = "내용을 최소 {{ limit }}자 이상 입력해주세요.",
     *       maxMessage = "내용은 최대 {{ limit }}자 까지 입력가능합니다."
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