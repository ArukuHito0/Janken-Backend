<?php
// ルームの状態を初期化するAPI
require_once 'db_common.php';

$roomId = $_POST['room_id'];

reset_player_hand($db, $roomId);
reset_player_select($db, $roomId);
reset_score($db, $roomId);
reset_winner($db, $roomId);

echo_game_json($db, $roomId);
?>