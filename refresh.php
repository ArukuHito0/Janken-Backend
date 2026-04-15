<?php
// 対戦の状態をリフレッシュするAPI
require_once 'db_common.php';

reset_players_select($db, $roomId);

include 'deck.php'; // deck.php内でecho_game_jsonも呼び出されるため、最後に呼び出す
?>