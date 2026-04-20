<?php
// 任意のカラムに値をセットする関数を定義するphpファイル

function set_column_int($db, $roomId, $column, $int_value){
    $stmt = $db->prepare("UPDATE game_rooms SET $column = :_int WHERE id = :id");
    $stmt->bindValue(':_int', $int_value, PDO::PARAM_INT);
    $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();
}

// カラムに真偽値をセットする関数
function set_column_bool($db, $roomId, $column, $bool_value){
    $stmt = $db->prepare("UPDATE game_rooms SET $column = :_bool WHERE id = :id");
    $stmt->bindValue(':_bool', $bool_value ? 1 : 0, PDO::PARAM_INT);
    $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();
}

// カラムに文字列をセットする関数
function set_column_str($db, $roomId, $column, $str_value){
    $stmt = $db->prepare("UPDATE game_rooms SET $column = :_str WHERE id = :id");
    $stmt->bindValue(':_str', $str_value, PDO::PARAM_STR);
    $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();
}
?>