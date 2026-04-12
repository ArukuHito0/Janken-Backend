<?php
try{
    $db = new PDO('mysql:dbname=janken_db;host=127.0.0.1;charset=utf8', 'root', '');
}catch(PDOException $e){
    echo 'DB接続エラー: '.$e->getMessage();
}

function reset_game_status($db, $roomId){
    set_game_status($db, $roomId, 0);
}

function reset_player_hand($db, $roomId){
    set_player_hand($db, $roomId, NULL, NULL, NULL);
}

function reset_score($db, $roomId){
    $row = get_room_data($db, $roomId);
    set_score($db, $roomId, 1, 0);
    set_score($db, $roomId, 2, 0);
}

function set_game_status($db, $roomId, $status){
    $stmt = $db->prepare("UPDATE game_rooms SET game_status = :game_status WHERE id = :id");
    $stmt->bindValue(':game_status', $status, PDO::PARAM_INT);
    $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();
}

function set_player_hand($db, $roomId, $p1_hand, $p2_hand, $open_card){
    $stmt = $db->prepare("UPDATE game_rooms SET p1_hand = :p1, p2_hand = :p2, open_card = :_open WHERE id = :id");
    $stmt->bindValue(':p1', $p1_hand, PDO::PARAM_STR);
    $stmt->bindValue(':p2', $p2_hand, PDO::PARAM_STR);
    $stmt->bindValue(':_open', $open_card, PDO::PARAM_INT);
    $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();
}

function set_player_select($db, $roomId, $selecter, $selectedHand){
    $stmt = $db->prepare("UPDATE game_rooms SET $selecter = :hand WHERE id = :id");
    $stmt->bindValue(':hand', $selectedHand, PDO::PARAM_INT);
    $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();
}

function set_score($db, $roomId, $playerNum, $score){
    $column = ($playerNum == 1) ? 'p1_score' : 'p2_score';
    $stmt = $db->prepare("UPDATE game_rooms SET $column = :score WHERE id = :id");
    $stmt->bindValue(':score', $score, PDO::PARAM_INT);
    $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();
}

function get_room_data($db, $roomId){
    $stmt = $db->prepare("SELECT * FROM game_rooms WHERE id = :id");
    $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO:: FETCH_ASSOC);
}

// 全てのSQLのデータを返信
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
        "p2_select" => (int)$row['p2_select']
    ]);
    exit;
}
?>