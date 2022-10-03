<?php

try {
    $dbh = new PDO('mysql:host=localhost;dbname=tictactoe', 'root', 'root');
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$room_id = '';
$player_id = '';


