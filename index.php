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
        $dbh = new PDO('mysql:host=localhost;dbname=tictactoe', 'root', '');
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
            <form action="index.php" method="POST">
                <table class="board">
                    <?php
                    for($id = 1; $id < 10; $id++){
                        if( $id === 1 || $id === 4 || $id == 7){
                           echo "<tr>";
                        }

                        echo "<td><input type='text' maxlength='1' name='$id' id='$id'></td>";

//                        if (isset($_POST["submit"]) && !empty($_POST["$id"])){
//
//                        }

                        if( $id === 3 || $id === 6 || $id == 9){
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>

                <input type="submit" name="submit" value="Draw">

                <?php
                    $error = false;


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

</body>
</html>
