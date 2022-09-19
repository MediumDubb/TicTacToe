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

<?php require_once('connect.php'); ?>
<!-- can write sql statements in this file since connect.php is included-->
<?php
    $player_char = '';
    $player_id = '';
    $room_id = '';

    if ( isset($_COOKIE['tictactoe_user'])){
        $player_id = $_COOKIE['tictactoe_user'];
    }

    function random_strings($length_of_string)
    {
        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        // Shuffle the $str_result and returns substring
        // of specified length
        return substr(str_shuffle($str_result),
        0, $length_of_string);
    }

    // Assign player ID's
    while ($row = $selectgameroom->fetch()):
        $room_id = htmlspecialchars($row['room_id']);

        if (!isset($_COOKIE['tictactoe_user'])){
            if (empty(htmlspecialchars($row['player_one_id'])) && empty(htmlspecialchars($row['player_two_id'])) && empty($player_id) ) {
                // generate random user_string as an ID for p1
                $player_id = random_strings(8);
                setcookie( "tictactoe_user", $player_id, strtotime( '+30 days' ) );

                $data = [
                    'player_one_id' => $player_id,
                    'x' => $player_id,
                ];

                // update player_one_id column in dbh
                $dbh->prepare($update_player_one_id)->execute($data);
            }
            elseif ( !empty(htmlspecialchars($row['player_one_id'])) && empty(htmlspecialchars($row['player_two_id'])) && empty($player_id) ){
                // generate random user_string as an ID for p2
                $player_id = random_strings(8);
                setcookie( "tictactoe_user", $player_id, strtotime( '+30 days' ) );

                if ($player_id === htmlspecialchars($row['player_one_id'])) {
                    while ($player_id === htmlspecialchars($row['player_one_id'])) {
                        $player_id = random_strings(8);
                    }
                }

                $data = [
                    'player_two_id' => $player_id,
                    'o' => $player_id,
                ];

                $dbh->prepare($update_player_two_id)->execute($data);
            }
            elseif ( empty(htmlspecialchars($row['player_one_id'])) && !empty(htmlspecialchars($row['player_two_id'])) && empty($player_id) ){
                // generate random user_string as an ID for p1 if p2 is already assigned
                $player_id = random_strings(8);
                setcookie( "tictactoe_user", $player_id, strtotime( '+30 days' ) );

                if ($player_id === htmlspecialchars($row['player_two_id'])){
                    while ($player_id === htmlspecialchars($row['player_two_id'])){
                        $player_id = random_strings(8);
                    }
                }

                $data = [
                    'player_one_id' => $player_id,
                ];

                $dbh->prepare($update_player_one_id)->execute($data);
            }
        }

     endwhile;
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

                    echo "<input id='user_char' type='hidden' value=''>";
                    echo "<input id='game_room' type='hidden' value='$room_id'>";
                    echo "<input id='user_id' type='hidden' value='$player_id'>";

                    for($id = 1; $id < 10; $id++){
                        if( $id === 1 || $id === 4 || $id == 7){
                           echo "<tr>";
                        }

                        echo "<td><input class='cell' autocomplete='false' type='text' readonly maxlength='1' name='$id' id='$id'";

                        if (!empty($_POST["$id"])){

                            if ($_POST["$id"] === "X" ||$_POST["$id"] === "x" || $_POST["$id"] === "O" || $_POST["$id"] === "o"){
                                echo "value=$_POST[$id]";

//                                row
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

//                              column
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

//                              diagonal
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

                <?php

                if ($x_wins === true){
                    echo "<input id='x_wins' type='hidden' value='true'>";
                    echo "X wins!";
                } elseif ($o_wins === true) {
                    echo "<input id='o_wins' type='hidden' value='true'>";
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
