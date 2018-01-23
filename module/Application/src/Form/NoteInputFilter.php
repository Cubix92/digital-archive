<?php

namespace Application\Form;

use Zend\Filter\File\RenameUpload;
use Zend\Filter\StringTrim;
use Zend\InputFilter\InputFilter;
use Zend\Validator\File\IsImage;
use Zend\Validator\InArray;
use Zend\Validator\StringLength;
use Zend\Validator\Uri;

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

        $this->add([
            'name' => 'url',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => Uri::class,
                    'options' => [
                        'allowRelative' => false
                    ]
                ],
            ],
        ]);

        $this->add([
            'name' => 'image',
            'required' => true,
            'filters' => [
                [
                    'name' => RenameUpload::class,
                    'options' => [
                        'target'    => 'public\upload',
                        'randomize' => true,
                        'use_upload_name' => true,
                    ]
                ],
            ],
            'validators' => [
                ['name' => IsImage::class],
            ],
        ]);
    }
}