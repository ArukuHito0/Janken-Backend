<?php
// マッチングに使用する関数群

// 空きルームを探す関数
function find_available_room($db){
    $stmt = $db->prepare("SELECT * FROM game_rooms 
                          WHERE (p1_id IS NULL OR p1_id = '')
                             OR (p2_id IS NULL OR p2_id = '')
                          ORDER BY id ASC LIMIT 1");
    $stmt->execute();
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
    return $room;
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
    }else {
        // 参加に失敗した場合は新しいルームを作成
        $res = create_new_room($db, $userId);
        $playerNum = $res['player_num'];
        $roomId = $res['room_id'];
    }

    return ['player_num' => $playerNum, 'room_id' => (int)$roomId];       
}

// 新しいルームを作成する関数
function create_new_room($db, $userId){
    // 新しいルームを作成し、プレイヤー1として参加
    $stmt = $db->prepare("INSERT INTO game_rooms (p1_id) VALUES (:userId)");
    $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
    $stmt->execute();
    
    $roomId = $db->lastInsertId();
        
    reset_game_status($db, $roomId);
    reset_players_ready($db, $roomId);
    reset_players_hand($db, $roomId);
    reset_players_select($db, $roomId);
    reset_players_score($db, $roomId);
    reset_winner($db, $roomId);
    
    // プレイヤー番号とルームIDを返す
    return ['player_num' => 1, 'room_id' => (int)$db->lastInsertId()];
}
?>