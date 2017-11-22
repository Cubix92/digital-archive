<?php

namespace Application\Form;

use Zend\Form\Form;

class NoteForm extends Form
{
    protected $categories;

    public function __construct(array $categories)
    {
        foreach((array)$categories as $category) {
            $this->categories[$category['id']] = $category['name'];
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
            'name' => 'position',
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
            'name' => 'content',
            'type' => 'textarea',
            'attributes' => [
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Treść'
            ]
        ]);

        $this->add([
            'name' => 'category_id',
            'type' => 'select',
            'options' => [
                'label' => 'Kategoria',
                'value_options' => $this->categories,
            ],
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