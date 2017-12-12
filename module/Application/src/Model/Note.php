<?php

namespace Application\Model;

class Note
{
    protected $id;

    protected $category;

    protected $tags;

    protected $title;

    protected $content;

    protected $url;

    protected $datePublished;

    public function __construct()
    {
        $this->tags = [];
        $this->category = new Category();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function addTag(Tag $tag) {
        $this->tags[] = $tag;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getDatePublished()
    {
        return $this->datePublished;
    }

    public function setDatePublished($datePublished)
    {
        $this->datePublished = $datePublished;
        return $this;
    }
}