<?php
require_once('connect.php');

$select_game_room = $dbh->query('SELECT BIN_TO_UUID(user_one_id), BIN_TO_UUID(user_two_id), id, secret_word, table_data, current_player, turn_over from game_room');

$select_game_room->setFetchMode(PDO::FETCH_ASSOC);


// init game room secret word
$secret_word = 'UPDATE secret_word SET guid=:secret_word';
// player one sql statement
$init_player_one = 'INSERT INTO game_room (user_one_id) VALUES (UUID_TO_BIN(UUID()))';
// player two sql statement
$init_player_two = 'INSERT INTO game_room (user_two_id) VALUES (UUID_TO_BIN(UUID()))';
// choose current player
$init_current_player = 'UPDATE game_room SET current_player=:current_player';
//update table data
$update_table = 'UPDATE game_room SET table_data=:table_data';
//update turn
$update_turn = 'UPDATE game_room SET turn_over=:turn_over';
// create new row
$create_room = 'INSERT INTO game_room (user_one_id, current_player) VALUES (:user_one_id,:current_player)';

// assign room and players
$row = $select_game_room->fetch();

$room_id = '';
$player_id = '';

$data = [
    'player_one_id' => $player_id,
];

//$dbh->prepare($init_player_one)->execute($data);

var_dump($row);
