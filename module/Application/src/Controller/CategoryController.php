<?php

namespace Application\Controller;

use Application\Form\CategoryForm;
use Application\Model\Category;
use Application\Model\CategoryCommand;
use Application\Model\CategoryRepository;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CategoryController extends AbstractActionController
{
    protected $categoryRepository;

    protected $categoryCommand;

    protected $categoryForm;

    public function __construct(CategoryRepository $categoryRepository, CategoryCommand $categoryCommand, CategoryForm $categoryForm)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryCommand = $categoryCommand;
        $this->categoryForm = $categoryForm;
    }

    public function indexAction()
    {
        return new ViewModel([
            'categories' => $this->categoryRepository->findAll()
        ]);
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $form = $this->categoryForm;

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                /** @var Category $category */
                $category = $form->getData();
                $this->categoryCommand->insert($category);
                $this->flashMessenger()->addSuccessMessage('Category was added successfull.');
                return $this->redirect()->toRoute('category');
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->notFoundAction();
        }

        try {
            $category = $this->categoryRepository->findById($id);
        } catch (\Exception $e) {
            $this->flashMessenger()->addSuccessMessage($e->getMessage());
            return $this->redirect()->toRoute('category', ['action' => 'index']);
        }

        $form = $this->categoryForm;
        $form->bind($category);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());
            $this->flashMessenger()->addSuccessMessage('Category was updated successfull.');
            if ($form->isValid()) {
                $this->categoryCommand->update($category);
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
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->notFoundAction();
        }

        try {
            $category = $this->categoryRepository->findById($id);
            $this->categoryCommand->delete($category);
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
            return $this->redirect()->toRoute('category', ['action' => 'index']);
        }

        $this->flashMessenger()->addSuccessMessage('Category was deleted successfull.');
        return $this->redirect()->toRoute('category', ['action' => 'index']);
    }
}
