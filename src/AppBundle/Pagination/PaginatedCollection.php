<?php

namespace AppBundle\Pagination;

use JMS\Serializer\Annotation as Serializer;

class PaginatedCollection
{
    /**
     * 아이템 리스트
     * @var array
     * @Serializer\Type("array")
     * @Serializer\Groups({"list", "mobile", "list_admin", "paging"})
     */
    protected $items;
    /**
     * 총 아이템 갯수
     * @var integer
     * @Serializer\Type("integer")
     * @Serializer\Groups({"list", "mobile", "list_admin", "paging"})
     */
    protected $total;
    /**
     * 현재 페이지의 아이템 갯수
     * @var integer
     * @Serializer\Type("integer")
     * @Serializer\Groups({"list", "mobile", "list_admin", "paging"})
     */
    protected $count;
    /**
     * 현재 페이지
     * @var integer
     * @Serializer\Type("integer")
     * @Serializer\Groups({"list", "mobile", "list_admin", "paging"})
     */
    protected $page;
    /**
     * 페이지당 아이템 갯수
     * @var integer
     * @Serializer\Type("integer")
     * @Serializer\Groups({"list", "mobile", "list_admin", "paging"})
     */
    protected $limit;
    /**
     * 페이지 링크
     * @var array
     * @Serializer\Type("array<string, string>")
     * @Serializer\Groups({"list", "mobile", "list_admin", "paging"})
     */
    protected $_links = [];

    /**
     * @var array
     */
    protected $tags;


    public function addLink($ref, $url)
    {
        $this->_links[$ref] = $url;
    }

    public function getLink($key)
    {
        return $this->_links[$key];
    }

    public function setItems(array $items)
    {
        $this->items = $items;
        $this->count = count($this->items);
    }

    public function setTotal($total)
    {
        $this->total = $total;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags(array $tags)
    {
        $this->tags = $tags;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }
}