-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 09 2021 г., 15:29
-- Версия сервера: 10.3.22-MariaDB
-- Версия PHP: 7.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `demo_laravel`
--

-- --------------------------------------------------------

--
-- Структура таблицы `applications`
--

CREATE TABLE `applications` (
  `application_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path_to_image_before` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path_to_image_after` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rejection_reason` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `applications`
--

INSERT INTO `applications` (`application_id`, `user_id`, `title`, `description`, `category`, `path_to_image_before`, `path_to_image_after`, `status`, `rejection_reason`, `created_at`) VALUES
(4, 1, 'Первая заявка', 'Описание', 'Первая категория', 'images/before/1_1620560806_1922939827.jpg', 'images/after/1_1620560969_669188500.jpg', 'Решена', NULL, '2021-05-09 14:46:46'),
(5, 1, 'Вторая заявка', 'Описание', 'Вторая категория', 'images/before/1_1620560818_1009557031.jpg', 'images/after/1_1620560963_2139985332.jpg', 'Решена', NULL, '2021-05-09 14:46:58'),
(6, 1, 'Третья заявка', 'Описание', 'Первая категория', 'images/before/1_1620560842_854668496.jpg', 'images/after/1_1620560950_122910768.jpg', 'Решена', NULL, '2021-05-09 14:47:22'),
(7, 1, 'Четвёртая заявка', 'Описание', 'Вторая категория', 'images/before/1_1620560911_796997101.jpg', 'images/after/1_1620560933_825494493.jpg', 'Решена', NULL, '2021-05-09 14:48:31'),
(8, 1, 'Пятая заявка', 'Описание', 'Первая категория', 'images/before/1_1620560995_616813417.jpg', NULL, 'Отклонена', 'Отклоняем', '2021-05-09 14:49:55'),
(9, 1, 'Шестая заявка', 'Описание', 'Вторая категория', 'images/before/1_1620561051_1948760763.jpg', NULL, 'Отклонена', 'Отклоняем', '2021-05-09 14:50:51');

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`category_id`, `category`) VALUES
(1, 'Первая категория'),
(2, 'Вторая категория');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fio` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `login` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `fio`, `login`, `email`, `password`, `remember_token`, `role`) VALUES
(1, 'Александр', 'ewoase', '1@1', '$2y$10$9lKdUfmsTd5XOyH0zfkWweeOF/aMKGrkvyQL6mF9/NuSwkNDh3LfW', 'KAd0FKJ4lZHPQIzS13urHWlffXiEmxgld2CdOvTsfEQuiZC6LHsjhwSCPFw2', 'admin'),
(2, 'Администратор', 'admin', '2@2', '$2y$10$cYOlIvzFaTZbcjaGRr9QzuNYYRKEombZ1UAVf7SzOVfvBn79oUsku', 'CLBI9MWrtbs0xNrq6oGFktO19HulUz6lSdWkgMhVeVwzVpn8Jk8XkzSlDINT', 'user');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`application_id`);

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `applications`
--
ALTER TABLE `applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
