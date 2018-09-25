<?php
namespace Framework\Lib\Database;

use \PDO;
use Framework\Lib\Interfaces\PdoDBInterface;

class PdoDB extends Database implements PdoDBInterface
{
    private $connection;
    private $fetchstyle = [
        "default" => PDO::FETCH_OBJ,
        "num" => PDO::FETCH_NUM
    ];
    /**
     * @return PDO
     */
    private function getConnexion()
    {
        if ($this->connection === null) {
            $dsn = 'mysql:host=' . $this->dbHost . ';dbname=' . $this->dbName;
            try {
                $pdo = new PDO($dsn, $this->dbUser, $this->dbPass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                $this->connection = $pdo;
            } catch (PDOException $e) {
                throw new \Exception('Error connecting to host. ' . $e->getMessage(), E_USER_ERROR);
            }
        }
        return $this->connection;
    }
    public function cacheQuery($statement)
    {
        $stmt = $this->getConnexion()->prepare($statement);
        $result = $stmt->execute();

        if (!$result) {
            throw new \Exception('Error executing and caching query: ' . $this->getConnexion()->errorInfo()[0], E_USER_ERROR);
            return -1;
        } else {
            $this->_queryCache[] = $stmt;
            return count($this->_queryCache) - 1;
        }
    }

    /**
     * @param $statement
     * @param bool|false $single
     * @return array|mixed
     */

    public function query($statement, $single = false,$style = null)
    {
        $queryStyle = !is_null($style) ? $this->fetchstyle[$style]: $this->fetchstyle["default"];
        
        $history = ['sql' => $statement];
        if (!$req = $this->getConnexion()->query($statement)) {
            throw new \Exception('Error executing query: ' . $this->connections->errorInfo(), E_USER_ERROR);
        } else {

            $req->setFetchMode($queryStyle);
            if ($single) {
                $datas = $req->fetch();
            } else {
                $datas = $req->fetchAll();
            }
            return $datas;
        }
    }
    /**
     * test if data exist in table
     *
     * @param string $statement
     * @param array $attr
     * @return void
     */
    public function exist(string $statement,array $attr):bool{
        $req = $this->getConnexion()->prepare($statement);
        $req->execute($attr);
        return $req->fetchColumn() !== false;
    }

    /**
     * @param $statement
     * @param $attr
     * @param bool|false $single
     * @return array|mixed
     */
    public function prepare($statement, $attr, $single = false, $style = null)
    {
        $queryStyle = !is_null($style) ? $this->fetchstyle[$style]: $this->fetchstyle["default"];
        try {
            $req = $this->getConnexion()->prepare($statement);
            $req->execute($attr);
            if ($single) {
                $datas = $req->fetch();
            } else {
                $datas = $req->fetchAll( $queryStyle);
            }
            return $datas;
        } catch (\Exception $e) {
            echo "<pre>";
            print_r($e->getMessage());
            die();
        }

    }
    public function execute($statement, $attr)
    {
        $req = $this->getConnexion()->prepare($statement);
        return $req->execute($attr);

    }
    public function sanitizeData($data)
    {
        return $this->getConnexion()->real_escape_string($data);
    }
    public function numRowsFromCache($cacheid)
    {
        return $this->queryCache[$cacheid]->rowCount();
    }
    public function resultsFromCache($cacheid)
    {
        return $this->queryCache[$cacheid]->fetch(PDO::FETCH_ASSOC);
    }
    public function cacheData($data)
    {
        $this->_dataCache[] = $data;
        return count($this->_dataCache) - 1;
    }
    public function dataFromCache($cache_id)
    {
        return $this->_dataCache[$cache_id];
    }
    public function affectedRows()
    {
        return $this->getConnexion()->affected_rows;
    }


}