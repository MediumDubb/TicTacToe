<?php

try {
    $dbh = new PDO('mysql:host=localhost;dbname=tictactoe', 'root', 'root');

    $selectgameroom = $dbh->query('SELECT * from gameroom');

    $update_player_one_id = 'UPDATE gameroom SET player_one_id=:player_one_id, x=:x';
// player two sql statement
    $update_player_two_id = 'UPDATE gameroom SET player_two_id=:player_two_id, o=:o';

// add new row to table if row before is full, assign two players
//    $update_player_one_id = 'INSERT INTO gameroom (player_one_id) VALUES (:player_one_id)';

    $selectgameroom->setFetchMode(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

