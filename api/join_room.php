<?php
header("Content-Type: application/json");

require_once('../database/connect.php');

// init game room secret word

$submission = $_REQUEST;

$check_room = $dbh->prepare("SELECT COUNT(*) FROM game_room WHERE secret_word ='" . $submission['secret_word']. "'");
$generate_user_two = "UPDATE game_room SET user_two_id = (SELECT UUID()) WHERE secret_word ='" . $submission['secret_word'] . "'";
$grab_room = $dbh->prepare("SELECT * FROM game_room WHERE secret_word ='" . $submission['secret_word']. "'");

if ( isset($submission['secret_word']) ) {

    //check for duplicate secret room word
    $check_room->execute();
    $check = $check_room->setFetchMode(PDO::FETCH_ASSOC);
    $rows = $check_room->fetchColumn(0);

    if ( $rows < 1 ){
        echo json_encode(['error' => 'Room Doesn\'t Exist']);
    } else {
        // generate the room row
        $dbh->prepare($generate_user_two)->execute();

        // fetch newly created row
        $grab_room->execute();
        $room = $grab_room->setFetchMode(PDO::FETCH_ASSOC);
        $assoc_array = $grab_room->fetch();
        $assoc_array += ['char' => 'o'];

        echo json_encode($assoc_array);
    }
}