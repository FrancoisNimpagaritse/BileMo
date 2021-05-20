-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  lun. 17 mai 2021 à 21:08
-- Version du serveur :  10.1.34-MariaDB
-- Version de PHP :  7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bilemo`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `name`) VALUES
(1, 'Europe Galaxy Phones tried 2021'),
(2, 'Forever mobile updated Twice --'),
(3, 'Extra Mobiles'),
(4, 'Techno Mobiles'),
(5, 'Uwezo Phone Services'),
(6, 'Uwezo Phone Services Ltd.'),
(7, 'Tanga-tanga Services Ltd.'),
(8, 'Forever mobile updated Twice'),
(9, 'Kenya TV Techno Mobile'),
(10, 'Europe Galaxy Phones Extra'),
(11, 'Lidl');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20210208120033', '2021-02-08 13:00:45', 547),
('DoctrineMigrations\\Version20210217225426', '2021-02-17 23:55:41', 749),
('DoctrineMigrations\\Version20210223190250', '2021-02-23 20:04:40', 163),
('DoctrineMigrations\\Version20210225102952', '2021-02-25 11:30:07', 1331);

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `description`) VALUES
(1, 'Perferendis laboriosam.', 160.92, 'Animi deserunt quo magnam iusto ducimus debitis est recusandae saepe et cum soluta sunt voluptas.'),
(2, 'Dolorem corrupti nesciunt.', 371.98, 'Sit ut blanditiis dolorum odit voluptatem est dignissimos qui sed quibusdam ut quo.'),
(3, 'In laborum quisquam.', 322.38, 'Est et eos asperiores rerum ut aut natus.'),
(4, 'Id suscipit.', 853.67, 'Maiores minus quod molestias officia sit deserunt dolores et.'),
(5, 'Dolore ipsum est.', 181.92, 'Laboriosam veniam aspernatur vero consectetur dolores est.'),
(6, 'Eligendi officiis voluptas.', 885.46, 'Veniam aut sit consequatur id et molestias aspernatur.'),
(7, 'Sed hic eum.', 975.77, 'Veritatis a fugit et tempora dolor quos ut blanditiis delectus voluptatem.'),
(8, 'Tenetur aut.', 313.64, 'Accusamus dolorum dolor sunt at ipsam quia.'),
(9, 'Consequuntur nihil.', 754.61, 'Officia vel iure facere non magni quisquam nulla rerum provident.'),
(10, 'Quia ut.', 619.91, 'Aut id molestias est qui quia non illo praesentium.'),
(11, 'Recusandae nesciunt.', 675.81, 'Dicta ab quae fuga placeat quibusdam non nesciunt voluptas architecto vitae fugiat sunt.'),
(12, 'Officiis nemo deleniti.', 966.67, 'Et consequatur vel beatae cumque quasi cumque optio hic ut.'),
(13, 'Neque eos.', 793.06, 'Saepe similique numquam et enim quis doloremque vero quisquam dicta voluptas.'),
(14, 'Tempora repellendus magnam.', 880.27, 'Hic sint placeat expedita nihil esse rerum amet iusto optio atque.'),
(15, 'Nemo repellat eligendi.', 366.28, 'Ut quis ut officia qui doloribus non.'),
(16, 'Quam placeat numquam.', 211.48, 'Nam alias voluptate aliquam cum deserunt et omnis quasi quidem incidunt alias autem.'),
(17, 'Quia enim.', 457.85, 'Dolorem cum sit nihil sit ullam molestiae optio tempore ea ducimus ut.'),
(18, 'Consequatur harum.', 904.96, 'Omnis fugiat rerum nostrum molestiae cumque asperiores facere vel asperiores ipsum.'),
(19, 'Est quas.', 958.14, 'Cum quia sapiente saepe officiis ut accusamus expedita dolores officia.'),
(20, 'Sapiente corrupti nesciunt.', 596.62, 'Voluptas iusto libero tempora aliquam labore unde voluptates modi consequatur ab commodi.');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `client_id`, `email`, `password`, `name`, `role`) VALUES
(1, 1, 'franimpa@yahoo.fr', '$argon2i$v=19$m=65536,t=4,p=1$QnNtT2xsbGRlQ2txTjY1NA$ltAsupcZBaQcb5k9A7xti6WpwC0DtmwWvefuXuTLLWs', 'Franceso Totti', 'ROLE_ADMIN'),
(2, 1, 'zkulas@hotmail.com', '$argon2i$v=19$m=65536,t=4,p=1$QnNtT2xsbGRlQ2txTjY1NA$ltAsupcZBaQcb5k9A7xti6WpwC0DtmwWvefuXuTLLWs', 'Linda Schumm', 'ROLE_ADMIN'),
(3, 1, 'gerlach.cordelia@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$U3VVVGQzMXpJSUd4V1NYNA$J2aw/daskrLRyQo8QmSmfoauyFg14LzU48EEHpPyyS4', 'Elvera Harvey', ''),
(9, 2, 'blabla@hotmail.com', 'test', 'Tchikaya Utamsi', ''),
(10, 3, 'amusement@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$ZUVYVS5IL2NLbXY3QUU1OQ$FefSxXAQv6dW23gd6Rz39cZNTLSYvAGe+DH6BLmcAeM', 'Alian Makaba', ''),
(12, 4, 'blabla2@hotmail.com', '$argon2i$v=19$m=65536,t=4,p=1$dFhTdVJlYVlvYTlPNTVKVQ$vo88Pn7fXW9SPo7YFRElxom9ZHcxY2VUFMazCs7uEVw', 'Tchikaya Utamsi Joo', ''),
(13, 4, 'testLundi@yahoo.fr', '$argon2i$v=19$m=65536,t=4,p=1$MlMwaDgyT1FWYkVkRVM2Tg$zSsVyVwT1701g8I7s6LMCwZFKIGX9rKM0fesJL2JT7U', 'Monday Test', 'ROLE_USER'),
(14, 1, 'testLundiSameclient@yahoo.fr', '$argon2i$v=19$m=65536,t=4,p=1$b0xhazVzNXhxcTBUZW1oRg$AI257fW0OjLnEKPNATHPYujRxjPxcbySCBptgOrvPiw', 'Monday Evening Test', 'ROLE_USER');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD KEY `IDX_8D93D64919EB6921` (`client_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D64919EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
