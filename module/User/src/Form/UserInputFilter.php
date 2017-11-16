<?php

namespace User\Form;

use Zend\Filter\StringToLower;
use Zend\Filter\StringTrim;
use Zend\Filter\ToInt;
use Zend\Filter\ToNull;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;

class UserInputFilter extends InputFilter
{
    public function init()
    {
        $this->add([
            'name'       => 'id',
            'required'   => false,
            'filters'    => [
                ['name' => ToInt::class],
                ['name' => ToNull::class],
            ]
        ]);

        $this->add([
            'name'       => 'email',
            'required'   => true,
            'filters'    => [
                ['name' => StringToLower::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                ['name' => EmailAddress::class],
            ],
        ]);
    }
}