<?php

namespace Log;

use Zend\Log\Logger;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'service_manager' => [
        'factories' => [
            Model\LogTable::class => Factory\LogTableFactory::class,
            Model\LogHydrator::class => InvokableFactory::class,
            Logger::class => Factory\LoggerFactory::class
        ],
    ]
];
