<?php
require_once 'db_common.php';

$roomId = $_POST['room_id'];
$playerNum = $_POST['player_num'];

set_player_status($db, $roomId, $playerNum, PLAYER_REMATCH); // プレイヤーの状態をDBにセット

echo_game_json($db, $roomId);
?>