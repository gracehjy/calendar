<?php

$mysqli = new mysqli('localhost', 'gracehjy', 'Luzr_5462', 'module5');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}

?>