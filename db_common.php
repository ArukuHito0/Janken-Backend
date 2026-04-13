<?php
// データベース接続と共通関数を定義するAPI
try{
    $db = new PDO('mysql:dbname=janken_db;host=127.0.0.1;charset=utf8', 'root', '');
}catch(PDOException $e){
    echo 'DB接続エラー: '.$e->getMessage();
}

// 各プレイヤーの手札・公開カードをリセット
function reset_player_hand($db, $roomId){
    set_player_hand($db, $roomId, NULL, NULL, NULL);
}

// 各プレイヤーの手をリセット
function reset_player_select($db, $roomId){
    set_player_select($db, $roomId, 'p1_select', 4);
    set_player_select($db, $roomId, 'p2_select', 4);
}

// 各プレイヤーのスコアを0にリセット
function reset_score($db, $roomId){
    $row = get_room_data($db, $roomId);
    set_score($db, $roomId, 1, 0);
    set_score($db, $roomId, 2, 0);
}

// 勝者をリセット
function reset_winner($db, $roomId){
    set_winner($db, $roomId, -1);
}

// ゲームの状態をセット
function set_game_status($db, $roomId, $status){
    $stmt = $db->prepare("UPDATE game_rooms SET game_status = :game_status WHERE id = :id");
    $stmt->bindValue(':game_status', $status, PDO::PARAM_INT);
    $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();
}

// 各プレイヤーの手札・公開カードをセット
function set_player_hand($db, $roomId, $p1_hand, $p2_hand, $open_card){
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

// じゃんけんの勝者をセット
function set_winner($db, $roomId, $winner){
    $stmt = $db->prepare("UPDATE game_rooms SET winner = :winner WHERE id = :id");
    $stmt->bindValue(':winner', $winner, PDO::PARAM_INT);
    $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();
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
        "game_status" => (int)$row['game_status'],
        "p1_score" => (int)$row['p1_score'],
        "p2_score" => (int)$row['p2_score'],
        "p1_hand"  => $row['p1_hand'],
        "p2_hand"  => $row['p2_hand'],
        "open_card" => (int)$row['open_card'],
        "p1_select" => (int)$row['p1_select'],
        "p2_select" => (int)$row['p2_select'],
        'winner' => (int)$row['winner']
    ]);
    exit;
}
?>