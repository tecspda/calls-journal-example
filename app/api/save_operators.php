<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once('../../config.php');
use app\controllers\ContollerOperators;

$post = json_decode(file_get_contents('php://input'));

if ((empty($post->id)) || ($post->id == 0)) {
	echo json_encode([
		'status' => 'error',
		'description' => 'Некорректный id, сохранение не удалось'
	]);
	exit;
}

$ret = ContollerOperators::save($post->id, $post->name, $post->price_per_minute);

echo json_encode([
	'status' => 'success',
	'description' => "Успешное сохранение. ID {$ret}"
]);

?>