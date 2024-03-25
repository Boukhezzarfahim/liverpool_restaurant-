-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 25 mars 2024 à 10:25
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `liverpool`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id_categorie` int(11) NOT NULL AUTO_INCREMENT,
  `nom_categorie` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id_categorie`, `nom_categorie`) VALUES
(43, 'Pizza'),
(44, 'Burger\'s'),
(45, 'Tacos'),
(46, 'Plats'),
(47, 'Boisson'),
(48, 'Dessert');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id_commande` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `telephone` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `commentaire` varchar(255) NOT NULL,
  `statut` varchar(255) NOT NULL DEFAULT 'en attente',
  `etat` varchar(20) NOT NULL,
  PRIMARY KEY (`id_commande`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `id_user`, `adresse`, `nom`, `telephone`, `date`, `commentaire`, `statut`, `etat`) VALUES
(59, 52, 'alger', 'fahim', 552545588, '2024-03-25 11:18:25', 'azul !', ' Commande approuvée  ', 'actif');

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `comment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `comment_content` text,
  `rating` int(11) DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`comment_id`, `user_id`, `comment_date`, `comment_content`, `rating`) VALUES
(11, 52, '2024-03-25 10:19:48', 'Service magnifique !', 4);

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `id_produit` int(11) NOT NULL AUTO_INCREMENT,
  `nom_produit` varchar(255) NOT NULL,
  `description` text,
  `image` varchar(255) NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `disponibilite` varchar(25) NOT NULL,
  `statut` varchar(255) NOT NULL,
  PRIMARY KEY (`id_produit`),
  KEY `fk_produits_categories` (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id_produit`, `nom_produit`, `description`, `image`, `id_categorie`, `disponibilite`, `statut`) VALUES
(73, 'Pizza végétariane', 'Déliciseusee', 'OIP.jpg', 43, '1', 'actif'),
(74, 'Muffinssss', 'Délicieux', '4.jpeg', 48, '0', 'actif'),
(75, 'Pancake', 'Le pancake, également appelé crêpe épaisse ou hotcake, est une spécialité culinaire originaire d\'Amérique du Nord.', '8.jpeg', 48, '1', 'actif'),
(76, 'Tacos', 'Méxicaine Tacos', 'side-view-shawarma-with-fried-potatoes-board-cookware.jpg', 45, '1', 'actif'),
(77, 'sss', 'hghghgh', 'liverpoll (1).jpg', 44, '1', 'actif');

-- --------------------------------------------------------

--
-- Structure de la table `produits_tailles`
--

DROP TABLE IF EXISTS `produits_tailles`;
CREATE TABLE IF NOT EXISTS `produits_tailles` (
  `id_produit` int(11) NOT NULL,
  `id_taille` int(11) NOT NULL,
  `prix_taille` float NOT NULL,
  `quantite` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_produit`,`id_taille`),
  KEY `id_taille` (`id_taille`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `produits_tailles`
--

INSERT INTO `produits_tailles` (`id_produit`, `id_taille`, `prix_taille`, `quantite`) VALUES
(73, 74, 2500, 20),
(73, 75, 1850, 15),
(73, 76, 1200, 10),
(74, 74, 650, 20),
(74, 75, 350, 30),
(74, 76, 200, 15),
(75, 74, 500, 20),
(75, 75, 450, 300),
(75, 76, 250, 450),
(77, 74, 550, 20);

-- --------------------------------------------------------

--
-- Structure de la table `produit_commande`
--

DROP TABLE IF EXISTS `produit_commande`;
CREATE TABLE IF NOT EXISTS `produit_commande` (
  `id_produit_commande` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` int(11) NOT NULL,
  `taille` varchar(255) NOT NULL,
  PRIMARY KEY (`id_produit_commande`),
  KEY `id_commande` (`id_commande`),
  KEY `produit_commande_ibfk_2` (`id_produit`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `produit_commande`
--

INSERT INTO `produit_commande` (`id_produit_commande`, `id_commande`, `id_produit`, `quantite`, `prix_unitaire`, `taille`) VALUES
(59, 59, 75, 1, 500, 'Grande'),
(60, 59, 73, 1, 2500, 'Grande');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `role_id` int(5) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`role_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`role_id`) VALUES
(1),
(2);

-- --------------------------------------------------------

--
-- Structure de la table `tailles`
--

DROP TABLE IF EXISTS `tailles`;
CREATE TABLE IF NOT EXISTS `tailles` (
  `id_taille` int(11) NOT NULL AUTO_INCREMENT,
  `nom_taille` varchar(255) NOT NULL,
  PRIMARY KEY (`id_taille`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `tailles`
--

INSERT INTO `tailles` (`id_taille`, `nom_taille`) VALUES
(74, 'Grande'),
(75, 'Moyenne'),
(76, 'petite'),
(77, 'Standard');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `uidUsers` tinytext NOT NULL,
  `emailUsers` tinytext NOT NULL,
  `pwdUsers` longtext NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role_id` int(11) NOT NULL DEFAULT '1',
  `is_blocked` tinyint(4) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `uidUsers`, `emailUsers`, `pwdUsers`, `reg_date`, `role_id`, `is_blocked`) VALUES
(51, 'Fahim60', 'fahim60@gmail.com', '$2y$10$SgAR68Y5FnavuFfaarO9Lep26BvoMKVwDDOF10JARAyJ44TEyDSgG', '2024-03-25 10:06:33', 2, 0),
(52, 'Fahim70', 'Fahim70@gmail.com', '$2y$10$ihgI3NljFV1y3DNoqtlA6OrPqxDqCNGvyFglu3ulljEYkwAqBSMPO', '2024-03-25 10:17:33', 1, 0);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `fk_produits_categories` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id_categorie`);

--
-- Contraintes pour la table `produits_tailles`
--
ALTER TABLE `produits_tailles`
  ADD CONSTRAINT `produits_tailles_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE,
  ADD CONSTRAINT `produits_tailles_ibfk_2` FOREIGN KEY (`id_taille`) REFERENCES `tailles` (`id_taille`) ON DELETE CASCADE;

--
-- Contraintes pour la table `produit_commande`
--
ALTER TABLE `produit_commande`
  ADD CONSTRAINT `produit_commande_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produit_commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`) ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
