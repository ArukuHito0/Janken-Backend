<?php
require_once 'db_common.php';

$roomId = $_POST['room_id'];
$playerNum = $_POST['player_num'];

// デッキをリセットし、シャッフル
$deck = [0,0,0,1,1,1,2,2,2];
shuffle($deck);

// player 1, player 2に3枚ずつ手札を配る
$p1_hand = implode(",", array_slice($deck, 0, 3)); // 配列[0]から3つ目までとる 0~2
$p2_hand = implode(",", array_slice($deck, 3, 3)); // 配列[3]から3つ目までとる 3~5
$open_card = $deck[6];

// 配った手札をDBに送信
set_player_hand($db, $roomId, $p1_hand, $p2_hand, $open_card);

// Unityに対して出力
echo_game_json($db, $roomId);
?>