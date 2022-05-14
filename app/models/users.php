<?php
namespace app\models;

use \mysqli;
use app\models\DB;

class Users
{
	private $db;
	
	function __construct() { 
		$this->db = new DB;
	}
	
	function __destruct() {	
		unset($this->db);
	}
	
	// select users
	public function get(string $name = ''):array
	{
		$name_ = $this->db->real_escape_string($name);
		$where = $name !== '' ? "AND a.`name` LIKE '%$name_%' " : '';
		
		$sql_text = "SELECT a.*, b.name operator_name FROM users a, operators b WHERE a.operator_id = b.id  $where ORDER BY a.name";

		$ret = $this->db->getQuery($sql_text);
		return $ret;
	}
	
	// добавляем оператора
	public function add(string $name, string $phone, int $operator_id):int
	{
		$sql_text = <<<HERE
			INSERT INTO `users` (`name`, `phone`, `operator_id`)
			VALUES ('$name', '$phone', $operator_id)
HERE;
		$this->db->mysqli->query($sql_text);
		return $this->db->mysqli->insert_id;
	}
	
	// редактируем оператора оператора
	public function save($id, string $name, string $phone, int $operator_id):int
	{
		$name_ = $this->db->real_escape_string($name);
		$phone_ = $phone;
		
		if($name !== '') { $params[] = " `name` = '$name_' "; };
		if($phone !== '') { $params[] = " phone = '$phone_' "; };
		if($operator_id !== '') { $params[] = " operator_id = $operator_id "; };
		
		$params = implode(', ', $params);
		
		$sql_text = <<<HERE
			UPDATE `users`
			SET $params
			WHERE id = $id
HERE;
		$this->db->execQuery($sql_text);
		return $id;
	}

}