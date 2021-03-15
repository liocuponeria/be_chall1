<?php

namespace App\Entity;

class Paginator
{
    /** @var int */
    private $page;

    /** @var int */
    private $size;

    /**
     * @param $page
     * @param $size
     */
    public function __construct($page, $size)
    {
        $this->page = $page;
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     * @return Paginator
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     * @return Paginator
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    public function getOffset()
    {
        return $this->page * $this->size;
    }
}
