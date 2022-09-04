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
            <form action="" method="POST">
                <table class="board">
                    <tr>
                        <td><input type="hidden" id="a1"></td>
                        <td><input type="hidden" id="a2"></td>
                        <td><input type="hidden" id="a3"></td>
                    </tr>
                    <tr>
                        <td><input type="hidden" id="b1"></td>
                        <td><input type="hidden" id="b2"></td>
                        <td><input type="hidden" id="b3"></td>
                    </tr>
                    <tr>
                        <td><input type="hidden" id="c1"></td>
                        <td><input type="hidden" id="c2"></td>
                        <td><input type="hidden" id="c3"></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<!--
    Set up AJAX and an Endpoint for sending game requests to.
    keep track of board, and refresh page every couple seconds to keep board up to date.
    Keep some kind of game room ID for keeping track of the players facing one another
-->

</body>
</html>
