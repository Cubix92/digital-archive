<?php

namespace User\Model;

use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Hydrator\Reflection as ReflectionHydrator;

class GameTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
         $this->tableGateway = $tableGateway;
    }

    public function findByUserId($id)
    {
        $sql = $this->tableGateway->getSql();

        $select = $sql->select()
            ->where(['user_id' => $id]);

        $statement = $sql->prepareStatementForSqlObject($select)->execute();
        $result = $statement;

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new \RuntimeException(sprintf(
                'Failed retrieving user with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet(new ReflectionHydrator(), new Game());
        $resultSet->initialize($result);
        $games = [];

        foreach($resultSet as $game) {
            $games[] = $game;
        }

        if (! $games) {
            throw new \InvalidArgumentException(sprintf(
                'User with identifier "%s" not found.',
                $id
            ));
        }

        return $games;
    }
}
