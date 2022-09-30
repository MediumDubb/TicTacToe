<?php
header("Content-Type: application/json");

require_once('../database/connect.php');

$submission = $_REQUEST;

$data = json_encode(['test' => 'success']);

echo $data;