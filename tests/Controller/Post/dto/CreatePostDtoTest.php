<?php

namespace Tests\Controller\Post\dto;

use AppBundle\Controller\Post\dto\CreatePostDto;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\String\ByteString;

class CreatePostDtoTest extends KernelTestCase
{
    private static $validator;
    private static $container;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::bootKernel();
        self::$validator = self::$kernel->getContainer()->get('validator');
    }

    public function testBlankTitle()
    {
        $dto = new CreatePostDto();
        $dto->setTitle('');
        $dto->setContent('test');

        $violations = self::$validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertSame('제목을 입력해주세요', $violations[0]->getMessage());
    }

    public function testTitleMinLength()
    {
        $dto = new CreatePostDto();
        $dto->setTitle('a');
        $dto->setContent('test');

        $violations = self::$validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertSame('제목을 최소 2자 이상 입력해주세요.', $violations[0]->getMessage());
    }

    public function testTitleMaxLength()
    {
        $maxLength = 50;
        $dto = new CreatePostDto();
        $dto->setTitle(str_repeat("a", $maxLength + 1));
        $dto->setContent('test');

        $violations = self::$validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertSame('제목은 최대 ' . $maxLength . '자 까지 입력가능합니다.', $violations[0]->getMessage());
    }

    public function testBlankContent()
    {
        $dto = new CreatePostDto();
        $dto->setTitle('test');
        $dto->setContent('');

        $violations = self::$validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertSame('내용을 입력해주세요', $violations[0]->getMessage());
    }

    public function testContentMinLength()
    {
        $dto = new CreatePostDto();
        $dto->setTitle('test');
        $dto->setContent('a');

        $violations = self::$validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertSame('내용을 최소 2자 이상 입력해주세요.', $violations[0]->getMessage());
    }

    public function testContentMaxLength()
    {
        $maxLength = 60000;
        $dto = new CreatePostDto();
        $dto->setTitle('test');
        $dto->setContent(str_repeat("a", $maxLength + 1));

        $violations = self::$validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertSame('내용은 최대 ' . $maxLength . '자 까지 입력가능합니다.', $violations[0]->getMessage());
    }


}
