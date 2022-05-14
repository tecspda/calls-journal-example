<?php
namespace app\controllers;

use app\models\Calls;

class ContollerCalls
{
	
	// select оператора
	public static function get(string $search_name = ''):array
	{
		$calls = new Calls;
		$ret_arr = $calls->get($search_name);

		return $ret_arr;
	}
	
	// добавляем оператора
	public static function add(object $params):int
	{
		// здесь можно поформатировать данные или поменять
		$calls = new Calls;
		$new_id = $calls->add($params);
		
		return $new_id;
	}
	
	// редактируем оператора оператора
	public static function save($id, string $name, string $phone, int $operator_id):int
	{
		$calls = new Calls;
		$new_id = $calls->save($id, $name, $phone, $operator_id);
		
		return $new_id;
	}

}

