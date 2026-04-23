<?php
// マッチングに使用する関数群

// 空きルームを探す関数
function find_available_room($db){
    $stmt = $db->prepare("SELECT * FROM game_rooms 
                          WHERE ((p1_id IS NULL OR p1_id = '')
                             OR (p2_id IS NULL OR p2_id = ''))
                             AND game_status = '" . STATUS_WAITING . "'
                          ORDER BY id ASC LIMIT 1");
    $stmt->execute();
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
    return $room;
}

// 自分が既に参加しているルームを探す関数
function find_joined_room($db, $userId){
    $stmt = $db->prepare("SELECT * FROM game_rooms 
                          WHERE (p1_id = :userId OR p2_id = :userId)
                          ORDER BY id ASC LIMIT 1");
    $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// ルームに参加する関数
function join_room($db, $room, $userId){
    $playerIdColumn = empty($room['p1_id']) ? 'p1_id' : 'p2_id';

    $roomId = $room['id'];

    $stmt = $db->prepare("UPDATE game_rooms SET $playerIdColumn = :userId
                          WHERE id = :id
                          AND ($playerIdColumn IS NULL OR $playerIdColumn = '')");
    $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
    $stmt->bindValue(':id', $room['id'], PDO::PARAM_INT);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        // 参加に成功した場合はプレイヤー番号を返す
        $playerNum = ($playerIdColumn == 'p1_id') ? 1 : 2; 
        set_player_connect($db, $roomId, $playerNum, true); // 接続状態をアクティブにセット
    }else {
        // 参加に失敗した場合は新しいルームを作成
        $res = create_new_room($db, $userId);
        $playerNum = $res['player_num'];
        $roomId = $res['room_id'];
    }

    return ['player_num' => $playerNum, 'room_id' => (int)$roomId];
}

// ルームに復帰する関数
function rejoin_room($db, $room, $userId){
    $playerNum = ($room['p1_id'] == $userId) ? 1 : 2;
    set_player_connect($db, $room['id'], $playerNum, true); // 接続状態をアクティブにセット
    return ['player_num' => $playerNum, 'room_id' => (int)$room['id']];
}

// ルームから退出する関数
function leave_room($db, $room, $userId){
    $playerNum = ($room['p1_id'] == $userId) ? 1 : 2;

    set_player_connect($db, $room['id'], $playerNum, false); // 接続状態を非アクティブにセット
    set_player_status($db, $room['id'], $playerNum, PLAYER_LEAVE); // プレイヤーの状態をDBにセット

    $p_id = ($playerNum == 1) ? 'p1_id' : 'p2_id';

    if($room['game_status'] == STATUS_END){
        $stmt= $db->prepare("UPDATE game_rooms SET $p_id = NULL WHERE id = :id");
        $stmt->bindValue(':id', $room['id'], PDO::PARAM_INT);
        $stmt->execute();
    }
}

// 新しいルームを作成する関数
function create_new_room($db, $userId){
    // 新しいルームを作成し、プレイヤー1として参加
    $stmt = $db->prepare("INSERT INTO game_rooms (p1_id) VALUES (:userId)");
    $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
    $stmt->execute();
    
    $roomId = $db->lastInsertId();

    reset_room_completely($db, $roomId); // ルームの状態を完全にリセット
    set_player_connect($db, $roomId, 1, true); // 接続状態をアクティブにセット

    // プレイヤー番号とルームIDを返す
    return ['player_num' => 1, 'room_id' => $roomId];
}
?>