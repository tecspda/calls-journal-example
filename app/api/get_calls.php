<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once('../../config.php');
use app\controllers\ContollerCalls;

$post = json_decode(file_get_contents('php://input'));

$calls = new ContollerCalls;
$ret = $calls->get($post->search_name);

echo json_encode([
	'status' => 'success',
	'answer' => $ret
]);

?>