<?php

namespace AppBundle\Controller\Post;

use AppBundle\Controller\BaseRestController;
use AppBundle\Controller\Post\dto\CreatePostDto;
use AppBundle\Controller\Post\type\CreatePostType;
use AppBundle\Entity\Post;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;


class PostController extends BaseRestController
{
    /**
     * @RequestParam(name = "user", requirements="\d+", description="사용자")
     * @RequestParam(name = "title", description="제목")
     * @RequestParam(name = "content", description="내용")
     *
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function postAction(ParamFetcher $paramFetcher)
    {
        $form = $this->createForm(CreatePostType::class);
        $form->submit($paramFetcher->all());

        if (!$form->isValid()) {
            return $this->handleView($this->view($form)->setStatusCode(Response::HTTP_BAD_REQUEST));
        }

        /** @var CreatePostDto $form */
        $postDto = $form->getData();
        $response = $this->get('app.service.post')->writePost($postDto);

        return $this->handleView($this->view($response))->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @RequestParam(name = "post", requirements="\d+", description="게시글")
     *
     * @param Post $id
     * @return Response
     */
    public function getAction(Post $id)
    {
//        print_r($id->getTitle());
//        exit;
        $context = new Context();
        $context->setGroups(["post_detail"]);
        $view = $this->view($id)->setContext($context);
        return $this->handleView($view);
    }

    public function cgetAction()
    {
    } // "get_users"     [GET] /users


}