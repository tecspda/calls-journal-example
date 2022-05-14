<?php
namespace app\controllers;

use \mysqli;
use app\models\Users;

class ContollerUsers
{
	
	// select оператора
	public static function get(string $name = ''):array
	{
		$users = new Users;
		$ret_arr = $users->get($name);

		return $ret_arr;
	}
	
	// добавляем оператора
	public static function add(string $name, string $phone, int $operator_id):int
	{
		$users = new Users;
		$new_id = $users->add($name, $phone, $operator_id);
		
		return $new_id;
	}
	
	// редактируем оператора оператора
	public static function save($id, string $name, string $phone, int $operator_id):int
	{
		$users = new Users;
		$new_id = $users->save($id, $name, $phone, $operator_id);
		
		return $new_id;
	}

}

