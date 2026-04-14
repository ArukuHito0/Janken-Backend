<?php
// ルームの作成・参加をするためのAPI
require_once 'db_common.php';
require_once 'matching_util.php';

$userId = $_POST['user_id'];

// プレイヤー１もしくはプレイヤー２のどちらかが空いているルームを探す
$room = find_available_room($db);

// 空いているルームがあれば参加、なければ新しいルームを作成
if($room){
    $res = join_room($db, $room, $userId);
}else{
    $res = create_new_room($db, $userId);
}

// ルームIDとプレイヤー番号をJSON形式で返す
echo json_encode([
    'player_num' => $res['player_num'],
    'room_id' => (int)$res['room_id']
]);
?>