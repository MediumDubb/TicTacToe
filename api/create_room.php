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

    $check_room = $dbh->prepare("SELECT COUNT(*) FROM game_room WHERE secret_word ='" . $submission['secret_word'] . "'");
    $generate_room = "INSERT INTO game_room (secret_word, user_one_id, table_data, join_url) VALUES (:secret_word, (SELECT UUID()),'" . $table_data . "','" . $join_room_url . "' )";
    $grab_room = $dbh->prepare("SELECT * FROM game_room WHERE secret_word ='" . $submission['secret_word'] . "'");

    if (isset($submission['secret_word'])) {

        //check for duplicate secret room word
        $check_room->execute();
        $check = $check_room->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $check_room->fetchColumn(0);

        if ($rows > 0) {
            echo json_encode(['error' => 'Room Exists']);
        } else {
            $data = [
                'secret_word' => $submission['secret_word']
            ];

            // generate the room row
            $dbh->prepare($generate_room)->execute($data);

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

