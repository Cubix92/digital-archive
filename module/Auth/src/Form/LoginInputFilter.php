<?php

namespace Auth\Form;

use Zend\Filter\StringToLower;
use Zend\Filter\StringTrim;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;

class LoginInputFilter extends InputFilter
{
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
            'required' => true
        ]);
    }
}