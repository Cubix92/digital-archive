<?php

namespace Application\Form;

use Zend\Form\Form;

class NoteForm extends Form
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
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'class' => 'btn btn-blk',
                'value' => 'Zapisz'
            ]
        ]);
    }
}