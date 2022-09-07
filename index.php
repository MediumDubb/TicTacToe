<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tic Tac Toe</title>
    <link rel="stylesheet" href="./style.css">
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
</head>
<body>
<?php
    try {
        $dbh = new PDO('mysql:host=localhost;dbname=tictactoe', 'root', 'root');
        foreach($dbh->query('SELECT * from gameroom') as $row) {
            print_r($row);
        }
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
?>
<div class="container">
    <div class="row">
        <div class="menu-wrapper">
            <table class="menu">
                <tr>
                    <th>P1: score</th>
                    <th>P2: score</th>
                    <th>Winner</th>
                </tr>
            </table>
        </div>
        <div class="board-wrapper">
            <form action="index.php" method="POST" id="tictac_board">
                <table class="board">
                    <?php
                    $error = false;
                    $x_wins = false;
                    $o_wins = false;

                    for($id = 1; $id < 10; $id++){
                        if( $id === 1 || $id === 4 || $id == 7){
                           echo "<tr>";
                        }

                        echo "<input id='user_char' type='hidden' value='x'>";
                        echo "<td><input class='cell' autocomplete='false' type='text' readonly maxlength='1' name='$id' id='$id'";

                        if (!empty($_POST["$id"])){

                            if ($_POST["$id"] === "X" ||$_POST["$id"] === "x" || $_POST["$id"] === "O" || $_POST["$id"] === "o"){
                                echo "value=$_POST[$id]";

                                for ($r1 = 1, $r2 = 2, $r3 = 3; $r1 <= 7, $r2 <= 8, $r3 <= 9; $r1 += 3, $r2 += 3, $r3 += 3){
                                    if ($_POST[$r1] == $_POST[$r2] && $_POST[$r2] == $_POST[$r3] ){
                                        if ($_POST[$r1] == "X" || $_POST[$r1] == "x")
                                        {
                                            $x_wins = true;
                                        } elseif ($_POST[$r1] == "O" || $_POST[$r1] == "o"){
                                            $o_wins = true;
                                        }
                                    }
                                }

                                for ($c1 = 1, $c2 = 4, $c3 = 7; $c1 <= 3, $c2 <= 6, $c3 <= 9; $c1 += 1, $c2 += 1, $c3 += 1){
                                    if ($_POST[$c1] == $_POST[$c2] && $_POST[$c2] == $_POST[$c3] ){
                                        if ($_POST[$c1] == "X" || $_POST[$c1] == "x")
                                        {
                                            $x_wins = true;
                                        } elseif ($_POST[$c1] == "O" || $_POST[$c1] == "o"){
                                            $o_wins = true;
                                        }
                                    }
                                }

                                for ($d1 = 1, $d2 = 5, $d3 = 9; $d1 <= 3, $d2 <= 5, $d3 >= 7; $d1 += 2, $d2 += 0, $d3 -= 2){
                                    if ($_POST[$d1] == $_POST[$d2] && $_POST[$d2] == $_POST[$d3] ){
                                        if ($_POST[$d1] == "X" || $_POST[$d1] == "x")
                                        {
                                            $x_wins = true;
                                        } elseif ($_POST[$d1] == "O" || $_POST[$d1] == "o")
                                        {
                                            $o_wins = true;
                                        }
                                    }
                                }
                            }
                        }
                        // close
                        echo "> </td>";

                        if( $id === 3 || $id === 6 || $id == 9){
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>

                <input type="submit" name="submit_btn" value="Draw">

                <?php

                if ($x_wins === true){
                    echo "X wins!";
                } elseif ($o_wins === true) {
                    echo "O wins!";
                }

                ?>
            </form>
        </div>
    </div>
</div>
<!--
    Set up AJAX and an Endpoint for sending game requests to.
    keep track of board, and refresh page every couple seconds to keep board up to date.
    Keep some kind of game room ID for keeping track of the players facing one another.\


    Check for a win condition or tie condition... somehow
-->
<script src="./script.js"></script>
</body>
</html>
