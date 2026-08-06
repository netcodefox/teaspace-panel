-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 05. Jun 2021 um 15:31
-- Server-Version: 10.3.27-MariaDB-0+deb10u1
-- PHP-Version: 7.4.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `teaspace`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `affiliates`
--

CREATE TABLE `affiliates` (
  `id` int(11) NOT NULL,
  `affiliate_id` varchar(255) NOT NULL,
  `affiliated_id` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `state` enum('pending','done') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `affiliates`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `affiliate_clicks`
--

CREATE TABLE `affiliate_clicks` (
  `id` int(11) NOT NULL,
  `affiliate_id` varchar(255) NOT NULL,
  `user_addr` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `affiliate_clicks`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mail_blacklist`
--

CREATE TABLE `mail_blacklist` (
  `id` int(11) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `mail_blacklist`
--

INSERT INTO `mail_blacklist` (`id`, `mail`, `created_at`, `updated_at`) VALUES
(1, 'bcaoo.com', '2020-09-03 00:02:10', '2020-09-03 00:02:10'),
(2, 'doc-mail.net', '2020-09-03 19:53:15', '2020-09-03 19:53:15'),
(3, '4nextmail.com', '2020-09-03 19:54:11', '2020-09-03 19:54:11'),
(4, '247web.net', '2020-09-03 19:54:17', '2020-09-03 19:54:17'),
(5, 'coin-host.net', '2020-09-03 19:54:29', '2020-09-03 19:54:29'),
(6, 'doublemail.de', '2020-09-03 19:54:38', '2020-09-03 19:54:38'),
(7, 'schreib-mir.tk', '2020-09-03 19:54:44', '2020-09-03 19:54:44'),
(8, 'puppetmail.de', '2020-09-03 19:54:49', '2020-09-03 19:54:49'),
(9, 'urhen.com', '2020-09-03 19:54:55', '2020-09-03 19:54:55'),
(10, 'zoemail.net', '2020-09-03 19:55:05', '2020-09-03 19:55:05'),
(11, 'zoemail.com', '2020-09-03 19:55:08', '2020-09-03 19:55:08'),
(12, 'zipzaps.de', '2020-09-03 19:55:28', '2020-09-03 19:55:28'),
(14, 'byom.de', '2020-09-03 20:10:25', '2020-09-03 20:10:25'),
(15, 'trash-mail.com', '2020-09-03 20:10:47', '2020-09-03 20:10:47'),
(16, 'zwoho.com', '2020-09-03 20:10:57', '2020-09-03 20:10:57'),
(17, 'phaantm.de', '2020-09-03 20:11:06', '2020-09-03 20:11:06'),
(18, 'fyii.de', '2020-09-03 20:11:13', '2020-09-03 20:11:13'),
(19, 'mailbox92.biz', '2020-09-03 20:11:27', '2020-09-03 20:11:27'),
(20, 'webtempmail.online', '2020-09-03 20:11:39', '2020-09-03 20:11:39'),
(21, 'virtual-email.com', '2020-09-03 20:11:50', '2020-09-03 20:11:50'),
(22, 'click-mail.net', '2020-09-03 20:11:59', '2020-09-03 20:11:59'),
(23, 'globaleuro.net', '2020-09-03 20:12:40', '2020-09-03 20:12:40'),
(24, 'nextemail.net', '2020-09-03 20:12:49', '2020-09-03 20:12:49'),
(25, 'quick-mail.online', '2020-09-03 20:12:59', '2020-09-03 20:12:59'),
(26, 'mailgen.biz', '2020-09-03 20:13:13', '2020-09-03 20:13:13'),
(27, 'mail-pro.info', '2020-09-03 20:13:24', '2020-09-03 20:13:24'),
(28, 'desoz.com', '2020-09-03 20:13:31', '2020-09-03 20:13:31'),
(29, 'mail4.online', '2020-09-03 20:14:33', '2020-09-03 20:14:33'),
(30, 're-gister.com', '2020-09-03 20:14:48', '2020-09-03 20:14:48'),
(32, 'freeweb.email', '2020-09-03 20:15:07', '2020-09-03 20:15:07'),
(33, 'alpha-web.net', '2020-09-03 20:15:19', '2020-09-03 20:15:19'),
(34, 'givmail.com', '2020-09-03 20:15:27', '2020-09-03 20:15:27'),
(35, 'tempr.email', '2020-09-03 20:15:35', '2020-09-03 20:15:35'),
(36, 'yourweb.email', '2020-09-03 20:15:43', '2020-09-03 20:15:43'),
(37, 'proeasyweb.com', '2020-09-03 20:15:53', '2020-09-03 20:15:53'),
(38, 'weave.email', '2020-09-03 20:16:01', '2020-09-03 20:16:01'),
(39, 'mailboxy.fun', '2020-09-03 20:16:15', '2020-09-03 20:16:15'),
(40, 'xn--hotmail-tc7d.com', '2020-09-03 20:16:31', '2020-09-03 20:16:31'),
(43, '20minutemail.it', '2020-09-03 20:16:58', '2020-09-03 20:16:58'),
(44, 'techgroup.top', '2020-09-03 20:17:07', '2020-09-03 20:17:07'),
(46, 'mail-cart.com', '2020-09-03 20:17:27', '2020-09-03 20:17:27'),
(47, 'tmpmail.org', '2020-09-03 20:17:38', '2020-09-03 20:17:38'),
(49, 'awdrt.org', '2020-09-04 15:34:29', '2020-09-04 15:34:29'),
(51, 'sanitzr.com', '2020-09-05 17:38:34', '2020-09-05 17:38:34'),
(53, 'mimpi99.com', '2020-09-06 16:35:14', '2020-09-06 16:35:14'),
(54, 'gomaild.com', '2020-09-13 18:28:24', '2020-09-13 18:28:24'),
(55, 'temp-mail.org', '2020-09-13 22:24:55', '2020-09-13 22:24:55'),
(56, 'kraftsucht.de', '2020-09-13 22:26:01', '2020-09-13 22:26:01'),
(57, 'ksv-systems.de', '2020-09-13 22:26:08', '2020-09-13 22:26:08'),
(58, 'kraftsucht.eu', '2020-09-13 22:27:50', '2020-09-13 22:27:50'),
(59, 'outree.org', '2020-09-15 12:52:37', '2020-09-15 12:52:37'),
(60, 'yeezus.ru', '2020-09-20 15:23:54', '2020-09-20 15:23:54'),
(61, 'gridmauk.com', '2020-09-20 19:08:46', '2020-09-20 19:08:46'),
(62, 'yevme.com', '2020-09-20 19:09:48', '2020-09-20 19:09:48'),
(63, 'a6mail.net', '2020-09-20 19:10:21', '2020-09-20 19:10:21'),
(64, 'onmail3.com', '2020-09-20 19:10:49', '2020-09-20 19:10:49'),
(72, 'gergerg.de', '2020-09-27 19:14:27', '2020-09-27 19:14:27'),
(73, 'funkspiel-schleswigholstein.de', '2020-10-06 21:17:32', '2020-10-06 21:17:32'),
(74, 'funkspiel-florian-hamburg.de', '2020-10-06 21:18:45', '2020-10-06 21:18:45'),
(75, 'mailsac.com', '2020-10-25 22:02:05', '2020-10-25 22:02:05'),
(76, 'prekab.net', '2020-10-25 22:02:24', '2020-10-25 22:02:24'),
(78, '	bcaoo.com', '2020-11-01 16:01:51', '2020-11-01 16:01:51'),
(80, 'bk.ru', '2020-11-02 12:05:32', '2020-11-02 12:05:32'),
(81, 'einsdany.de', '2020-11-04 21:03:57', '2020-11-04 21:03:57'),
(82, 'visionhost24.eu', '2020-11-19 21:00:52', '2020-11-19 21:00:52'),
(83, 'visionhost24.de', '2020-11-19 21:01:08', '2020-11-19 21:01:08'),
(84, 'winofilab24.de', '2020-11-19 21:01:38', '2020-11-19 21:01:38'),
(85, 'cuoly.com', '2020-11-29 16:44:41', '2020-11-29 16:44:41');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_info` varchar(255) NOT NULL,
  `key` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `password_resets`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_prices`
--

CREATE TABLE `product_prices` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `product_prices`
--

INSERT INTO `product_prices` (`id`, `name`, `price`, `created_at`, `updated_at`) VALUES
(2, 'TEASPEAK', '0.12', '2020-08-02 22:39:54', '2020-12-06 11:59:20');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `login` int(11) NOT NULL,
  `register` int(11) NOT NULL,
  `wartung` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `settings`
--

INSERT INTO `settings` (`id`, `login`, `register`, `wartung`) VALUES
(1, 1, 1, 0);

-- --------------------------------------------------------




-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `teaspeaks`
--

CREATE TABLE `teaspeaks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `slots` int(11) NOT NULL,
  `node_id` int(11) NOT NULL,
  `teaspeak_ip` varchar(255) NOT NULL,
  `teaspeak_port` varchar(255) NOT NULL,
  `sid` int(11) NOT NULL,
  `expire_at` datetime NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `state` enum('ACTIVE','SUSPENDED','DELETED') NOT NULL,
  `days` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `teaspeaks`
--



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `teaspeak_hosts`
--

CREATE TABLE `teaspeak_hosts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `login_ip` varchar(255) NOT NULL,
  `login_port` varchar(255) NOT NULL,
  `login_name` varchar(255) NOT NULL,
  `login_passwort` varchar(255) NOT NULL,
  `status` enum('ACTIVE','DISABLED') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `teaspeak_hosts`
--

INSERT INTO `teaspeak_hosts` (`id`, `name`, `login_ip`, `login_port`, `login_name`, `login_passwort`, `status`) VALUES
(1, 'teaspeak Server', 'tea.wino-voice.de', '10101', 'serveradmin', '******', 'ACTIVE');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `categorie` varchar(255) NOT NULL,
  `priority` varchar(255) NOT NULL,
  `produkt` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `last_msg` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `tickets`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ticket_message`
--

CREATE TABLE `ticket_message` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `writer_id` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `ticket_message`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gateway` varchar(255) NOT NULL,
  `state` enum('pending','success') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `tid` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `transactions`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `state` enum('pending','active','hv') NOT NULL,
  `role` enum('customer','support','p_support','techniker','content','admin') NOT NULL,
  `plesk_uid` varchar(255) DEFAULT NULL,
  `plesk_password` varchar(255) DEFAULT NULL,
  `session_token` varchar(255) DEFAULT NULL,
  `verify_code` varchar(255) DEFAULT NULL,
  `user_addr` varchar(255) DEFAULT NULL,
  `support_pin` varchar(255) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `affiliate_id` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `postcode` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `login_notify` int(11) NOT NULL DEFAULT 1,
  `transaction_notify` int(11) NOT NULL DEFAULT 1,
  `product_notify` int(11) NOT NULL DEFAULT 1,
  `support_notify` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `users`
--



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_transactions`
--

CREATE TABLE `user_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `desc` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `user_transactions`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `voucher_codes`
--

CREATE TABLE `voucher_codes` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `voucher_codes`
--

-- --------------------------------------------------------

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `affiliates`
--
ALTER TABLE `affiliates`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `affiliate_clicks`
--
ALTER TABLE `affiliate_clicks`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `mail_blacklist`
--
ALTER TABLE `mail_blacklist`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `product_prices`
--
ALTER TABLE `product_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);



--
-- Indizes für die Tabelle `teaspeaks`
--
ALTER TABLE `teaspeaks`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `teaspeak_hosts`
--
ALTER TABLE `teaspeak_hosts`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_2` (`id`),
  ADD KEY `id` (`id`);

--
-- Indizes für die Tabelle `ticket_message`
--
ALTER TABLE `ticket_message`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `user_transactions`
--
ALTER TABLE `user_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `voucher_codes`
--
ALTER TABLE `voucher_codes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `affiliates`
--
ALTER TABLE `affiliates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `affiliate_clicks`
--
ALTER TABLE `affiliate_clicks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT für Tabelle `mail_blacklist`
--
ALTER TABLE `mail_blacklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT für Tabelle `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT für Tabelle `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT für Tabelle `product_prices`
--
ALTER TABLE `product_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


--
-- AUTO_INCREMENT für Tabelle `teaspeaks`
--
ALTER TABLE `teaspeaks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `teaspeak_hosts`
--
ALTER TABLE `teaspeak_hosts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT für Tabelle `ticket_message`
--
ALTER TABLE `ticket_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT für Tabelle `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT für Tabelle `user_transactions`
--
ALTER TABLE `user_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=675;

--
-- AUTO_INCREMENT für Tabelle `voucher_codes`
--
ALTER TABLE `voucher_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
