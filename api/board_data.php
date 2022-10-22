<?php
header("Content-Type: application/json");

require_once('../database/connect.php');

// init game room secret word

$submission = $_REQUEST;

$board_data = [];

foreach ($submission['board_data'] as $board_tile)
{
    if ($board_tile == "o"){
        $board_data[] = 0;
    }

    if ($board_tile == "x"){
        $board_data[] = 1;
    }

    if ($board_tile == ""){
        $board_data[] = null;
    }
}

$board_data = json_encode($board_data);
$update_board = "UPDATE game_room SET table_data = '" . $board_data . "' WHERE id = " . $submission['room_id'];
$grab_room = $dbh->prepare("SELECT id, table_data, current_player, turn_over FROM game_room WHERE id =" . $submission['room_id']);

if ( !isset($submission['board_data']) ) {
    $grab_room->execute();
    $room = $grab_room->setFetchMode(PDO::FETCH_ASSOC);
    $assoc_array = $grab_room->fetch();

    echo json_encode($assoc_array);
}

$dbh->prepare($update_board)->execute();

$grab_room->execute();
$room = $grab_room->setFetchMode(PDO::FETCH_ASSOC);
$assoc_array = $grab_room->fetch();

echo json_encode($assoc_array['user_id'] = $submission['user_id']);