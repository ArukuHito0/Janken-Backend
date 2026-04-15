<?php
// じゃんけんの勝敗判定を行うAPI
require_once 'db_common.php';

// 最新の情報の確認
$row = get_room_data($db, $roomId);

$p1 = $row['p1_select']; // player 1の手
$p2 = $row['p2_select']; // player 2の手

// 各プレイヤーが手を選択済みなら、勝敗判定に進む
if(is_select_players($row)){
   if($p1 == $p2){
    // あいこ
    set_winner($db, $roomId, -1);
   }else if(($p1 == 0 && $p2 == 1) || ($p1 == 1 && $p2 == 2) || ($p1 == 2 && $p2 == 0)){
    // player 1の勝利
    set_winner($db, $roomId, 1);
    set_score($db, $roomId, 1, $row['p1_score'] + 1);
   }else{
    // player 2の勝利
    set_winner($db, $roomId, 2);
    set_score($db, $roomId, 2, $row['p2_score'] + 1);
   }
}
?>