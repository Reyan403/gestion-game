-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 11 mars 2026 à 15:49
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_fiche_de_jeu`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `twitch_game_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`, `twitch_game_id`) VALUES
(17, 'RPG', '38202,21027,206793,71375'),
(18, 'FPS', '516575,32399,515025'),
(19, 'Course', '504461,33214,313554'),
(20, 'Horreur', '512710,115243,18834'),
(21, 'Aventure', '493057,497078,518204'),
(22, 'Action', '21779,509658,162502'),
(23, 'Battle Royale', '33214,511224,491487'),
(24, 'Open World', '32982,27471,167805');

-- --------------------------------------------------------

--
-- Structure de la table `commentary`
--

CREATE TABLE `commentary` (
  `id` int(11) NOT NULL,
  `description` longtext NOT NULL,
  `is_validated` tinyint(4) NOT NULL,
  `is_archived` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentary`
--

INSERT INTO `commentary` (`id`, `description`, `is_validated`, `is_archived`, `created_at`, `user_id`, `game_id`) VALUES
(10, 'Un jeu captivant avec une histoire immersive et des personnages attachants. L’aventure est prenante du début à la fin.', 0, 0, '2023-10-15 14:30:00', 16, 34),
(11, 'Les graphismes sont magnifiques et l’univers est très bien détaillé, mais la difficulté peut parfois sembler déséquilibrée.', 0, 0, '2023-11-02 09:15:00', 17, 41),
(12, 'Un gameplay dynamique et intuitif qui offre une excellente prise en main, même pour les nouveaux joueurs.', 1, 0, '2023-12-10 21:00:00', 16, 40),
(13, 'La bande-son est exceptionnelle et accompagne parfaitement les moments forts du jeu.', 0, 1, '2024-01-05 18:45:00', 17, 40),
(14, 'Le mode multijoueur ajoute une vraie plus-value avec des parties intenses et compétitives.', 0, 1, '2024-01-20 16:20:00', 17, 42),
(15, 'Malgré quelques bugs mineurs, l’expérience globale reste très agréable et divertissante.', 1, 0, '2023-10-15 12:30:00', 18, 34);

-- --------------------------------------------------------

--
-- Structure de la table `game`
--

CREATE TABLE `game` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `game`
--

INSERT INTO `game` (`id`, `title`, `description`, `image`) VALUES
(34, 'The Legend of Zelda: Breath of the Wild', 'Explorez le vaste royaume d’Hyrule en résolvant des énigmes, combattant des ennemis et découvrant des secrets cachés dans ce jeu d’aventure épique.', 'img/zelda.jpg'),
(35, 'Minecraft', 'Créez, explorez et survivez dans un monde ouvert composé de blocs, où l’imagination est la seule limite.', 'img/minecraft.jpg'),
(36, 'The Witcher 3: Wild Hunt', 'Incarnez Geralt de Riv, chasseur de monstres, dans un RPG riche en quêtes, choix moraux et combats intenses dans un univers médiéval fantastique.', 'img/the-witcher.webp'),
(37, 'Fortnite', 'Participez à des batailles multijoueur intenses jusqu’au dernier survivant, construisez des structures et défiez vos amis dans des combats dynamiques.', 'img/fortnite.jpg'),
(38, 'Cyberpunk 2077', 'Plongez dans Night City, une métropole futuriste, et personnalisez votre personnage pour accomplir des missions dans un monde ouvert cyberpunk.', 'img/Cyberpunk2077.webp'),
(39, 'Resident Evil Village', 'Plongez dans une horreur intense avec Ethan Winters qui doit survivre dans un village rempli de monstres et découvrir les secrets terrifiants de la famille Dimitrescu.', 'img/resident-evil.jpg'),
(40, 'Grand Theft Auto V (GTA V)', 'Vivez une aventure criminelle à Los Santos, avec missions scénarisées, exploration libre et une multitude d’activités dans un monde ouvert vivant.', 'img/GTA.avif'),
(41, 'Overwatch', 'Choisissez un héros aux compétences uniques et affrontez des équipes adverses dans des matchs rapides et tactiques.', 'img/overwatch.jpg'),
(42, 'Hollow Knight', 'Explorez les sombres et mystérieux royaumes de Hallownest, combattez des ennemis redoutables et découvrez l’histoire cachée de ce monde en 2D.', 'img/hollow-knight.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `game_category`
--

CREATE TABLE `game_category` (
  `game_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `game_category`
--

INSERT INTO `game_category` (`game_id`, `category_id`) VALUES
(34, 21),
(34, 22),
(35, 21),
(36, 17),
(36, 21),
(36, 22),
(37, 22),
(37, 23),
(38, 17),
(38, 18),
(38, 22),
(39, 20),
(40, 22),
(40, 24),
(41, 18),
(41, 22),
(42, 21);

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

CREATE TABLE `note` (
  `note_game` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `note`
--

INSERT INTO `note` (`note_game`, `game_id`, `user_id`) VALUES
(1, 34, 16),
(5, 34, 17),
(4, 34, 18),
(2, 40, 16),
(1, 40, 17),
(5, 40, 18),
(2, 41, 16),
(3, 41, 17),
(5, 41, 18);

-- --------------------------------------------------------

--
-- Structure de la table `right`
--

CREATE TABLE `right` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `right`
--

INSERT INTO `right` (`id`, `name`) VALUES
(38, 'Attribuer un rôle'),
(35, 'Création d\'un rôle'),
(27, 'Création de fiche de jeu'),
(31, 'Créer un commentaire'),
(36, 'Modification d\'un rôle'),
(28, 'Modification d\'une fiche de jeu'),
(33, 'Suppression d\'un commentaire'),
(37, 'Suppression d\'un rôle'),
(34, 'Suppression d\'un utilisateur'),
(29, 'Suppression d\'une fiche de jeu'),
(32, 'Validation d\'un commentaire'),
(30, 'Validation d\'une fiche de jeu'),
(39, 'Voir les rôles');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `symfony_role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id`, `name`, `symfony_role`) VALUES
(13, 'Modérateur', 'ROLE_MODERATOR'),
(14, 'Administrateur', 'ROLE_ADMIN'),
(15, 'Rédacteur', 'ROLE_EDITOR'),
(16, 'Utilisateur', 'ROLE_USER');

-- --------------------------------------------------------

--
-- Structure de la table `role_right`
--

CREATE TABLE `role_right` (
  `role_id` int(11) NOT NULL,
  `right_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role_right`
--

INSERT INTO `role_right` (`role_id`, `right_id`) VALUES
(13, 28),
(14, 27),
(14, 28),
(14, 29),
(14, 30),
(14, 31),
(14, 32),
(14, 33),
(14, 34),
(14, 35),
(14, 36),
(14, 37),
(14, 38),
(14, 39),
(15, 27),
(15, 29),
(15, 30),
(15, 31),
(15, 32),
(15, 33);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `name`, `mail`, `password`) VALUES
(13, 'admin', 'admin@example.com', '$2y$13$uB5nBzP6ngHuuNl62iH3.OTM/yDoQqi13a/gcebQjRdr2bYkHGHaC'),
(14, 'moderateur', 'moderateur@example.com', '$2y$13$3CcVNt6.cofEPFxK38GFWOaJrjyVS3bNp43FCXtTQj.EpnKGb6DV2'),
(15, 'redacteur', 'redacteur@example.com', '$2y$13$wtJyGrHUJE7JOLGuyWsbju5m3tFndIc8Z1vYJD/K4KnwqDc8R7tZS'),
(16, 'Chloé Petit', 'chloe.petit@example.com', '$2y$13$J1mHQqHXEBtUVdO2JMjmButeiV5xgrJLQN1weYGuvX2kTOV3ELWyC'),
(17, 'Nathan Robert', 'nathan.robert@example.com', '$2y$13$3GxssW8aIqlkwu.0x5L/0.frzgTKaPsJIu7dfBTL.NAsOJR20gmIK'),
(18, 'Reyan Ghazzaoui', 'ghazzaoui.reyan@example.com', '$2y$13$6bwQkNsVBr9iwWehPDcYg.ZzIHa9qKdVy7Kic3xuAuJ3wELyoJ1su');

-- --------------------------------------------------------

--
-- Structure de la table `user_role`
--

CREATE TABLE `user_role` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_role`
--

INSERT INTO `user_role` (`user_id`, `role_id`) VALUES
(13, 14),
(14, 13),
(15, 15),
(16, 16),
(17, 16),
(18, 16);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_64C19C15E237E06` (`name`);

--
-- Index pour la table `commentary`
--
ALTER TABLE `commentary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1CAC12CAA76ED395` (`user_id`),
  ADD KEY `IDX_1CAC12CAE48FD905` (`game_id`);

--
-- Index pour la table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_232B318C2B36786B` (`title`);

--
-- Index pour la table `game_category`
--
ALTER TABLE `game_category`
  ADD PRIMARY KEY (`game_id`,`category_id`),
  ADD KEY `IDX_AD08E6E7E48FD905` (`game_id`),
  ADD KEY `IDX_AD08E6E712469DE2` (`category_id`);

--
-- Index pour la table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`game_id`,`user_id`),
  ADD KEY `IDX_CFBDFA14E48FD905` (`game_id`),
  ADD KEY `IDX_CFBDFA14A76ED395` (`user_id`);

--
-- Index pour la table `right`
--
ALTER TABLE `right`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B4CA75145E237E06` (`name`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_57698A6A5E237E06` (`name`),
  ADD UNIQUE KEY `UNIQ_57698A6A940F5A39` (`symfony_role`);

--
-- Index pour la table `role_right`
--
ALTER TABLE `role_right`
  ADD PRIMARY KEY (`role_id`,`right_id`),
  ADD KEY `IDX_43169D3BD60322AC` (`role_id`),
  ADD KEY `IDX_43169D3B54976835` (`right_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D6495126AC48` (`mail`);

--
-- Index pour la table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `IDX_2DE8C6A3A76ED395` (`user_id`),
  ADD KEY `IDX_2DE8C6A3D60322AC` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `commentary`
--
ALTER TABLE `commentary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `game`
--
ALTER TABLE `game`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `right`
--
ALTER TABLE `right`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentary`
--
ALTER TABLE `commentary`
  ADD CONSTRAINT `FK_1CAC12CAA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_1CAC12CAE48FD905` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`);

--
-- Contraintes pour la table `game_category`
--
ALTER TABLE `game_category`
  ADD CONSTRAINT `FK_AD08E6E712469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_AD08E6E7E48FD905` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `FK_CFBDFA14A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_CFBDFA14E48FD905` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`);

--
-- Contraintes pour la table `role_right`
--
ALTER TABLE `role_right`
  ADD CONSTRAINT `FK_43169D3B54976835` FOREIGN KEY (`right_id`) REFERENCES `right` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_43169D3BD60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `FK_2DE8C6A3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_2DE8C6A3D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
