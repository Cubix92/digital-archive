<?php

namespace Auth\Form;

use Zend\Form\Form;

class UserForm extends Form
{
    public function init()
    {
        $this->setAttribute('method', 'POST');

        $this->add([
            'name' => 'id',
            'type' => 'hidden'
        ]);

        $this->add([
            'name' => 'email',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Nazwa użytkownika'
            ]
        ]);

        $this->add([
            'name' => 'password',
            'type' => 'password',
            'attributes' => [
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Hasło użytkownika'
            ]
        ]);

        $this->add([
            'type' => 'csrf',
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600,
                ],
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