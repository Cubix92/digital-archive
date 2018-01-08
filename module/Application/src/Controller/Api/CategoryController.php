<?php

namespace Application\Controller\Api;

use Application\Model\Note;
use Application\Model\CategoryCommand;
use Application\Model\CategoryHydrator;
use Application\Model\CategoryRepository;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class CategoryController extends AbstractRestfulController
{
    protected $categoryRepository;

    protected $categoryCommand;

    protected $categoryHydrator;

    public function __construct(CategoryRepository $categoryRepository, CategoryCommand $categoryCommand, CategoryHydrator $categoryHydrator)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryCommand = $categoryCommand;
        $this->categoryHydrator = $categoryHydrator;
    }

    public function get($id):JsonModel
    {
        try {
            $note = $this->categoryRepository->findById($id);
        } catch(\UnexpectedValueException $e) {
            return new JsonModel([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        $data = $this->categoryHydrator->extract($note);

        return new JsonModel([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function getList():JsonModel
    {
        /**
         * @var Note $note
         */
        $categories = $this->categoryRepository->findAll();
        $data = [];

        foreach($categories as $category) {
            $data[] = $this->categoryHydrator->extract($category);
        }

        return new JsonModel([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function create($data)
    {
        parent::create($data);
    }

    public function update($id, $data)
    {
        parent::update($id, $data);
    }

    public function delete($id)
    {
        parent::delete($id);
    }
}