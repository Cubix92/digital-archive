<?php

namespace Application\Form;

use Application\Model\Category;
use Zend\Form\Form;

class NoteForm extends Form
{
    protected $categories;

    public function __construct(array $categories)
    {
        /** @var Category $category */
        foreach ((array)$categories as $category) {
            $this->categories[$category->getId()] = $category->getName();
        }

        parent::__construct('note');
    }

    public function init()
    {
        $this->setAttribute('method', 'POST');

        $this->add([
            'name' => 'id',
            'type' => 'hidden'
        ]);

        $this->add([
            'name' => 'title',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Tytuł'
            ]
        ]);

        $this->add([
            'name' => 'category_id',
            'type' => 'select',
            'attributes' => [
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Kategoria',
                'value_options' => $this->categories,
            ],
        ]);

        $this->add([
            'name' => 'url',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Url'
            ]
        ]);

        $this->add([
            'name' => 'content',
            'type' => 'textarea',
            'attributes' => [
                'class' => 'form-control',
                'rows' => 10
            ],
            'options' => [
                'label' => 'Treść'
            ]
        ]);

        $this->add([
            'type' => 'collection',
            'name' => 'tags',
            'options' => [
                'label' => 'Powiązane tagi',
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'template_placeholder' => '__index__',
                'target_element' => [
                    'type' => TagFieldset::class,
                ]
            ],
            'attributes' => [
                'id' => 'tag-collection'
            ]
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'class' => 'btn btn-blk',
                'value' => 'Zapisz'
            ]
        ]);
    }
}