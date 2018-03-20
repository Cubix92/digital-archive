<?php

namespace Log\Model;

use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\TableGateway\TableGatewayInterface;

class LogTable
{
    protected $sql;

    protected $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll(): ResultSetInterface
    {
        return $this->tableGateway->select();
    }
}
