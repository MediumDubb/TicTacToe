<?php
header("Content-Type: application/json");

require_once('../database/connect.php');

$submission = $_REQUEST;

if( !empty($submission)){
    $getCurrentUserInfo = $dbh->prepare("SELECT  user_one_id, user_two_id, current_player, winner_id FROM game_room WHERE id = " . $submission['room_id']);
    $getCurrentUserInfo->execute();
    $room = $getCurrentUserInfo->setFetchMode(PDO::FETCH_ASSOC);
    $current_user_info = $getCurrentUserInfo->fetch();
    $board_data = [];
    $winner = null;

    if (isset($submission['board_data']) && !empty($submission['board_data'])){
        foreach ($submission['board_data'] as $board_tile)
        {
            if ($board_tile == "o"){
                $board_data[] = 0;
            }

            if ($board_tile == "x"){
                $board_data[] = 1;
            }

            if ($board_tile == ""){
                $board_data[] = null;
            }
        }
    }

    if ($winner === null && isset($submission['board_data']) && !empty($submission['board_data'])) {
        for ($r = 0 ; $r < 9; $r += 3){
            $row_check = [];
            for($c = $r; $c < ($r + 3); $c++){
                if ($board_data[$c] === 1){
                    array_push($row_check, $board_data[$c]);
                }
                if ($board_data[$c] === 0){
                    array_push($row_check, $board_data[$c]);
                }
            }

            if( count($row_check) == 3 && $row_check[0] == $row_check[1] && $row_check[1] == $row_check[2]){
                $winner =  ['winner' => '3 in a row', 'winner_id' => $current_user_info['current_player']];
            }

        }
    }

    if ($winner === null && isset($submission['board_data']) && !empty($submission['board_data'])) {
        for ($r = 0; $r < 3; $r++) {
            $row_check = [];
            for ($c = $r; $c <= 9; $c += 3) {
                if ($board_data[$c] === 1) {
                    array_push($row_check, $board_data[$c]);
                }
                if ($board_data[$c] === 0) {
                    array_push($row_check, $board_data[$c]);
                }
            }

            if (count($row_check) == 3 && $row_check[0] == $row_check[1] && $row_check[1] == $row_check[2]) {
                $winner = ['winner' => '3 in a column', 'winner_id' => $current_user_info['current_player']];
            }

        }
    }

    if ($winner === null && isset($submission['board_data']) && !empty($submission['board_data'])) {
        for ($r = 0 ; $r < 3; $r += 2) {
            $row_check = [];
            if ($r == 0) {
                for ($c = $r; $c <= 9; $c += 4) {
                    if ($board_data[$c] === 1) {
                        array_push($row_check, $board_data[$c]);
                    }
                    if ($board_data[$c] === 0) {
                        array_push($row_check, $board_data[$c]);
                    }
                }
            }

            if ($r == 2) {
                for ($c = $r; $c <= 7; $c += 2) {
                    if ($board_data[$c] === 1) {
                        array_push($row_check, $board_data[$c]);
                    }
                    if ($board_data[$c] === 0) {
                        array_push($row_check, $board_data[$c]);
                    }
                }
            }


            if (count($row_check) == 3 && $row_check[0] == $row_check[1] && $row_check[1] == $row_check[2]) {
                $winner = ['winner' => '3 in a column', 'winner_id' => $current_user_info['current_player']];
            }
        }
    }

    $board_data = json_encode($board_data);

    if ( $current_user_info['current_player'] == $submission['user_id'] && $current_user_info['winner_id'] == null) {

        $update_player = '';

        if ($current_user_info['current_player'] == $current_user_info['user_one_id']) {
            $update_player = $current_user_info['user_two_id'];
        } else {
            $update_player = $current_user_info['user_one_id'];
        }

// Check for a winning play

        $update_board = "UPDATE game_room SET table_data = '" . $board_data . "', current_player = '" . $update_player . "' WHERE id = " . $submission['room_id'];
        $grab_room = $dbh->prepare("SELECT id, table_data, current_player FROM game_room WHERE id = " . $submission['room_id']);

        $dbh->prepare($update_board)->execute();

        $grab_room->execute();
        $room = $grab_room->setFetchMode(PDO::FETCH_ASSOC);
        $assoc_array = $grab_room->fetch();

//        Nothing below here matters atm

        if( $winner === null){
            $assoc_array += ['user_id' => $submission['user_id']];
            echo json_encode($assoc_array);
            exit();
        } else {
            $update_winner = "UPDATE game_room SET winner_id = '" . $current_user_info['current_player'] . "' WHERE id = " . $submission['room_id'];
            $dbh->prepare($update_winner)->execute();
            $assoc_array += ['user_id' => $submission['user_id']];
            $assoc_array += $winner;
            echo json_encode($assoc_array);
            exit();
        }

    } else {
        echo json_encode(['turn' => '0', 'table_data' => "'". $board_data ."'"]);
        exit();
    }
}


