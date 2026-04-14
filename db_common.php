<?php
// データベース接続と共通関数を定義するAPI
try{
    $db = new PDO('mysql:dbname=janken_db;host=127.0.0.1;charset=utf8', 'root', '');
}catch(PDOException $e){
    echo 'DB接続エラー: '.$e->getMessage();
}

// ゲームの状態を定数で定義
define('STATUS_WAITING', 'waiting');     // ルーム待機中
define('STATUS_READY', 'ready');         // ルーム準備完了
define('STATUS_SELECTING', 'selecting'); // プレイヤーが手を選択中
define('STATUS_BATTLE', 'battle');       // じゃんけんの勝敗判定中
define('STATUS_RESULT', 'result');       // 結果表示中
define('STATUS_END', 'end');             // 対戦終了

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
    $row = get_room_data($db, $roomId);
    set_score($db, $roomId, 1, 0);
    set_score($db, $roomId, 2, 0);
}

// 各プレイヤーの準備状態をリセット
function reset_players_ready($db, $roomId){
    set_player_ready($db, $roomId, 1, false);
    set_player_ready($db, $roomId, 2, false);
}

// 勝者をリセット
function reset_winner($db, $roomId){
    set_winner($db, $roomId, -1);
}

// ゲームの状態をセット
function set_game_status($db, $roomId, $status){
    $stmt = $db->prepare("UPDATE game_rooms SET game_status = :game_status WHERE id = :id");
    $stmt->bindValue(':game_status', $status, PDO::PARAM_STR);
    $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();
}

// 各プレイヤーの手札・公開カードをセット
function set_players_hand($db, $roomId, $p1_hand, $p2_hand, $open_card){
    $stmt = $db->prepare("UPDATE game_rooms SET p1_hand = :p1, p2_hand = :p2, open_card = :_open WHERE id = :id");
    $stmt->bindValue(':p1', $p1_hand, PDO::PARAM_STR);
    $stmt->bindValue(':p2', $p2_hand, PDO::PARAM_STR);
    $stmt->bindValue(':_open', $open_card, PDO::PARAM_INT);
    $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();
}

// プレイヤーの手をセット
function set_player_select($db, $roomId, $selecter, $selectedHand){
    $stmt = $db->prepare("UPDATE game_rooms SET $selecter = :hand WHERE id = :id");
    $stmt->bindValue(':hand', $selectedHand, PDO::PARAM_INT);
    $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();
}

// プレイヤーのスコアをセット
function set_score($db, $roomId, $playerNum, $score){
    $column = ($playerNum == 1) ? 'p1_score' : 'p2_score';
    $stmt = $db->prepare("UPDATE game_rooms SET $column = :score WHERE id = :id");
    $stmt->bindValue(':score', $score, PDO::PARAM_INT);
    $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();
}

// プレイヤーの準備状態をセット
function set_player_ready($db, $roomId, $playerNum, $isReady){
    $column = ($playerNum == 1) ? 'p1_ready' : 'p2_ready';
    $stmt = $db->prepare("UPDATE game_rooms SET $column = :ready WHERE id = :id");
    $stmt->bindValue(':ready', $isReady, PDO::PARAM_BOOL);
    $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();
}

// じゃんけんの勝者をセット
function set_winner($db, $roomId, $winner){
    $stmt = $db->prepare("UPDATE game_rooms SET winner = :winner WHERE id = :id");
    $stmt->bindValue(':winner', $winner, PDO::PARAM_INT);
    $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();
}

// プレイヤーが両方揃っているか確認
function is_match_players($row){
    return (!empty($row['p1_id']) && !empty($row['p2_id']));
}

// 各プレイヤーが手を選択しているか確認
function is_select_players($row){
    return ($row['p1_select'] != 4 && $row['p2_select'] != 4);
}

// プレイヤーが両方準備完了しているか確認
function is_ready_players($row){
    return ($row['p1_ready'] && $row['p2_ready']);
}

// ゲームの勝敗がついたか
function is_decided_game($row){
    $winPlayerScore = ($row['winner'] == 1) ? $row['p1_score'] : $row['p2_score'];
    return ($winPlayerScore >= 3);
}

// ルームのDBを取得
function get_room_data($db, $roomId){
    $stmt = $db->prepare("SELECT * FROM game_rooms WHERE id = :id");
    $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO:: FETCH_ASSOC);
}

// ルームのDBをUnityに返す
function echo_game_json($db, $roomId){
    $row = get_room_data($db, $roomId);
    
    header('Content-Type:application/json');

    echo json_encode([
        "p1_id" => $row['p1_id'],
        "p2_id" => $row['p2_id'],
        "open_card" => (int)$row['open_card'],
        "p1_hand"  => $row['p1_hand'],
        "p2_hand"  => $row['p2_hand'],
        "p1_select" => (int)$row['p1_select'],
        "p2_select" => (int)$row['p2_select'],
        "p1_score" => (int)$row['p1_score'],
        "p2_score" => (int)$row['p2_score'],
        "p1_ready" => (bool)$row['p1_ready'],
        "p2_ready" => (bool)$row['p2_ready'],
        'winner' => (int)$row['winner'],
        "game_status" => $row['game_status'],
    ]);
    exit;
}
?>