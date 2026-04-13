<?php
// プレイヤーが出す手を選択するためのAPI
require_once 'db_common.php';

// UnityからPOSTで送られてくるデータ
$roomId = $_POST['room_id'];
$playerNum = $_POST['player_num'];
$selectedHand = $_POST['selected_hand'];

// 出す手の選択
$selecter = ($playerNum == 1) ? 'p1_select' : 'p2_select';
set_player_select($db, $roomId, $selecter, $selectedHand);

echo_game_json($db, $roomId);
?>