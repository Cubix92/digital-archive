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
        $form = $this->categoryForm;

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());

            if ($form->isValid()) {
                /** @var Category $category */
                $category = $form->getData();
                $this->categoryCommand->insert($category);
                $this->getEventManager()->trigger('categoryAdded');
                $this->flashMessenger()->addSuccessMessage('Category was added successfull');
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
        } catch (\UnexpectedValueException $e) {
            $this->flashMessenger()->addErrorMessage('Category with identifier not found');
            return $this->redirect()->toRoute('category', ['action' => 'index']);
        }

        $form = $this->categoryForm->bind($category);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());

            if ($form->isValid()) {
                $this->categoryCommand->update($category);
                $this->getEventManager()->trigger('categoryEdited');
                $this->flashMessenger()->addSuccessMessage('Category was updated successfull');
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
        } catch (\InvalidArgumentException $e) {
            $this->flashMessenger()->addErrorMessage('Category with identifier not found');
            return $this->redirect()->toRoute('category', ['action' => 'index']);
        }

        $this->categoryCommand->delete($category);
        $this->getEventManager()->trigger('categoryDeleted');
        $this->flashMessenger()->addSuccessMessage('Category was deleted successfull');
        return $this->redirect()->toRoute('category', ['action' => 'index']);
    }
}