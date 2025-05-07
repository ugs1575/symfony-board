<?php

namespace AppBundle\Controller\Post;

use AppBundle\Controller\BaseRestController;
use AppBundle\Controller\Post\dto\CreatePostDto;
use AppBundle\Controller\Post\type\CreatePostType;
use AppBundle\Entity\Post;
use AppBundle\Entity\Venue;
use Doctrine\ORM\Query;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations\QueryParam;
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
     * @RequestParam(name = "user", requirements="\d+", description="사용자")
     * @RequestParam(name = "title", description="제목")
     * @RequestParam(name = "content", description="내용")
     *
     * @param Post $id
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function putAction(Post $id, ParamFetcher $paramFetcher)
    {
        $form = $this->createForm(CreatePostType::class);
        $form->submit($paramFetcher->all());

        if (!$form->isValid()) {
            return $this->handleView($this->view($form)->setStatusCode(Response::HTTP_BAD_REQUEST));
        }

        /** @var CreatePostDto $form */
        $postDto = $form->getData();
        $response = $this->get('app.service.post')->updatePost($id, $postDto);

        $context = new Context();
        $context->setGroups(["post_detail"]);
        $view = $this->view($response)->setContext($context);
        return $this->handleView($view);
    }

    /**
     * @RequestParam(name = "post", requirements="\d+", description="게시글")
     *
     * @param Post $id
     * @return Response
     */
    public function getAction(Post $id)
    {
        $context = new Context();
        $context->setGroups(["post_detail"]);
        $view = $this->view($id)->setContext($context);
        return $this->handleView($view);
    }

    /**
     * @RequestParam(name = "post", requirements="\d+", description="게시글")
     *
     * @param Post $id
     * @return Response
     */
    public function deleteAction(Post $id)
    {
        $this->get('app.service.post')->deletePost($id);
        return $this->handleView($this->view()->setStatusCode(Response::HTTP_NO_CONTENT));
    }

    /**
     * @QueryParam(name="page", requirements="\d+", default="1", description="Current page")
     * @QueryParam(name="limit", requirements="\d+", default="20", description="Limit page")
     * @QueryParam(name="keyword", nullable=true, strict=true, description="검색어")
     *
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $currentPage = $paramFetcher->get('page');
        $maxPerPage = $paramFetcher->get('limit');
        $filters = array_filter($paramFetcher->all(), function ($v, $k) {
            return !in_array($k, ['page', 'limit']) && $v !== null;
        }, ARRAY_FILTER_USE_BOTH);

        /** @var Query $filteredQuery */
        $filteredQuery = $this->getManager()->getRepository(Post::class)->getFilteredQuery($filters);

        $paginatedCollection = $this->get('pagination_factory')->createByQuery($filteredQuery,
            $currentPage, $maxPerPage, 'get_posts', $paramFetcher->all());

        $context = new Context();
        $context->setGroups(["list", "post_detail"]);
        $view = $this->view($paginatedCollection)->setContext($context);
        return $this->handleView($view);
    }


}