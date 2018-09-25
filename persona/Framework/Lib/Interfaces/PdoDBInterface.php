<?php
namespace Framework\Lib\Interfaces;

interface PdoDBInterface 
{

    public function cacheQuery($statement);
    public function query($statement, $single = false);
    public function prepare($statement, $attr, $single = false);
    public function execute($statement, $attr);
    public function sanitizeData($data);
    public function numRowsFromCache($cacheid);
    public function resultsFromCache($cacheid);
    public function cacheData($data);
    public function dataFromCache($cache_id);
    public function affectedRows();
}