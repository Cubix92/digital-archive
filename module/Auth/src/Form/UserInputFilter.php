<?php

namespace Auth\Form;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Filter\StringToLower;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\Filter\ToNull;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Db\NoRecordExists;
use Zend\Validator\EmailAddress;
use Zend\Validator\Identical;
use Zend\Validator\StringLength;

class UserInputFilter extends InputFilter
{
    public $dbAdapter;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function excludeEmail(string $email)
    {
        $this->remove('email');

        $this->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                ['name' => StringToLower::class],
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
            ],
            'validators' => [
                ['name' => EmailAddress::class],
                [
                    'name' => NoRecordExists::class,
                    'options' => [
                        'table'   => 'user',
                        'field'   => 'email',
                        'adapter' => $this->dbAdapter,
                        'exclude' => [
                            'field' => 'email',
                            'value' => $email
                        ]
                    ]
                ]
            ],
        ]);
    }

    public function init()
    {
        $this->add([
            'name' => 'id',
            'required' => false,
            'filters' => [
                ['name' => ToInt::class],
                ['name' => ToNull::class],
            ]
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
                [
                    'name' => NoRecordExists::class,
                    'options' => [
                        'table'   => 'user',
                        'field'   => 'email',
                        'adapter' => $this->dbAdapter,
                    ]
                ]
            ],
        ]);

        $this->add([
            'name' => 'password',
            'required' => true,
            'validators' => [
                [
                    'name' => Identical::class,
                    'options' => [
                        'token' => 'repeat_password'
                    ],
                ],
                [
                    'name' => StringLength::class,
                    'options' => [
                        'min' => 6,
                        'max' => 120
                    ],
                ],
            ]
        ]);

        $this->add([
            'name' => 'repeat_password',
            'required' => false,
            'validators' => [
                [
                    'name' => Identical::class,
                    'options' => [
                        'token' => 'password'
                    ],
                ]
            ]
        ]);
    }
}