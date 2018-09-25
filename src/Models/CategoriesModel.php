<?php
namespace Models;

class CategoriesModel extends \Framework\Lib\Database\Model

{

    public function FindPaginated(int $limit) : array
    {
        return $this->query("SELECT * FROM $this->table LIMIT $limit");
    }
}