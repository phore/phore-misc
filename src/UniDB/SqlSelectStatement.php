<?php


namespace Phore\Misc\UniDB;


class SqlSelectStatement extends SqlStatement
{
    private $select;
    private $from;

    private $where = [];

    private $limit;
    private $offset;

    private $params = [];

    public function __construct(array $select, string $from)
    {
        $this->select = $select;
        $this->from = $from;
    }

    public function select(array $cols = ["*"]) : self
    {
        $i->select = $cols;
        return $this;
    }

    public function from(string $tables) : self
    {
        $this->from = $tables;
        return $this;
    }

    public function where(string $stmt, array $params=[]) : self
    {
        if (strpos($stmt, "'") !== false) {
            throw new \Exception("SecurityException (Possible SqlInjection): Statement is not allowed to contain \"'\" - use correct escaping strategy!");
        }
        // Wrap statement in brackets to connect them whith
        $this->where[] = " ( " . $stmt . " ) ";
        foreach ($params as $key => $val) {
            if (is_numeric($key)) {
                $this->params[] = $val;
            } else {
                $this->params[$key] = $val;
            }
        }
        return $this;
    }

    public function limit(int $limit) : self
    {
        $this->limit = $limit;
        return $this;
    }

    public function __toString()
    {
        $stmt = "SELECT ";
        $stmt .= implode(",", $this->select) . " ";
        $stmt .= "FROM {$this->from} ";
        if ( ! empty($this->from)) {
            $stmt .= "WHERE ";
            $stmt .= implode(" AND ", $this->where);
        }
        if ($this->limit !== null)
            $stmt .= " LIMIT {$this->limit}";
        return $stmt;
    }

    public function getParams() : array
    {
        return $this->params;
    }

}
