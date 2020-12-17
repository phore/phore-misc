<?php


namespace Phore\Misc\UniDB;


class Result
{
    /**
     * The original PDOStatement
     *
     * @var \PDOStatement
     */
    public $pdoStatement;

    private $firstCell = null;

    public function __construct (\PDOStatement $PDOStatement)
    {
        $this->pdoStatement = $PDOStatement;
    }

    public function fetchAll(int $fetchMode = \PDO::FETCH_ASSOC)
    {
        return $this->pdoStatement->fetchAll($fetchMode);
    }

    public function rowCount()
    {
        return $this->pdoStatement->rowCount();
    }

    public function first()
    {
        return $this->pdoStatement->rowCount();
    }

    public function one()
    {

    }

    public function firstCell()
    {
        return $this->pdoStatement->fetch(\PDO::FETCH_BOTH)[0];
    }

}
