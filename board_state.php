<?php
try {
    $dbh = new PDO('mysql:host=localhost;dbname=tictactoe', 'root', 'root');
    $dbh->query('SELECT * from gameroom');
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

if( ! isset($_GET['roomID']) && ! isset($_GET['playerID'])){
// check for unfilled room (either join one with someone waiting or make a new room)
// assign room id
// assign player id
// assign tictactoe character (x/o)
}