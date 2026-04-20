-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2026-04-20 08:47:44
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `janken_db`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `game_rooms`
--

CREATE TABLE `game_rooms` (
  `id` int(11) NOT NULL,
  `game_status` varchar(20) DEFAULT NULL,
  `winner` int(11) NOT NULL,
  `open_card` int(11) DEFAULT NULL,
  `p1_id` text DEFAULT NULL,
  `p2_id` text DEFAULT NULL,
  `p1_connect` tinyint(1) NOT NULL DEFAULT 0,
  `p2_connect` tinyint(1) NOT NULL DEFAULT 0,
  `p1_hand` varchar(10) DEFAULT NULL,
  `p2_hand` varchar(10) DEFAULT NULL,
  `p1_select` int(11) DEFAULT 4,
  `p2_select` int(11) DEFAULT 4,
  `p1_score` int(11) DEFAULT 0,
  `p2_score` int(11) DEFAULT 0,
  `p1_status` varchar(20) NOT NULL DEFAULT '''selecting''',
  `p2_status` varchar(20) NOT NULL DEFAULT '''selecting'''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `game_rooms`
--
ALTER TABLE `game_rooms`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `game_rooms`
--
ALTER TABLE `game_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
