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
            $this->pdo->exec(file_get_contents($this->schemaFile));
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


    public function query($table, int $limit=null) : array
    {
        $sql = "SELECT * FROM " . $this->pdo->quote($table);
        if ($limit !== null)
            $sql .= " LIMIT $limit";

        $stmt = $this->pdo->query($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }


}
