<?php

namespace Application\Model;

class Tag
{
    protected $id;

    protected $notes;

    protected $name;

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

    public function getNotes(): array
    {
        return $this->notes;
    }

    public function setNotes(array $notes)
    {
        $this->notes = $notes;
        return $this;
    }

    public function addNote(Note $note)
    {
        $this->notes[] = $note;
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
}