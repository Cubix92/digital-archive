<?php

namespace Application\Controller;

use Log\Model\LogTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LogController extends AbstractActionController
{
    protected $logTable;

    public function __construct(LogTable $logTable)
    {
        $this->logTable = $logTable;
    }

    public function indexAction()
    {
        return new ViewModel([
            'logs' => $this->logTable->fetchAll()
        ]);
    }
}