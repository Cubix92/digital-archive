<?php

namespace Application\Form;

use Zend\Filter\StringTrim;
use Zend\InputFilter\InputFilter;
use Zend\Validator\InArray;
use Zend\Validator\StringLength;

class NoteInputFilter extends InputFilter
{
    protected $categories;

    public function __construct(array $categories)
    {
        $this->categories = $categories;
    }

    public function init()
    {
        $this->add([
            'name' => 'title',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 255
                    ]
                ],
            ],
        ]);

        $this->add([
            'name' => 'category',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => InArray::class,
                    'options' => [
                        'haystack' => $this->categories
                    ]
                ],
            ],
        ]);
    }
}