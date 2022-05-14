<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once('../../config.php');
use app\controllers\ContollerUsers;

$users = new ContollerUsers;
$ret = $users->get();

echo json_encode([
	'status' => 'success',
	'answer' => $ret
]);

?>