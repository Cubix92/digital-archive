<?php

namespace Log;

use Zend\Log\Logger;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'service_manager' => [
        'factories' => [
            Model\LogRepository::class => Factory\LogRepositoryFactory::class,
            Model\LogHydrator::class => InvokableFactory::class,
            Logger::class => Factory\LoggerFactory::class
        ],
    ]
];
