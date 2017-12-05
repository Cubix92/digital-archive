<?php

namespace Application\Form;

use Zend\Form\Form;

class CategoryForm extends Form
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
            'name' => 'name',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Nazwa'
            ]
        ]);

        $this->add([
            'name' => 'icon',
            'type' => 'select',
            'attributes' => [
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Kategoria',
                'value_options' => [
                    'ancient-sword' => 'ancient-sword.svg',
                    'castle' => 'castle.svg',
                    'chess-knight' => 'chess-knight.svg',
                    'f1-car' => 'f1-car.svg',
                    'hill-fort' => 'hill-fort.svg',
                    'joystick' => 'joystick.svg',
                    'jump-across' => 'jump-across.svg',
                    'light-backpack' => 'light-backpack.svg',
                    'light-bulb' => 'light-bulb.svg',
                    'mine-explosion' => 'mine-explosion.svg',
                    'pistol-gun' => 'pistol-gun.svg',
                    'podium-winner' => 'podium-winner.svg',
                    'revolt' => 'revolt.svg',
                    'shambling-mound' => 'shambling-mound.svg',
                    'starfighter' => 'starfighter.svg',
                    'sword-spade' => 'sword-spade.svg',
                    'treasure-map' => 'treasure-map.svg'
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