<?php

namespace Application\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
class LogRepository
{
    protected $sql;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->sql = new Sql($dbAdapter);
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
        foreach ($resultSet as $log) {
            $log = (new Log())
                ->setId($log['id'])
                ->setContent($log['content'])
                ->setDate(new \DateTime($log['date']))
                ->setType($log['type']);

            $logs[] = $log;
        }

        return $logs;
    }
}
