<?php

try {
    $dbh = new PDO('mysql:host=localhost;dbname=tictactoe', 'root', 'root');

    $q = $dbh->query('SELECT * from gameroom');
    $q->setFetchMode(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

