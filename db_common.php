<?php
require_once 'game_validator.php';
require_once 'room_columns_manager.php';

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

// プレイヤーの状態を定数で定義
define('PLAYER_SELECTING', 'selecting'); // 選択中
define('PLAYER_REMATCH', 'rematch');     // 再戦希望
define('PLAYER_LEAVE', 'leave');         // ルームから退出

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
        "game_status" => $row['game_status'],
        'winner' => (int)$row['winner'],
        "open_card" => (int)$row['open_card'],
        "p1_id" => $row['p1_id'],
        "p2_id" => $row['p2_id'],
        "p1_connect" => (bool)$row['p1_connect'],
        "p2_connect" => (bool)$row['p2_connect'],
        "p1_hand"  => $row['p1_hand'],
        "p2_hand"  => $row['p2_hand'],
        "p1_select" => (int)$row['p1_select'],
        "p2_select" => (int)$row['p2_select'],
        "p1_score" => (int)$row['p1_score'],
        "p2_score" => (int)$row['p2_score'],
        "p1_status" => $row['p1_status'],
        "p2_status" => $row['p2_status'],
    ]);
    exit;
}
?>