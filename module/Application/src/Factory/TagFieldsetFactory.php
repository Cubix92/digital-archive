<?php

namespace Application\Factory;

use Application\Form\TagFieldset;
use Application\Service\Slugger;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class TagFieldsetFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $slugger = $container->get(Slugger::class);

        return new TagFieldset($slugger);
    }
}
