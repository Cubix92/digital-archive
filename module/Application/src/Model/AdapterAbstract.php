<?php

namespace Application\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\AbstractPreparableSql;
use Zend\Db\Sql\Sql;

abstract class AdapterAbstract
{
    protected $sql;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->sql = new Sql($dbAdapter);
    }

    protected function executeStatement(AbstractPreparableSql $command)
    {
        $statement = $this->sql->prepareStatementForSqlObject($command);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface) {
            throw new \RuntimeException(
                'Database error occurred during operation'
            );
        }

        return $result;
    }
}