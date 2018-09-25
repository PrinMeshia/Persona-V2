<?php 
namespace Framework\Lib\Database;

use Psr\Container\ContainerInterface;




/**
 * 
 */
class Database
{
    private $_cacheEnable = false;
    private $_cacheTime = "";
    private $_cacheDir = false;
    private $_queryCache = [];
    private $_dataCache = [];

    /**
     * @param $dbName
     * @param string $dbUser
     * @param string $dbPass
     * @param string $dbHost
     */
    public function __construct(array $data)
    {
        if ($data) {
            $this->dbName = $data['name'];
            $this->dbHost =$data['host'];
            $this->dbUser =$data['user'];
            $this->dbPass =$data['pass']; 
        }
    }

}
