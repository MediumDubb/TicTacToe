<?php
require_once('connect.php');

$selectgameroom = $dbh->query('SELECT * from game_room');

$selectgameroom->setFetchMode(PDO::FETCH_ASSOC);

// init game room id
$init_guid = 'INSERT INTO game_room (guid) VALUES (UUID_TO_BIN(UUID()))';
// init game room id
$secret_word = 'UPDATE secret_word SET guid=:secret_word';
// player one sql statement
$init_player_one = 'UPDATE game_room SET user_one_id=:user_one_id';
// player two sql statement
$init_player_two = 'UPDATE game_room SET user_two_id=:user_two_id';
// choose current player
$init_current_player = 'UPDATE game_room SET current_player=:current_player';
//update table data
$update_table = 'UPDATE game_room SET table_data=:table_data';
//update turn
$update_turn = 'UPDATE game_room SET turn_over=:turn_over';
// create new row
$create_room = 'INSERT INTO game_room (user_one_id, current_player) VALUES (:user_one_id,:current_player)';

// assign room and players
$row = $selectgameroom->fetch();

$room_id = '';
$player_id = '';

$data = [
    'player_one_id' => $player_id,
];

//$dbh->prepare($init_player_one)->execute($data);

