<?php
header("Content-Type: application/json");

require_once('../database/connect.php');

// init game room secret word
$submission = $_REQUEST;

$getCurrentUserInfo = $dbh->prepare("SELECT  user_one_id, user_two_id, current_player FROM game_room WHERE id = " . $submission['room_id']);
$getCurrentUserInfo->execute();
$room = $getCurrentUserInfo->setFetchMode(PDO::FETCH_ASSOC);
$current_user_info = $getCurrentUserInfo->fetch();
$board_data = [];

if (isset($submission['board_data']) && !empty($submission['board_data'])){
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
}

if ( $current_user_info['current_player'] == $submission['user_id'] ) {

    $update_player = '';

    if ($current_user_info['current_player'] == $current_user_info['user_one_id']) {
        $update_player = $current_user_info['user_two_id'];
    } else {
        $update_player = $current_user_info['user_one_id'];
    }

// Check for a winning play

    $update_board = "UPDATE game_room SET table_data = '" . $board_data . "', current_player = '" . $update_player . "' WHERE id = " . $submission['room_id'];
    $grab_room = $dbh->prepare("SELECT id, table_data, current_player, turn_over FROM game_room WHERE id = " . $submission['room_id']);

    $dbh->prepare($update_board)->execute();

    $grab_room->execute();
    $room = $grab_room->setFetchMode(PDO::FETCH_ASSOC);
    $assoc_array = $grab_room->fetch();

    echo json_encode($assoc_array += ['user_id' => $submission['user_id']]);
} else {
    echo json_encode(['turn' => '0', 'table_data' => "'". $board_data ."'"]);
}

