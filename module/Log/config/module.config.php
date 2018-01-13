<?php

namespace Log;

use Zend\Log\Logger;

return [
    'service_manager' => [
        'factories' => [
            Model\LogRepository::class => Factory\LogRepositoryFactory::class,
            Logger::class => Factory\LoggerFactory::class
        ],
    ]
];
