<?php 
namespace Framework\Lib\Database;

use Psr\Container\ContainerInterface;
use Framework\Lib\Interfaces\PdoDBInterface;
use Framework\Lib\Exceptions\NoRecordException;

/**
 * 
 */
class Model
{
	protected $table;
	protected $entity;
	protected $db;
	protected $query;
	protected $cache;
	public function __construct(PdoDBInterface $pdo)
	{
		$this->db = $pdo;
		if (is_null($this->table)) {
			$parts = explode('\\', get_class($this));
			$className = str_replace(["Model", "model"], "", end($parts));
			$this->table = $className;
		}
	}
	/**
	 * Get the value of entity
	 */
	public function getEntity() : string
	{
		return $this->entity;
	}

	/**
	 * Get the value of table
	 */
	public function getTable() : string
	{
		return $this->table;
	}
	/**
	 * Undocumented function
	 *
	 * @param string $statement
	 * @param array $attr
	 * @param boolean $single
	 * @param [type] $style
	 * @return void
	 */
	public function query(string $statement, array $attr = [], bool $single = false, $style = null)
	{

		if (!empty($attr)) {
			return $this->db->prepare($statement, $attr, $single, $style);
		} else {
			return $this->db->query($statement, $single, $style);
		}
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function getAll()
	{
		$record = $this->query('SELECT * FROM ' . $this->table);
		if($record === false){
			throw new NoRecordException();
		}
		return $record;
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function count()
	{
		$record = $this->query('SELECT count(id) FROM ' . $this->table,[],true,"num");
		if($record === false){
			throw new NoRecordException();
		}
		return $record[0];
	}
	/**
	 * Undocumented function
	 *
	 * @param string $key
	 * @param string $elem
	 * @return void
	 */
	public function find(string $key, string $elem)
	{
		$record =  $this->query('SELECT * FROM ' . $this->table . ' WHERE ' . $key . ' = ?', [$elem], true);
		if($record === false){
			throw new NoRecordException();
		}
		return $record;
	}
	/**
	 * Undocumented function
	 *
	 * @param string $key
	 * @param string $elem
	 * @return void
	 */
	public function findLike(string $key, string $elem)
	{
		$record =  $this->query('SELECT * FROM ' . $this->table . ' WHERE ' . $key . ' like ?', ['%' . $elem . '%']);
		if($record === false){
			throw new NoRecordException();
		}
		return $record;
	}
	/**
	 * Undocumented function
	 *
	 * @param integer $id
	 * @param array $params
	 * @return boolean
	 */
	public function update(int $id, array $params) : bool
	{
		$fieldsQuery = $this->buildFieldQuery($params);
		$params["id"] = $id;
		return $this->db->execute("UPDATE $this->table SET $fieldsQuery where id=:id", $params);
	}
	/**
	 * Undocumented function
	 *
	 * @param array $params
	 * @return boolean
	 */
	public function insert(array $params) : bool
	{
		$fieldsQuery = $this->parseParam($params);
		return $this->db->execute("INSERT INTO {$this->table} (" . join(',', $fieldsQuery['fields']) . ") VALUES (" . join(',', $fieldsQuery['values']) . ")", $params);
	}
	/**
	 * Undocumented function
	 *
	 * @param integer $id
	 * @return boolean
	 */
	public function delete(int $id) : bool
	{
		return $this->db->execute("DELETE FROM $this->table where id = ?", [$id]);
	}
	/**
	 * Undocumented function
	 *
	 * @param array $fields
	 * @return array
	 */
	public function findList(array $fields) : array
	{
		$listfield = join(", ", $fields);
		$record = $this->db->query("SELECT {$listfield} from {$this->table}",false,"num");
		if($record === false){
			throw new NoRecordException();
		}
		$list = [];
		foreach ($record as $item) {
			$list[$item[0]] = $item[1];
		}

		return $list;
	}
	/**
	 * Undocumented function
	 *
	 * @param string $id
	 * @return boolean
	 */
	public function exist(string $id):bool{
		return $this->db->exist("select id from {$this->table} where id = ?",[$id]);
	}
/**
 * Undocumented function
 *
 * @param [type] $params
 * @return array
 */
	private function parseParam($params) : array
	{
		$fields = array_keys($params);
		$values = array_map(function ($field) {
			return ':' . $field;
		}, $fields);
		return ["fields" => $fields, "values" => $values];
	}
	/**
	 * Undocumented function
	 *
	 * @param array $params
	 * @return string
	 */
	private function buildFieldQuery(array $params) : string
	{
		return join(', ', array_map(function ($field) {
			return "$field=:$field";
		}, array_keys($params)));
	}



}
