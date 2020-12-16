<?php


namespace Phore\Misc\UniDB;


class SQLiteDriver
{
    protected $dbFile;
    protected $schemaFile;

    /**
     * @var \PDO
     */
    public $pdo;

    /**
     * @var string
     */
    public $lastQueryRaw;
    
    public function __construct($filename)
    {
        $this->dbFile = $filename;
    }


    public function setSchemaFile($filename)
    {
        $this->schemaFile = $filename;
        return $this;
    }

    public function connect()
    {
        $createSchema = false;
        if ( ! file_exists($this->dbFile))
            $createSchema = true;
        $this->pdo = new \PDO("sqlite:$this->dbFile", null, null, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]);
        if ($createSchema && $this->schemaFile !== null) {
            try {
                $this->pdo->exec(file_get_contents($this->schemaFile));
            } catch (\PDOException $e) {
                print_r($this->pdo->errorInfo());
                throw $e;

            }
        }
    }

    public function insert(string $table, array $data)
    {

        $keys = [];
        $vals = [];

        foreach ($data as $key => $value) {
            $keys[] = $this->pdo->quote($key);
            $vals[] = $this->pdo->quote($value);
        }

        $sql = "INSERT INTO " . $this->pdo->quote($table) . " (" . implode(",", $keys) . ") VALUES (" . implode(",", $vals) . ")";
        //echo $sql;
        $this->pdo->exec($sql);
    }


    public function queryRaw($sql)
    {
        $stmt = $this->pdo->query($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function query($stmt, array $params=[]) : array
    {
        if (!is_string($stmt) && !$stmt instanceof SqlStatement)
            throw new \InvalidArgumentException("Parameter 1 must be string or SqlStatement");

        $prepare = $this->pdo->prepare($stmt->__toString());
        if ($stmt instanceof SqlStatement) {
            $params = $stmt->getParams();
        }

        $prepare->execute($params);
        $this->lastQueryRaw = $prepare->queryString;
        return $prepare->fetchAll(\PDO::FETCH_ASSOC);

    }


}
