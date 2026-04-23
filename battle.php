<?php
// じゃんけんの勝敗判定を行うAPI
require_once 'db_common.php';

// 最新の情報の確認
$row = get_room_data($db, $roomId);

// 各プレイヤーが手を選択済みなら、勝敗判定に進む
if(is_select_hand_players($row)){
   $result = get_janken_winner($row); // 勝敗判定の関数を呼び出す

   set_winner($db, $roomId, $result['winner']); // 勝者をDBにセット
   if($result['winner'] != -1){
      set_score($db, $roomId, $result['winner'], $result['p_score'] + 1); // 勝者のスコアを1点加算
   }
}
?>