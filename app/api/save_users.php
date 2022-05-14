<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once('../../config.php');
use app\controllers\ContollerUsers;

$post = json_decode(file_get_contents('php://input'));

if ((empty($post->id)) || ($post->id == 0)) {
	echo json_encode([
		'status' => 'error',
		'description' => 'Некорректный id, сохранение не удалось'
	]);
	exit;
}

$ret = ContollerUsers::save($post->id, $post->name, $post->phone, $post->operator_id);

echo json_encode([
	'status' => 'success',
	'description' => "Успешное сохранение. ID {$ret}"
]);

?>