<?php

namespace Auth\Controller;

use Application\Form\CategoryForm;
use Application\Model\CategoryTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CategoryController extends AbstractActionController
{
    protected $categoryTable;

    protected $categoryForm;

    public function __construct(CategoryTable $categoryTable, CategoryForm $categoryForm)
    {
        $this->categoryTable = $categoryTable;
        $this->categoryForm = $categoryForm;
    }

    public function indexAction()
    {
        return new ViewModel([
            'categories' => $this->categoryTable->findAll()
        ]);
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $form = $this->categoryForm;

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $category = $form->getData();
                $this->categoryTable->save($category);

                return $this->redirect()->toRoute('category');
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('category', ['action' => 'add']);
        }

        try {
            $category = $this->categoryTable->findById($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('category', ['action' => 'index']);
        }

        $form = $this->categoryForm;
        $form->bind($category);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->categoryTable->save($category);
                return $this->redirect()->toRoute('category', ['action' => 'index']);
            }
        }

        return new ViewModel([
            'category' => $category,
            'form' => $form
        ]);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('category');
        }

        $this->categoryTable->delete($id);

        return $this->redirect()->toRoute('category', ['action' => 'index']);
    }
}
