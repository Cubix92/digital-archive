<?php

namespace Application\Controller\Api;

use Application\Model\NoteCommand;
use Application\Model\NoteRepository;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class NoteController extends AbstractRestfulController
{
    protected $categoryRepository;

    protected $categoryCommand;

    public function __construct(NoteRepository $categoryRepository, NoteCommand $categoryCommand)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryCommand = $categoryCommand;
    }

    public function get($id):JsonModel
    {
        parent::get($id);
    }

    public function getList():JsonModel
    {
        parent::getList();
    }

    public function create($data):JsonModel
    {
        parent::create($data);
    }

    public function update($id, $data):JsonModel
    {
        parent::update($id, $data);
    }

    public function delete($id):JsonModel
    {
        parent::delete($id);
    }
}