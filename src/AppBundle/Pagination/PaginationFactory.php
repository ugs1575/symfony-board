<?php

namespace AppBundle\Pagination;

use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Routing\RouterInterface;

class PaginationFactory
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var PaginatedCollection
     */
    private $collection;

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * PaginationFactory constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param $className
     * @return $this
     */
    public function setType($className)
    {
        $this->collection = new $className();
        return $this;
    }

    /**
     * @param array $tags
     * @return $this
     */
    public function setTags(array $tags)
    {
        $this->collection->setTags($tags);
        return $this;
    }

    /**
     * @param array $items
     * @param $currentPage
     * @param $maxPerPage
     * @param $routeName
     * @param array $routeParams
     * @param mixed $callback
     * @return PaginatedCollection
     */
    public function createByArray(
        array $items,
        $currentPage,
        $maxPerPage,
        $routeName,
        array $routeParams,
        $callback = null
    )
    {
        $pagerfanta = new Pagerfanta(new ArrayAdapter($items));
        $pagerfanta->setMaxPerPage($this->limitMaxPerPage($maxPerPage, $routeName));
        $pagerfanta->setCurrentPage($currentPage);

        return $this->create($pagerfanta, $routeName, $routeParams, $callback);
    }

    /**
     * @param $query
     * @param $currentPage
     * @param $maxPerPage
     * @param $routeName
     * @param array $routeParams
     * @param mixed $callback
     * @param bool $fetchJoinCollection
     * @param bool $useOutputWalkers
     * @return PaginatedCollection
     */
    public function createByQuery(
        $query,
        $currentPage,
        $maxPerPage,
        $routeName,
        array $routeParams,
        $callback = null,
        $fetchJoinCollection = true,
        $useOutputWalkers = false
    )
    {
        $pagerfanta = new Pagerfanta(new DoctrineORMAdapter($query, $fetchJoinCollection, $useOutputWalkers));
        $pagerfanta->setMaxPerPage($this->limitMaxPerPage($maxPerPage, $routeName));
        $pagerfanta->setCurrentPage($currentPage);

        return $this->create($pagerfanta, $routeName, $routeParams, $callback);
    }

    public function createBy(
        $currentPage,
        $maxPerPage,
        $routeName,
        array $routeParams,
        $callback = null
    )
    {
        $pagerfanta = new Pagerfanta($this->adapter);
        $pagerfanta->setMaxPerPage($this->limitMaxPerPage($maxPerPage, $routeName));
        $pagerfanta->setCurrentPage($currentPage);

        return $this->create($pagerfanta, $routeName, $routeParams, $callback);
    }

    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    private function limitMaxPerPage($maxPerPage, $routeName)
    {
        if (preg_match('/^site/', $routeName)) {
            return 100 < (int)$maxPerPage ? 100 : $maxPerPage;
        }

        return $maxPerPage;
    }

    /**
     * @param Pagerfanta $pagerfanta
     * @param $routeName
     * @param array $routeParams
     * @param $callback
     * @return PaginatedCollection
     */
    private function create(
        Pagerfanta $pagerfanta,
        $routeName,
        array $routeParams,
        $callback
    )
    {
        $items = [];
        foreach ($pagerfanta->getIterator() as $result) {
            if ($callback) {
                if ($result = $callback($result)) {
                    $items[] = $result;
                }
            } else {
                $items[] = $result;
            }
        }

        $paginatedCollection = $this->collection;

        if ($paginatedCollection === null) {
            $paginatedCollection = new PaginatedCollection();
        }

        $paginatedCollection->setItems($items);
        $paginatedCollection->setTotal($pagerfanta->getNbResults());
        $paginatedCollection->setPage($pagerfanta->getCurrentPage());
        $paginatedCollection->setLimit($pagerfanta->getMaxPerPage());

        $createLinkUrl = function ($targetPage) use ($routeName, $routeParams) {
            return $this->router->generate($routeName, array_merge(
                $routeParams,
                ['page' => $targetPage]
            ));
        };

        $paginatedCollection->addLink('self', $createLinkUrl($pagerfanta->getCurrentPage()));
        $paginatedCollection->addLink('first', $createLinkUrl(1));
        $paginatedCollection->addLink('last', $createLinkUrl($pagerfanta->getNbPages()));
        if ($pagerfanta->hasNextPage()) {
            $paginatedCollection->addLink('next', $createLinkUrl($pagerfanta->getNextPage()));
        }
        if ($pagerfanta->hasPreviousPage()) {
            $paginatedCollection->addLink('prev', $createLinkUrl($pagerfanta->getPreviousPage()));
        }

        return $paginatedCollection;
    }

}