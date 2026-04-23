<?php
// ゲームの条件を判定する関数を定義するphpファイル

// ルームにプレイヤーが両方接続しているか確認
function is_connect_players($row){
    return ($row['p1_connect'] && $row['p2_connect']);
}

// どちらかのプレイヤーが接続されているか
function is_connect_some_player($row){
    return ($row['p1_connect'] || $row['p2_connect']);
}

// 各プレイヤーが手を選択しているか確認
function is_select_hand_players($row){
    return ($row['p1_select'] != 4 && $row['p2_select'] != 4);
}

// ゲームの勝敗がついたか
function is_decided_game($row){
    $winnerScore = ($row['winner'] == 1) ? $row['p1_score'] : $row['p2_score'];
    return ($winnerScore >= 3);
}

// プレイヤーが再戦を希望しているか
function is_rematch_players($row){
    return ($row['p1_status'] === PLAYER_REMATCH && $row['p2_status'] === PLAYER_REMATCH);
}

// プレイヤーが選択済みか
function is_selected_players($row){
    return ($row['p1_status'] != PLAYER_SELECTING && $row['p2_status'] != PLAYER_SELECTING);
}
?>