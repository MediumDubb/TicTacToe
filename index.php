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
                    <th class="message"></th>
                </tr>
            </table>
        </div>
        <div class="board-wrapper">
            <form action="" method="POST" id="tictac_board" class="d-invisible">
                <table class="board">
                    <?php

                    echo "<input id='user_char' type='hidden' value=''>";
                    echo "<input id='game_room' type='hidden' value=''>";
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
<!--
    Set up AJAX and an Endpoint for sending game requests to.
    keep track of board, and refresh page every couple seconds to keep board up to date.
    Keep some kind of game room ID for keeping track of the players facing one another.\


    Check for a win condition or tie condition... somehow
-->

<script src="./script.js"></script>

<?php
// crappy way to get some user data back if page is refreshed unexpectedly...
// maybe thinking about saving remote ip *no good public facing ip is the same for all people on a network*
// cookie with user id in db so I can refer to that incase the url gets wiped and a user re joins an open/unfinshed game
if (isset($_GET['room_id']) && isset($_GET['user_id'])){
    ?>
    <script>
        $( document ).ready(function() {
            let board_inputs = $("#tictac_board input[type='text']");
            let init_form = $("div.init-room");
            let tictactoe_board = $("#tictac_board");

            reconnect('<?php echo $_GET['room_id']; ?>', '<?php echo $_GET['user_id']; ?>');

            function reconnect(room_id, user_id) {
                let request = $.ajax({
                    method: "POST",
                    url: 'api/reconnect.php',
                    data: {'room_id': room_id, 'user_id': user_id},
                    dataType: 'json'
                });

                request.done( function ( result ) {
                    console.log('done. ' + result);
                    if ( result.error ){
                        // return error result (duplicate secret word)
                        alert(result.error);
                    } else {
                        // handle setting up new game room (remove overhang form, show board)
                        parse_board(JSON.parse(result.table_data), board_inputs);
                        $('#user_char').val(result.char);
                        init_form.hide();
                        tictactoe_board.removeClass("d-invisible");
                    }

                });

                request.fail( function (iqXHR, status) {
                    alert("Request Failed:" + status);
                });
            }

            function parse_board(obj, board){
                for (let key in obj) {
                    if (obj.hasOwnProperty(key)) {

                        if (obj[key] === null){
                            $(board[key]).val('');
                        }

                        if (obj[key] === 0){
                            $(board[key]).val('o');
                        }

                        if (obj[key] === 1){
                            $(board[key]).val('x');
                        }
                    }
                }
            }
        });
    </script>
    <?php
}
?>

</body>
</html>