<?php

namespace Log\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

class LogRepository
{
    protected $sql;

    protected $logHydrator;

    public function __construct(AdapterInterface $dbAdapter, LogHydrator $logHydrator)
    {
        $this->sql = new Sql($dbAdapter);
        $this->logHydrator = $logHydrator;
    }

    public function findAll(): array
    {
        $logs = [];
        $logSelect = new Select('log');
        $result = $this->sql->prepareStatementForSqlObject($logSelect)->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        /**
         * @var Log $log
         */
        foreach ($resultSet->toArray() as $logSet) {
            $logs[] = $this->logHydrator->hydrate($logSet, new Log());
        }

        return $logs;
    }
}
