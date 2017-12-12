<?php

namespace Auth\Form;

use Zend\Authentication\AuthenticationService as AuthService;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;
use Zend\Authentication\Validator\Authentication as AuthenticationValidator;
use Zend\Filter\StringToLower;
use Zend\Filter\StringTrim;
use Zend\Filter\ToInt;
use Zend\Filter\ToNull;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;
use Zend\Validator\Identical;

class LoginInputFilter extends InputFilter
{
    protected $authService;

    protected $authAdapter;

    public function __construct(AuthService $authService, AuthAdapter $authAdapter)
    {
        $this->authService = $authService;
        $this->authAdapter = $authAdapter;
    }

    public function init()
    {
        $this->add([
            'name' => 'csrf',
            'required' => true
        ]);

        $this->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                ['name' => StringToLower::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                ['name' => EmailAddress::class],
            ],
        ]);

        $this->add([
            'name' => 'password',
            'required' => true,
            'validators' => [
                [
                    'name' => AuthenticationValidator::class,
                    'options' => [
                        'identity' => 'email',
                        'credential' => 'password',
                        'service' => $this->authService,
                        'adapter' => $this->authAdapter
                    ],
                ]
            ]
        ]);
    }
}