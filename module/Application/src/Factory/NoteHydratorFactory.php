<?php

namespace Application\Factory;

use Application\Model\CategoryTable;
use Application\Model\NoteHydrator;
use Interop\Container\ContainerInterface;

class NoteHydratorFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $categoryTable = $container->get(CategoryTable::class);

        return new NoteHydrator($categoryTable);
    }
}
