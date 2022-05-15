<?php
namespace app\models;

use \mysqli;
use app\models\DB;

class Calls
{
	private $db;
	
	function __construct() { 
		$this->db = new DB;
	}
	
	function __destruct() {	
		unset($this->db);
	}
	
	// select users
	public function get(string $search_name = ''):array
	{
		$name_ = $this->db->real_escape_string($search_name);
		$where = $name_ !== '' ? "AND (c.`name` LIKE '%$name_%' OR a.phone LIKE '%$name_%' OR b.name LIKE '%$name_%') " : '';
		
		$sql_text = <<<HERE
			SELECT a.*, b.`name` operator_name, c.`name` user_name, format(b.price_per_minute * a.call_duration, 2) price
			FROM calls a
			JOIN operators b
				   ON a.operator_id = b.id
			JOIN users c
				   ON a.user_id = c.id
			$where
			ORDER BY a.date_time
HERE;
// dd($sql_text, 'aaa');
		$ret = $this->db->getQuery($sql_text);
		return $ret;
	}
	
	// добавляем звонок
	// type = [0: исходящий, 1: входящий]
	// call_duration в секундах
	public function add(object $params):int
	{		
		extract((array) $params);
		
		$date_ = date('Y-m-d H:i', strtotime($date_time));
		
		$sql_text = <<<HERE
			INSERT INTO `calls` (`user_id`, `phone`, `type`, `date_time`, `call_duration`, `operator_id`)
			VALUES ($user_id, '$phone', $type, '$date_', $call_duration, $operator_id);
HERE;

// dd($sql_text, 'sql');
		$this->db->mysqli->query($sql_text);
		return $this->db->mysqli->insert_id;
	}
	
	// редактируем оператора оператора
	public function save(array $params):int
	{
// 		$name_ = $this->db->real_escape_string($name);
// 		$phone_ = $phone;
// 		
// 		if($name !== '') { $params[] = " `name` = '$name_' "; };
// 		if($phone !== '') { $params[] = " phone = '$phone_' "; };
// 		if($operator_id !== '') { $params[] = " operator_id = $operator_id "; };
// 		
// 		$params = implode(', ', $params);
// 		
// 		$sql_text = <<<HERE
// 			UPDATE `users`
// 			SET $params
// 			WHERE id = $id
// HERE;
// 		$this->db->execQuery($sql_text);
// 		return $id;
	}

}