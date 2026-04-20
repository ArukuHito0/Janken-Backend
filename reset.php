<?php
require_once 'db_common.php';

$roomId = $_POST['room_id'];

reset_game_status($db, $roomId);
reset_players_status($db, $roomId);
reset_players_hand($db, $roomId);
reset_players_select($db, $roomId);
reset_players_score($db, $roomId);
reset_winner($db, $roomId);
?>