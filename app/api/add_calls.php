<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once('../../config.php');
use app\controllers\ContollerCalls;

$post = json_decode(file_get_contents('php://input'));

if ((empty($post->phone))) {
	echo json_encode([
		'status' => 'error',
		'description' => 'Некорректный phone, сохранение не удалось'
	]);
	exit;
}

$ret = ContollerCalls::add($post);

echo json_encode([
	'status' => 'success',
	'description' => "Успешное сохранение. ID {$ret}"
]);

?>