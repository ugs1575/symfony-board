<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Exception\ValidationFailedException;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BaseRestController
 * @package AppBundle\Controller
 * @see http://symfony.com/doc/current/bundles/FOSRestBundle/5-automatic-route-generation_single-restful-controller.html#implicit-resource-name-definition
 */
class BaseRestController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @param $className
     * @return ObjectRepository
     */
    protected function getRepository($className)
    {
        return $this->getDoctrine()->getManager()->getRepository($className);
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    protected function getManager()
    {
        return $this->getDoctrine()->getManager();
    }

    protected function validate($object)
    {
        $errors = $this->get('validator')->validate($object);
        if (count($errors) > 0) {
            throw new ValidationFailedException($errors);
        }
    }

    /**
     * @param Request $request
     * @param string $classType
     * @return object
     */
    protected function convert(Request $request, $classType)
    {
        $object = new $classType();
        foreach (array_keys($request->request->all()) as $name) {
            if (property_exists($object, $name)) {
                $object->$name = $request->get($name);
            }
        }
        return $object;
    }

    protected function authenticationDecode(Request $request)
    {
        if ($request->headers->get('authorization')) {
            $token = str_replace("Bearer ", "", $request->headers->get('authorization'));

            $data = $this->get('lexik_jwt_authentication.encoder')->decode($token);
            return $this->getRepository(User::class)->findOneBy(['email' => $data['username']]);
//            return $this->getRepository(User::class)->findOneBy(['email' => $data['username']])->getId();
        } else {
            $user = new User();
            return $user;
        }

    }

    /**
     * @param $callback
     * @param $time
     * @param $cacheItem
     * @return mixed
     */
    protected function getItemsAfterCached($callback, $time, CacheItem $cacheItem)
    {
        $items = $callback();

        $cacheItem->set($items);
        $cacheItem->expiresAfter($time);
        $this->get('cache.app')->save($cacheItem);
        return $items;
    }

    /**
     * @return User
     * @throws \Doctrine\ORM\ORMException
     */
    protected function getSystemUser()
    {
        /** @var User $user */
        $user = $this->get('doctrine.orm.entity_manager')->getReference(User::class, 20);

        return $user;
    }

    /**
     * [OPTIONS] /articles.
     */
//    public function optionsAction()
    /**
     * [GET] /articles.
     */
//    public function cgetAction(ConstraintViolationList $errors)

    /**
     * [GET] /articles/{slug}.
     *
     * @param $slug
     */
//    public function getAction($slug)

    /**
     * [POST] /articles.
     */
//    public function cpostAction()

    /**
     * [PATCH] /articles.
     */
//    public function cpatchAction()

    /**
     * [PUT] /articles/{slug}.
     *
     * @param $slug
     */
//    public function putAction($slug)

    /**
     * [PATCH] /articles/{slug}.
     *
     * @param $slug
     */
//    public function patchAction($slug)

    /**
     * [LOCK] /articles/{slug}.
     *
     * @param $slug
     */
//    public function lockAction($slug)

    /**
     * [GET] /articles/{slug}/comments.
     *
     * @param $slug
     */
//    public function getCommentsAction($slug)

    /**
     * [GET] /articles/{slug}/comments/{id}.
     *
     * @param $slug
     * @param $id
     */
//    public function getCommentAction($slug, $id)

    /**
     * [DELETE] /articles/{slug}/comments/{id}.
     *
     * @param $slug
     * @param $id
     */
//    public function deleteCommentAction($slug, $id)

    /**
     * [PATCH] /articles/{slug}/ban.
     *
     * @param $slug
     * @param $id
     */
//    public function banAction($slug, $id)

    /**
     * [POST] /articles/{slug}/comments/{id}/vote.
     *
     * @param $slug
     * @param $id
     */
//    public function postCommentVoteAction($slug, $id)
    /**
     * NO route.
     */
//    public function _articlebarAction()

    /**
     * [GET] /articles/check_articlename.
     */
//    public function check_articlenameAction()

    // conventional HATEOAS actions below

    /**
     * [GET] /articles/new.
     */
//    public function newAction()

    /**
     * [GET] /article/{slug}/edit.
     *
     * @param $slug
     */
//    public function editAction($slug)

    /**
     * [GET] /article/{slug}/remove.
     *
     * @param $slug
     */
//    public function removeAction($slug)

    /**
     * [GET] /articles/{slug}/comments/new.
     *
     * @param $slug
     */
//    public function newCommentAction($slug)

    /**
     * [GET] /articles/{slug}/comments/{id}/edit.
     *
     * @param $slug
     * @param $id
     */
//    public function editCommentAction($slug, $id)

    /**
     * [GET] /articles/{slug}/comments/{id}/remove.
     *
     * @param $slug
     * @param $id
     */
//    public function removeCommentAction($slug, $id)

    /**
     * [PATCH] /articles/{articleId}/comments/{commentId}/hide.
     *
     * @param $articleId
     * @param $commentId
     */
//    public function hideCommentAction($articleId, $commentId)

    // Parameter of type Request should be ignored

    /**
     * [GET] /articles/{slug}/votes.
     *
     * @param Request $request
     * @param $slug
     */
//    public function getVotesAction(Request $request, $slug)

    /**
     * [GET] /articles/{slug}/votes/{id}.
     *
     * @param Request $request
     * @param $slug
     * @param $id
     */
//    public function getVoteAction(Request $request, $slug, $id)

    /**
     * [GET] /articles/{slug}/foos.
     *
     * @param $slug
     * @param Request $request
     */
//    public function getFoosAction($slug, Request $request)

}