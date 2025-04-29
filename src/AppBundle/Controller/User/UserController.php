<?php

namespace AppBundle\Controller\User;

use AppBundle\Controller\BaseRestController;
use AppBundle\Form\UserType;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseRestController
{
    public function cgetAction()
    {
    } // "get_users"     [GET] /users

    public function getAction($slug)
    {
    } // "get_user"      [GET] /users/{slug}

    public function getCommentsAction($slug)
    {
    } // "get_user_comments"    [GET] /users/{slug}/comments

    /**
     * @RequestParam(name="name", allowBlank=false, description="이름")
     * @RequestParam(name="email", allowBlank=false, description="이메일")
     * @RequestParam(name="password", allowBlank=false, description="패스워드")
     *
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function postAction(ParamFetcherInterface $paramFetcher)
    {
        $form = $this->createForm(UserType::class);
        $form->submit($paramFetcher->all());

        if (!$form->isValid()) {
            return $this->handleView($this->view($form)->setStatusCode(Response::HTTP_BAD_REQUEST));
        }

        return $this->handleView($this->view($form)->setStatusCode(Response::HTTP_CREATED));
//        $form->submit()
        //todo : 검증먼저 해보기 email type
    }
}