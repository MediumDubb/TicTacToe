<?php
header("Content-Type: application/json");

require_once('../database/connect.php');

//refresh board data (every 2 sec)
$submission = $_REQUEST;

if( !empty($submission)) {
    $grab_room = $dbh->prepare("SELECT id, table_data, current_player, turn_over, winner_id FROM game_room WHERE id = ?");
    $grab_room->bindParam(1, $submission['room_id'], PDO::PARAM_INT);

    $grab_room->execute();
    $room = $grab_room->setFetchMode(PDO::FETCH_ASSOC);
    $assoc_array = $grab_room->fetch();

    echo json_encode($assoc_array);
    exit();
}