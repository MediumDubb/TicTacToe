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

<?php require_once('database/connect.php'); ?>
<!-- can write sql statements in this file since connect.php is included-->
<?php
$error = false;
$x_wins = false;
$o_wins = false;
$board_array = [
    ['', '', ''],
    ['', '', ''],
    ['', '', ''],
];
?>
<div class="container">
    <div class="row">
        <div class="menu-wrapper">
            <table class="menu">
                <tr>
                    <th class="message" id="message"></th>
                </tr>
            </table>
        </div>
        <div class="board-wrapper">
            <form action="" method="POST" id="tictac_board" class="d-invisible">
                <table class="board">
                    <?php

                    echo "<input id='user_char' type='hidden' value=''>";
                    echo "<input id='room_id' type='hidden' value=''>";
                    echo "<input id='user_id' type='hidden' value=''>";

                    for($id = 0; $id < 9; $id++){
                        if( $id === 0 || $id === 3 || $id == 6){
                            echo "<tr>";
                        }

                        echo "<td><input class='cell' autocomplete='false' type='text' readonly maxlength='1' name='$id' id='$id'></td>";

                        if( $id === 2 || $id === 5 || $id == 8){
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>
            </form>
        </div>
    </div>
</div>
<div class="init-room">
    <div class="init-room-wrapper">
        <form id="init-room-form" action="">
            <h2>Tic Tac Toe</h2>
            <label for="secret_word"> Create Room:
                <input id="secret_word" name="secret_word" type="text" placeholder="&nbsp;create secret word">
            </label>
            <div class="err creat-room"><p></p></div>
            <label for="join_room" class=""> Join Room:
                <input id="join_room" name="join_room" type="text" placeholder="&nbsp;provide secret word">
            </label>
            <div class="err no-room"><p></p></div>
            <input type="submit" value="start" disabled>

        </form>
    </div>
</div>
<script src="./script.js"></script>

<footer>

</footer>
</body>
</html>