<?php

namespace Auth\Form;

use Zend\Form\Form;

class LoginForm extends Form
{
    public function init()
    {
        $this->setAttribute('method', 'POST');

        $this->add([
            'name' => 'email',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Email'
            ],
            'options' => [
                'label' => 'Nazwa użytkownika'
            ]
        ]);

        $this->add([
            'name' => 'password',
            'type' => 'password',
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Hasło'
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
                'class' => 'login login-submit',
                'value' => 'Zaloguj się'
            ]
        ]);
    }
}