<?php
header("Content-Type: application/json");

require_once('../database/connect.php');

// init game room secret word

$submission = $_REQUEST;

if( !empty($submission)) {

    $request_info = $_SERVER;

    $base_url = $request_info['REQUEST_SCHEME'] . '://' . $request_info['HTTP_HOST'];

    $join_room_url = $base_url . '/?action=join_room&secret_word=' . $submission['secret_word'];

    $table_data = '[null,null,null,null,null,null,null,null,null]';

    $check_room = $dbh->prepare("SELECT COUNT(*) FROM game_room WHERE secret_word = ?");
    $check_room->bindParam(1, $submission['secret_word'], PDO::PARAM_STR);

    $generate_room = $dbh->prepare("INSERT INTO game_room (secret_word, user_one_id, table_data, join_url) VALUES ( ?, (SELECT UUID()), ?, ? )");
    $generate_room->bindParam(1, $submission['secret_word'], PDO::PARAM_STR);
    $generate_room->bindParam(2, $table_data, PDO::PARAM_STR);
    $generate_room->bindParam(3, $join_room_url, PDO::PARAM_STR);

    $grab_room = $dbh->prepare("SELECT * FROM game_room WHERE secret_word = ?");
    $grab_room->bindParam(1, $submission['secret_word'], PDO::PARAM_STR);

    if (isset($submission['secret_word'])) {

        //check for duplicate secret room word
        $check_room->execute();
        $check = $check_room->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $check_room->fetchColumn(0);

        if ($rows > 0) {
            echo json_encode(['error' => 'Room Exists']);
        } else {

            // generate the room row
            $generate_room->execute();

            // fetch newly created row
            $grab_room->execute();
            $room = $grab_room->setFetchMode(PDO::FETCH_ASSOC);
            $assoc_array = $grab_room->fetch();
            $assoc_array += ['char' => 'x'];

            echo json_encode($assoc_array);
            exit();
        }
    }
}

