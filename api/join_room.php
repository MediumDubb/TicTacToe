<?php
header("Content-Type: application/json");

require_once('../database/connect.php');

$submission = $_REQUEST;

if( !empty($submission)) {

    $check_room = $dbh->prepare("SELECT COUNT(*) FROM game_room WHERE secret_word = ?");
    $check_room->bindParam(1, $submission['secret_word'], PDO::PARAM_STR);

    $check_p2_id = $dbh->prepare("SELECT user_two_id FROM game_room WHERE secret_word = ?");
    $check_p2_id->bindParam(1, $submission['secret_word'], PDO::PARAM_STR);

    $generate_user_two = $dbh->prepare("UPDATE game_room SET user_two_id = (SELECT UUID()) WHERE secret_word = ?");
    $generate_user_two->bindParam(1, $submission['secret_word'], PDO::PARAM_STR);

    $grab_room = $dbh->prepare("SELECT * FROM game_room WHERE secret_word = ?");
    $grab_room->bindParam(1, $submission['secret_word'], PDO::PARAM_STR);

    $get_users = $dbh->prepare("SELECT user_one_id, user_two_id FROM game_room WHERE secret_word = ?");
    $get_users->bindParam(1, $submission['secret_word'], PDO::PARAM_STR);

    if (isset($submission['secret_word'])) {

        //check for duplicate secret room word
        $check_room->execute();
        $check = $check_room->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $check_room->fetchColumn(0);

        // check for user_two_id
        $check_p2_id->execute();
        $check = $check_p2_id->setFetchMode(PDO::FETCH_ASSOC);
        $value = $check_p2_id->fetchColumn(0);

        if ($rows < 1 || !empty($value)) {

            if ($rows < 1) {
                echo json_encode(['error' => 'Room Doesn\'t Exist']);
            } elseif (!empty($value)) {
                echo json_encode(['error' => 'Room is Full']);
            }

        } else {
            // generate the room row
            $generate_user_two->execute();

            // get both users (both have been created at this point)
            $get_users->execute();
            $users = $get_users->fetch(PDO::FETCH_ASSOC);

            $current_player = null;

            // random player assigned to first move
            if (mt_rand(0, 10) >= 5) {
                $current_player = $users['user_two_id'];
            } else {
                $current_player = $users['user_one_id'];
            }
            // query for setting current_player
            $set_current_player = $dbh->prepare("UPDATE game_room SET current_player = ? WHERE secret_word = ?");
            $set_current_player->bindParam(1, $current_player, PDO::PARAM_STR);
            $set_current_player->bindParam(2, $submission['secret_word'], PDO::PARAM_STR);
            $set_current_player->execute();

            // fetch newly created row
            $grab_room->execute();
            $room = $grab_room->setFetchMode(PDO::FETCH_ASSOC);
            $assoc_array = $grab_room->fetch();
            $assoc_array += ['char' => 'o'];

            echo json_encode($assoc_array);
            exit();
        }
    }
}