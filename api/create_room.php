<?php
header("Content-Type: application/json");

require_once('../database/connect.php');

// init game room secret word

$submission = $_REQUEST;

$select_game_room = '';

$secret_word = 'INSERT INTO game_room (secret_word) VALUES (:secret_word)';




if ( isset($submission['secret_word']) ){
    $data = [
        'secret_word' => $submission['secret_word']
    ];

    $dbh->prepare($secret_word)->execute($data);

    $sql = "SELECT id FROM game_room WHERE secret_word = '" . $submission['secret_word'] . "';";
    $objQuery = $dbh->query($sql);
    $objQuery->setFetchMode(PDO::FETCH_ASSOC);
    $row = $objQuery->fetch();

    echo json_encode($row);
}
