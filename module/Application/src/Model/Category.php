<?php

namespace Application\Model;

class Category
{
    protected $id;

    protected $notes;

    protected $name;

    protected $icon;

    protected $position;

    public function __construct()
    {
        $this->notes = [];
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

    public function getNotes(): ?array
    {
        return $this->notes;
    }

    public function addNote(Note $note)
    {
        $this->notes[] = $note;
    }

    public function setNotes(array $notes)
    {
        $this->notes = $notes;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }
}