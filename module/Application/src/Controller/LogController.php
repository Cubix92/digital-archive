<?php

namespace Application\Controller;

use Log\Model\LogRepository;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LogController extends AbstractActionController
{
    protected $logRepository;

    public function __construct(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    public function indexAction()
    {
        return new ViewModel([
            'logs' => $this->logRepository->findAll()
        ]);
    }
}