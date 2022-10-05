<?php
header("Content-Type: application/json");

require_once('../database/connect.php');

// init game room secret word

$submission = $_REQUEST;

$generate_room = 'INSERT INTO game_room (secret_word, user_one_id) VALUES (:secret_word, (SELECT UUID()))';
$grab_room = $dbh->prepare("SELECT * FROM game_room WHERE secret_word ='" . $submission['secret_word']. "'");

if ( isset($submission['secret_word']) ) {
    $data = [
        'secret_word' => $submission['secret_word']
    ];

    $dbh->prepare($generate_room)->execute($data);

    $grab_room->execute();
    $result = $grab_room->setFetchMode(PDO::FETCH_ASSOC);

    if ($result) {
        $assoc_array = $grab_room->fetch();
        echo json_encode($assoc_array);
    } else {
        echo json_encode(['error' => 'Room not created']);
    }
}

