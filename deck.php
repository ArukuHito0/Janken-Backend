<?php
// デッキのリセットとシャッフル、手札の配布を行うAPI
require_once 'db_common.php';

$roomId = $_POST['room_id'];
$playerNum = $_POST['player_num'];

// デッキをリセットし、シャッフル
$deck = [0,0,0,1,1,1,2,2,2];
shuffle($deck);

// player 1, player 2に3枚ずつ手札を配る
$p1_array = array_slice($deck, 0, 3); // 配列[0]から3つ目までとる 0~2
$p2_array = array_slice($deck, 3, 3); // 配列[3]から3つ目までとる 3~5

$p1_hand = implode(",", $p1_array); // p1_arrayを文字列に変換
$p2_hand = implode(",", $p2_array); // p2_arrayを文字列に変換
$open_card = $deck[6];

// 配った手札をDBに送信
set_players_hand($db, $roomId, $p1_hand, $p2_hand, $open_card);
?>