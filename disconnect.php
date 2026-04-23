<?php
require_once 'db_common.php';
require_once 'matching_util.php';

$roomId = $_POST['room_id'];
$userId = $_POST['user_id'];

// 既に参加しているルームを探す
$room = find_joined_room($db, $userId);

if($room){
    // ルームから退出
    leave_room($db, $room, $userId);
    $row = get_room_data($db, $room['id']);
    if(!is_connect_some_player($row)){
        // どちらのプレイヤーも接続していない場合は、ルームの状態を完全にリセット
        reset_room_completely($db, $room['id']);
        reset_players_id($db, $room['id']);
    }
}
?>