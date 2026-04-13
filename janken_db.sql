-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2026-04-13 08:12:47
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
  `open_card` int(11) DEFAULT NULL,
  `p1_hand` varchar(10) DEFAULT NULL,
  `p2_hand` varchar(10) DEFAULT NULL,
  `p1_select` int(11) DEFAULT -1,
  `p2_select` int(11) DEFAULT -1,
  `p1_score` int(11) DEFAULT 0,
  `p2_score` int(11) DEFAULT 0,
  `p1_ready` tinyint(1) NOT NULL DEFAULT 0,
  `p2_ready` tinyint(1) NOT NULL DEFAULT 0,
  `winner` int(11) NOT NULL,
  `game_status` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `game_rooms`
--

INSERT INTO `game_rooms` (`id`, `open_card`, `p1_hand`, `p2_hand`, `p1_select`, `p2_select`, `p1_score`, `p2_score`, `p1_ready`, `p2_ready`, `winner`, `game_status`) VALUES
(1, NULL, NULL, NULL, 4, 4, 0, 0, 0, 0, -1, 1);

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `game_rooms`
--
ALTER TABLE `game_rooms`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
