<?php
namespace Application\Form;

use Application\Model\Tag;
use Zend\Filter\Callback;
use Zend\Filter\StringToLower;
use Zend\Filter\StringTrim;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Hydrator\ClassMethods as ClassMethodsHydrator;

class TagFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('tags');

        $this->setHydrator(new ClassMethodsHydrator(false));
        $this->setObject(new Tag());

        $this->setAttribute('class', 'col-md-4 margin-top-10');

        $this->add([
            'name' => 'id',
            'type' => 'hidden'
        ]);

        $this->add([
            'name' => 'name',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'name' => [
                'required' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StringToLower::class],
                    [
                        'name' => Callback::class,
                        'options' => [
                            'callback' => function($tag) {
                                var_dump($tag);die;
                            }
                        ]
                    ],
                ]
            ],
        ];
    }
}