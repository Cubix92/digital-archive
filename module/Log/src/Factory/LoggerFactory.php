<?php

namespace Log\Factory;

use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Log\Logger;
use Zend\Log\Processor\PsrPlaceholder;
use Zend\Log\Writer\Db;

class LoggerFactory
{
    protected $mapping = [
        'timestamp' => 'date',
        'priority'  => 'type',
        'message'   => 'content',
    ];

    public function __invoke(ContainerInterface $container)
    {
        $dbAdapter = $container->get(AdapterInterface::class);
        $psrPlaceholder = new PsrPlaceholder();

        $writer = (new Db($dbAdapter, 'log', $this->mapping))
            ->setFormatter(new \Zend\Log\Formatter\Db('Y-m-d H:i:s'));

        $logger = (new Logger())
            ->addWriter($writer)
            ->addProcessor($psrPlaceholder);

        return $logger;
    }
}
