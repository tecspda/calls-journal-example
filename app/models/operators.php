<?php
namespace app\models;

use \mysqli;
use app\models\DB;

class Operators
{
	private $db;
	
	function __construct() { 
		$this->db = new DB;
	}
	
	function __destruct() {	
		unset($this->db);
	}
	
	// select оператора
	public function get(string $name = ''):array
	{
		$name_ = $this->db->real_escape_string($name);
		$where = $name !== '' ? "WHERE `name` LIKE '%$name_%' " : '';
		
		$sql_text = "SELECT * FROM operators $where";

		$ret = $this->db->getQuery($sql_text);
		return $ret;
	}
	
	// добавляем оператора
	public function add(string $name, float $price_per_minute):int
	{
		$sql_text = <<<HERE
			INSERT INTO `operators` (`name`, `price_per_minute`)
			VALUES ('$name', $price_per_minute)
HERE;

		$this->db->mysqli->query($sql_text);
		return $this->db->mysqli->insert_id;
	}
	
	// редактируем оператора оператора
	public function save($id, string $name, float $price_per_minute):int
	{
		$name_ = $this->db->real_escape_string($name);
		$price_per_minute_ = $price_per_minute;
		
		if($name !== '') { $params[] = " `name` = '$name_' "; };
		if($price_per_minute !== '') { $params[] = " price_per_minute = $price_per_minute_ "; };
		
		$params = implode(', ', $params);
		
		$sql_text = <<<HERE
			UPDATE `operators`
			SET $params
			WHERE id = $id
HERE;
		$this->db->execQuery($sql_text);
		return $id;
	}

}