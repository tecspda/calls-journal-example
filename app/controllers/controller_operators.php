<?php
namespace app\controllers;

use \mysqli;
use app\models\Operators;

class ContollerOperators
{
	
	// select оператора
	public static function get(string $name = ''):array
	{
		$operators = new Operators;
		$ret_arr = $operators->get($name);

		return $ret_arr;
	}
	
	// добавляем оператора
	public static function add(string $name, float $price_per_minute):int
	{
		$operators = new Operators;
		$new_id = $operators->add($name, $price_per_minute);
		
		return $new_id;
	}
	
	// редактируем оператора оператора
	public static function save($id, string $name, float $price_per_minute):int
	{
		$operators = new Operators;
		$new_id = $operators->save($id, $name, $price_per_minute);
		
		return $new_id;
	}

}

// принимаем запрос от аякс (добавление, сохранение)
$post = json_decode(file_get_contents('php://input'), true);

if (!empty($post['type'])){
	
	if($post['type'] == 'get'){
		ContollerOperators::get($post['name']);
	} elseif ($post['type'] == 'add'){
		ContollerOperators::add($post['name'], $post['phone'], $post['phone_opeator']);
	} elseif ($post['type'] == 'save'){
		ContollerOperators::add($post['name'], $post['phone'], $post['phone_opeator']);
	}
	
} 