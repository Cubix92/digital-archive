<?php

namespace Application\Controller\Api;

use Application\Model\CategoryCommand;
use Application\Model\CategoryRepository;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class NoteController extends AbstractRestfulController
{
    protected $categoryRepository;

    protected $categoryCommand;

    public function __construct(CategoryRepository $categoryRepository, CategoryCommand $categoryCommand)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryCommand = $categoryCommand;
    }

    public function get($id):JsonModel
    {
        return parent::get($id);
    }

    public function getList():JsonModel
    {
        return parent::getList();
    }

    public function create($data):JsonModel
    {
        return parent::create($data);
    }

    public function update($id, $data):JsonModel
    {
        return parent::update($id, $data);
    }

    public function delete($id):JsonModel
    {
        return parent::delete($id);
    }
}