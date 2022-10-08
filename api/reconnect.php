<?php
header("Content-Type: application/json");

require_once('../database/connect.php');

// init game room secret word

$submission = $_REQUEST;

$grab_room_users = $dbh->prepare("SELECT user_one_id, user_two_id FROM game_room WHERE id ='" . $submission['room_id']. "'");

if ( isset($submission['room_id']) && isset($submission['user_id'])) {

    //check for duplicate secret room word
    $grab_room_users->execute();
    $check = $grab_room_users->setFetchMode(PDO::FETCH_ASSOC);
    $rows = $grab_room_users->fetch();


    if ( $rows['user_one_id'] != null && $rows['user_one_id'] == $submission['user_id'] ){
        echo json_encode(['char' => 'x']);
    } elseif( $rows['user_one_id'] != null && $rows['user_two_id'] == $submission['user_id'] ) {
        echo json_encode(['char' => 'o']);
    } else {
        echo json_encode(['error' => 'Data specified not found']);
    }
}