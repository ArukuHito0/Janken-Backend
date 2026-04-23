<?php
// ゲームの状態を監視するAPI
require_once 'db_common.php';

$roomId = $_POST['room_id'];
$playerNum = $_POST['player_num'];

$row = get_room_data($db, $roomId);
$gameStatus = $row['game_status'];

if($playerNum == ($row['p1_connect'] ? 1 : 2)){
    switch($gameStatus){
        case STATUS_WAITING:
            // ルーム待機中
            if(is_connect_players($row)){
                // プレイヤーが埋まったら、ルーム準備完了に移行
                set_game_status($db, $roomId, STATUS_READY);
            }
            break;
        case STATUS_READY:
            // ルーム準備完了
            include 'refresh.php';
            set_game_status($db, $roomId, STATUS_SELECTING);
            break;
        case STATUS_SELECTING:
            // プレイヤーが手を選択中
            if(is_select_hand_players($row)){
                // 両プレイヤーが手を選択したら、勝敗判定に移行
                set_game_status($db, $roomId, STATUS_BATTLE);
            }
            break;
        case STATUS_BATTLE:
            include 'battle.php';
            set_game_status($db, $roomId, STATUS_RESULT);
            break;
        case STATUS_RESULT:
            if(is_decided_game($row)){
                // 勝敗がついたら、対戦終了に移行
                set_game_status($db, $roomId, STATUS_END);
            }else{
                // 勝敗がついていない場合は、次のバトルに移行
                set_game_status($db, $roomId, STATUS_READY);
            }
            break;
        case STATUS_END:
            // 対戦終了
            if(is_selected_players($row)){
                if(is_rematch_players($row)){
                    // お互いが再戦を希望するなら、ゲームの進行をリセットして再度対戦開始
                    include 'reset.php';
                    set_game_status($db, $roomId, STATUS_READY);
                }else{
                    // プレイヤーが再戦を希望しない場合は、ルーム待機状態に戻す
                    set_game_status($db, $roomId, STATUS_WAITING);
                }
            }
            break;
    }
}

echo_game_json($db, $roomId);
?>