<?php
header("Content-Type: application/json");

require_once('../database/connect.php');

// init game room secret word

$submission = $_REQUEST;

$board_data = [];

foreach ($submission['board_data'] as $board_tile)
{
    if ($board_tile == "o"){
        $board_data[] = "0";
    }

    if ($board_tile == "x"){
        $board_data[] = "1";
    }

    if ($board_tile == ""){
        $board_data[] = "null";
    }
}

$board_data = json_encode($board_data);

$update_board = "UPDATE tictactoe SET table_data ='" . $board_data . "' WHERE id = '" . $submission['room_id']. "'";

if ( isset($submission['table_data']) && isset($submission['room_id'])) {

    //check for duplicate secret room word
    $dbh->prepare($update_board)->execute();

}