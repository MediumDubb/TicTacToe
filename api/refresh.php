<?php
header("Content-Type: application/json");

require_once('../database/connect.php');

// init game room secret word

$submission = $_REQUEST;

$grab_room = $dbh->prepare("SELECT id, table_data, current_player, turn_over, winner_id FROM game_room WHERE id = " . $submission['room_id']);

$grab_room->execute();
$room = $grab_room->setFetchMode(PDO::FETCH_ASSOC);
$assoc_array = $grab_room->fetch();

echo json_encode($assoc_array);