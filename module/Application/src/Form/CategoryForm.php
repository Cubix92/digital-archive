<?php

namespace Application\Form;

use Zend\Form\Form;

class CategoryForm extends Form
{
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
            'name' => 'name',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Nazwa'
            ]
        ]);

        $this->add([
            'name' => 'icon',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Ikonka'
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