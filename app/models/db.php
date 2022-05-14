<?php
namespace app\models;

use \mysqli;

class DB
{
	public $mysqli;
	
	function __construct() {
		// Create connection
		$this->mysqli = new mysqli(DB_HOST, U_NAME, U_PASS, DB_NAME);
		// Check connection
		if ($this->mysqli->connect_error) {
			die("Connection failed: " . self::$mysqli->connect_error);
		}
	}
	
	function __destruct() {
		if(isset($this->mysqli)) $this->mysqli->close();
	}
	
	// метод запрос к данным
	public function getQuery($sql):array
	{
		$ret = [];
		$result = $this->mysqli->query($sql);
		if ($result->num_rows){
			while ($row = $result->fetch_object()){
				$ret[] = $row;
			}
		}
		return $ret;
	}
	
	// метод exec sql
	public function execQuery($sql):bool
	{
		$ret = [];
		$result = $this->mysqli->query($sql);
		
		return $result;
	}
	
	// экранируем
	public function real_escape_string($str):string
	{
		return ($str !== '' ? $this->mysqli->real_escape_string($str) : '');
	}
	
}