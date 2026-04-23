<?php
// ルームのカラムを更新する関数を定義するphpファイル
require_once 'table_dao.php';

// プレイヤーの接続状態をリセット
function reset_players_connect($db, $roomId){
    set_player_connect($db, $roomId, 1, false);
    set_player_connect($db, $roomId, 2, false);
}

// ルームのステートをリセット
function reset_game_status($db, $roomId){
    set_game_status($db, $roomId, STATUS_WAITING);
}

// 各プレイヤーの手札・公開カードをリセット
function reset_players_hand($db, $roomId){
    set_players_hand($db, $roomId, NULL, NULL, NULL);
}

// 各プレイヤーの手をリセット
function reset_players_select($db, $roomId){
    set_player_select($db, $roomId, 'p1_select', 4);
    set_player_select($db, $roomId, 'p2_select', 4);
}

// 各プレイヤーのスコアを0にリセット
function reset_players_score($db, $roomId){
    set_score($db, $roomId, 1, 0);
    set_score($db, $roomId, 2, 0);
}

// 各プレイヤーの状態をリセット
function reset_players_status($db, $roomId){
    set_player_status($db, $roomId, 1, PLAYER_SELECTING);
    set_player_status($db, $roomId, 2, PLAYER_SELECTING);
}

// 勝者をリセット
function reset_winner($db, $roomId){
    set_winner($db, $roomId, -1);
}

// プレイヤーIDをリセット
function reset_players_id($db, $roomId){
    $stmt = $db->prepare("UPDATE game_rooms SET p1_id = NULL, p2_id = NULL WHERE id = :id");
    $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();
}

// ルームの状態を完全にリセット
function reset_room_completely($db, $roomId){
    reset_players_connect($db, $roomId);
    reset_game_status($db, $roomId);
    reset_players_status($db, $roomId);
    reset_players_hand($db, $roomId);
    reset_players_select($db, $roomId);
    reset_players_score($db, $roomId);
    reset_winner($db, $roomId);
}

// プレイヤーの接続状態をセット
function set_player_connect($db, $roomId, $playerNum, $isActive){
    $p_connect = ($playerNum == 1) ? 'p1_connect' : 'p2_connect';
    set_column_bool($db, $roomId, $p_connect, $isActive);
}

// ゲームの状態をセット
function set_game_status($db, $roomId, $status){
    set_column_str($db, $roomId, 'game_status', $status);
}

// 各プレイヤーの手札・公開カードをセット
function set_players_hand($db, $roomId, $p1_hand, $p2_hand, $open_card){
    set_player_hand($db, $roomId, 1, $p1_hand);
    set_player_hand($db, $roomId, 2, $p2_hand);
    set_open_card($db, $roomId, $open_card);
}

// プレイヤーの手札をセット
function set_player_hand($db, $roomId, $playerNum, $hand){
    $p_hand = ($playerNum == 1) ? 'p1_hand' : 'p2_hand';
    set_column_str($db, $roomId, $p_hand, $hand);
}

// 公開カードをセット
function set_open_card($db, $roomId, $open_card){
    set_column_int($db, $roomId, 'open_card', $open_card);
}

// プレイヤーの手をセット
function set_player_select($db, $roomId, $selecter, $selectedHand){
    set_column_int($db, $roomId, $selecter, $selectedHand);
}

// プレイヤーのスコアをセット
function set_score($db, $roomId, $playerNum, $score){
    $p_score = ($playerNum == 1) ? 'p1_score' : 'p2_score';
    set_column_int($db, $roomId, $p_score, $score);
}

// プレイヤーの準備状態をセット
function set_player_status($db, $roomId, $playerNum, $status){
    $p_status = ($playerNum == 1) ? 'p1_status' : 'p2_status';
    set_column_str($db, $roomId, $p_status, $status);
}

// じゃんけんの勝者をセット
function set_winner($db, $roomId, $playerNum){
    set_column_int($db, $roomId, 'winner', $playerNum);
}

// じゃんけんの勝敗判定を行う関数
function get_janken_winner($row){
    $p1 = $row['p1_select']; // player 1の手
    $p2 = $row['p2_select']; // player 2の手
    
    if($p1 == $p2)
        return ['winner' => -1]; // あいこ
    else if(($p1 == 0 && $p2 == 1) || ($p1 == 1 && $p2 == 2) || ($p1 == 2 && $p2 == 0))
        return ['winner' => 1, 'p_score' => $row['p1_score']]; // player 1の勝利
    else
        return ['winner' => 2, 'p_score' => $row['p2_score']]; // player 2の勝利
}
?>