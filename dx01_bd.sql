-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+deb12u1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 26 déc. 2025 à 00:39
-- Version du serveur : 10.11.14-MariaDB-0+deb12u2
-- Version de PHP : 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dx01_bd`
--

-- --------------------------------------------------------

--
-- Structure de la table `Chapter`
--

CREATE TABLE `Chapter` (
  `id` int(11) NOT NULL,
  `titre` varchar(128) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `monster_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Chapter`
--

INSERT INTO `Chapter` (`id`, `titre`, `content`, `image`, `monster_id`) VALUES
(1, 'Le réveil dans la taverne', 'Vous ouvrez les yeux dans une taverne sombre et enfumée. Votre tête vous lance et vous ne vous souvenez de rien. Le tavernier s\'approche et vous tend une lettre cachetée à votre nom. \"Ceci est arrivé pour vous ce matin,\" dit-il d\'un air mystérieux. La lettre parle d\'un trésor caché dans les ruines au nord de la ville. Que faites-vous ?', 'https://via.placeholder.com/600x400/8B4513/FFFFFF?text=Taverne', NULL),
(2, 'La forêt sombre', 'Vous vous enfoncez dans la forêt qui mène aux ruines. Les arbres sont si serrés que la lumière peine à passer. Soudain, vous entendez un grognement. Un loup sauvage vous barre la route, les crocs découverts. Vous devez combattre !', 'https://via.placeholder.com/600x400/228B22/FFFFFF?text=Forêt', NULL),
(4, 'L\'entrée des ruines', 'Après avoir vaincu le loup, vous arrivez devant d\'imposantes ruines de pierre. Une grande porte en bois vermoulu est entrouverte. Des inscriptions anciennes sont gravées au-dessus : \"Seuls les braves trouveront ce qu\'ils cherchent\". Vous poussez la porte et entrez dans l\'obscurité.', 'https://via.placeholder.com/600x400/696969/FFFFFF?text=Ruines', NULL),
(5, 'La salle aux gobelins', 'La première salle est infestée de gobelins ! Ils vous repèrent immédiatement et se précipitent vers vous en poussant des cris stridents. Préparez-vous au combat !', 'https://via.placeholder.com/600x400/556B2F/FFFFFF?text=Gobelins', NULL),
(6, 'Le marchand mystérieux', 'Au détour d\'un couloir, vous tombez sur un marchand itinérant installé au milieu des ruines. \"Ah ! Un aventurier ! J\'ai ce qu\'il vous faut pour survivre ici. Jetez un œil à ma marchandise !\" Il vous montre ses potions et ses armes.', 'https://via.placeholder.com/600x400/FFD700/000000?text=Marchand', NULL),
(7, 'La chambre du trésor', 'Vous atteignez enfin la chambre du trésor ! Des coffres brillent dans la pénombre. Mais alors que vous vous approchez, un dragon rouge émerge de l\'ombre, gardien séculaire de ces richesses. Le combat final commence !', 'https://via.placeholder.com/600x400/DC143C/FFFFFF?text=Dragon', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `Chapter_Treasure`
--

CREATE TABLE `Chapter_Treasure` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Chapter_Treasure`
--

INSERT INTO `Chapter_Treasure` (`id`, `chapter_id`, `item_id`, `quantity`) VALUES
(1, 4, 60, 1),
(2, 5, 50, 2),
(3, 7, 62, 1),
(4, 7, 63, 1),
(5, 7, 51, 3);

-- --------------------------------------------------------

--
-- Structure de la table `Class`
--

CREATE TABLE `Class` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `base_pv` int(11) NOT NULL,
  `base_mana` int(11) NOT NULL,
  `strength` int(11) NOT NULL,
  `initiative` int(11) NOT NULL,
  `max_items` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Class`
--

INSERT INTO `Class` (`id`, `name`, `description`, `base_pv`, `base_mana`, `strength`, `initiative`, `max_items`) VALUES
(1, 'Guerrier', 'Maître des armes et du combat rapproché, le guerrier possède une résistance exceptionnelle et une force redoutable.', 15, 0, 5, 3, 8),
(2, 'Mage', 'Manipulateur des arcanes, le mage utilise la magie pour détruire ses ennemis à distance. Fragile mais puissant.', 8, 20, 2, 4, 6),
(3, 'Voleur', 'Agile et rusé, le voleur excelle dans la discrétion et les attaques rapides. Il peut transporter plus d\'objets que les autres.', 10, 5, 3, 7, 12);

-- --------------------------------------------------------

--
-- Structure de la table `Encounter`
--

CREATE TABLE `Encounter` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `monster_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Encounter`
--

INSERT INTO `Encounter` (`id`, `chapter_id`, `monster_id`) VALUES
(1, 2, 2),
(2, 5, 1),
(3, 5, 1),
(4, 6, 11),
(5, 7, 9);

-- --------------------------------------------------------

--
-- Structure de la table `Hero`
--

CREATE TABLE `Hero` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `biography` text DEFAULT 'Pas d\'histoire',
  `pv` int(11) NOT NULL,
  `mana` int(11) NOT NULL,
  `strength` int(11) NOT NULL,
  `initiative` int(11) NOT NULL,
  `armor_item_id` int(11) DEFAULT NULL,
  `primary_weapon_item_id` int(11) DEFAULT NULL,
  `secondary_weapon_item_id` int(11) DEFAULT NULL,
  `shield_item_id` int(11) DEFAULT NULL,
  `xp` int(11) NOT NULL,
  `current_level` int(11) DEFAULT 1,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Hero`
--

INSERT INTO `Hero` (`id`, `name`, `class_id`, `image`, `biography`, `pv`, `mana`, `strength`, `initiative`, `armor_item_id`, `primary_weapon_item_id`, `secondary_weapon_item_id`, `shield_item_id`, `xp`, `current_level`, `user_id`) VALUES
(16, 'Haxyos', 1, 'https://via.placeholder.com/300x400/1a1a1a/f2a900?text=Hero', 'je suis le hero le plus fort', 10, 0, 0, 10, 40, 10, NULL, 30, 0, 1, 12),
(18, 'hell', 1, 'https://via.placeholder.com/300x400/1a1a1a/f2a900?text=Hero', 'halla', 10, 0, 0, 3, 40, 10, NULL, 30, 0, 1, NULL),
(19, 'test', 1, 'https://via.placeholder.com/300x400/1a1a1a/f2a900?text=Hero', 'test', 10, 0, 0, 4, 43, 13, NULL, 31, 0, 1, NULL),
(22, 'bonjour', 1, 'https://via.placeholder.com/300x400/1a1a1a/f2a900?text=Hero', 'hello', 10, 0, 0, 0, 40, 10, NULL, 30, 0, 1, 12),
(23, 'hell', 3, 'https://via.placeholder.com/300x400/1a1a1a/f2a900?text=Hero', 'f', 8, 0, 0, 10, 44, 13, NULL, NULL, 0, 1, 12),
(24, 'hell', 3, 'https://via.placeholder.com/300x400/1a1a1a/f2a900?text=Hero', 'f', 8, 0, 0, 10, 44, 13, NULL, NULL, 0, 1, 12),
(25, 'test', 1, 'https://via.placeholder.com/300x400/1a1a1a/f2a900?text=Hero', 'test', 10, 0, 0, 0, 40, 10, NULL, 30, 0, 1, 12),
(26, 'hell', 1, 'https://via.placeholder.com/300x400/1a1a1a/f2a900?text=Hero', 'efe', 10, 0, 0, 0, 40, 10, NULL, 30, 0, 1, 12),
(27, 'guerrier 1', 1, 'https://via.placeholder.com/300x400/1a1a1a/f2a900?text=Hero', 'jtebasie', 6, 4, 3, 0, 40, 10, NULL, NULL, 0, 1, 12),
(28, 'celui qui saoule', 2, 'https://via.placeholder.com/300x400/1a1a1a/f2a900?text=Hero', 'blabla', 6, 4, 3, 0, 43, 14, NULL, NULL, 0, 1, 12),
(31, 'Guerrier', 1, '/images/guerrier.png', 'Son passé', 6, 4, 3, 0, 40, 10, NULL, 30, 0, 1, 11),
(32, 'TEST', 2, '/images/sorcier.png', 'ergergerg', 6, 4, 3, 3, 43, 14, NULL, NULL, 0, 1, 11),
(33, 'I Miss Him', 3, '/images/voleur.png', 'cc', 6, 4, 3, 8, 44, 13, NULL, NULL, 0, 1, 3),
(34, 'test', 1, '/images/guerrier.png', 'gegerg', 6, 4, 3, 0, 40, 10, NULL, 30, 0, 1, 11);

-- --------------------------------------------------------

--
-- Structure de la table `Hero_Progress`
--

CREATE TABLE `Hero_Progress` (
  `id` int(11) NOT NULL,
  `hero_id` int(11) DEFAULT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Completed',
  `completion_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Hero_Progress`
--

INSERT INTO `Hero_Progress` (`id`, `hero_id`, `chapter_id`, `status`, `completion_date`) VALUES
(1, 31, 1, 'In Progress', '2025-12-16 11:29:13'),
(2, 32, 1, 'In Progress', '2025-12-16 11:33:01'),
(3, 33, 1, 'In Progress', '2025-12-16 11:33:46'),
(4, 34, 1, 'In Progress', '2025-12-26 00:37:07');

-- --------------------------------------------------------

--
-- Structure de la table `Inventory`
--

CREATE TABLE `Inventory` (
  `id` int(11) NOT NULL,
  `hero_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inventory_traits`
--

CREATE TABLE `inventory_traits` (
  `id` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `id_traits` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Items`
--

CREATE TABLE `Items` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `item_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Items`
--

INSERT INTO `Items` (`id`, `name`, `description`, `item_type`) VALUES
(2, 'Sans arme', 'Aucune arme équipée', 'ARME'),
(3, 'Sans bouclier', 'Aucun bouclier équipé', 'BOUCLIER'),
(10, 'Épée courte', 'Une lame simple mais efficace. +2 Force', 'ARME'),
(11, 'Épée longue', 'Une épée de qualité supérieure. +4 Force', 'ARME'),
(12, 'Hache de guerre', 'Lourde et puissante. +5 Force, -1 Initiative', 'ARME'),
(13, 'Dague empoisonnée', 'Petite mais mortelle. +3 Force', 'ARME'),
(14, 'Bâton du sage', 'Augmente la puissance magique. +1 Force, +5 Mana', 'ARME'),
(15, 'Arc long', 'Pour attaquer à distance. +3 Force, +1 Initiative', 'ARME'),
(20, 'Dague de secours', 'Une arme légère de secours. +1 Force', 'ARME'),
(21, 'Hachette', 'Petite hache pratique. +2 Force', 'ARME'),
(30, 'Bouclier en bois', 'Protection basique. +2 PV', 'BOUCLIER'),
(31, 'Bouclier en fer', 'Solide protection. +4 PV', 'BOUCLIER'),
(32, 'Bouclier du chevalier', 'Le meilleur des boucliers. +6 PV', 'BOUCLIER'),
(40, 'Armure de cuir', 'Légère et flexible. +3 PV', 'ARMURE'),
(41, 'Cotte de mailles', 'Protection intermédiaire. +5 PV, -1 Initiative', 'ARMURE'),
(42, 'Armure de plaques', 'Protection maximale. +8 PV, -2 Initiative', 'ARMURE'),
(43, 'Robe de mage', 'Tissu enchanté. +2 PV, +3 Mana', 'ARMURE'),
(44, 'Tunique de voleur', 'Discrète et confortable. +2 PV, +2 Initiative', 'ARMURE'),
(50, 'Potion de soin', 'Restaure 5 PV', 'CONSOMMABLE'),
(51, 'Grande potion de soin', 'Restaure 10 PV', 'CONSOMMABLE'),
(52, 'Potion de mana', 'Restaure 5 Mana', 'CONSOMMABLE'),
(53, 'Grande potion de mana', 'Restaure 10 Mana', 'CONSOMMABLE'),
(54, 'Élixir de force', 'Augmente temporairement la force de 3', 'CONSOMMABLE'),
(55, 'Antidote', 'Soigne les empoisonnements', 'CONSOMMABLE'),
(60, 'Clé rouillée', 'Une vieille clé mystérieuse', 'QUETE'),
(61, 'Parchemin ancien', 'Contient des inscriptions étranges', 'QUETE'),
(62, 'Gemme bleue', 'Pierre précieuse brillante', 'QUETE'),
(63, 'Amulette protectrice', '+2 PV permanent', 'ACCESSOIRE'),
(64, 'Anneau de rapidité', '+2 Initiative permanent', 'ACCESSOIRE');

-- --------------------------------------------------------

--
-- Structure de la table `Level`
--

CREATE TABLE `Level` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `level` int(11) NOT NULL,
  `required_xp` int(11) NOT NULL,
  `pv_bonus` int(11) NOT NULL,
  `mana_bonus` int(11) NOT NULL,
  `strength_bonus` int(11) NOT NULL,
  `initiative_bonus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Level`
--

INSERT INTO `Level` (`id`, `class_id`, `level`, `required_xp`, `pv_bonus`, `mana_bonus`, `strength_bonus`, `initiative_bonus`) VALUES
(1, 1, 2, 50, 5, 0, 2, 1),
(2, 1, 3, 150, 5, 0, 2, 1),
(3, 1, 4, 300, 6, 0, 3, 1),
(4, 1, 5, 500, 6, 0, 3, 2),
(5, 2, 2, 50, 3, 8, 1, 1),
(6, 2, 3, 150, 3, 8, 1, 2),
(7, 2, 4, 300, 4, 10, 2, 2),
(8, 2, 5, 500, 4, 10, 2, 3),
(9, 3, 2, 50, 4, 2, 2, 2),
(10, 3, 3, 150, 4, 2, 2, 3),
(11, 3, 4, 300, 5, 3, 3, 3),
(12, 3, 5, 500, 5, 3, 3, 4);

-- --------------------------------------------------------

--
-- Structure de la table `Links`
--

CREATE TABLE `Links` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `next_chapter_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Links`
--

INSERT INTO `Links` (`id`, `chapter_id`, `next_chapter_id`, `description`) VALUES
(5, 5, 6, 'Après avoir vaincu les gobelins, explorer plus loin'),
(6, 6, 7, 'Acheter des provisions et continuer'),
(7, 5, 7, 'Prendre un passage secret découvert'),
(12, 2, 4, 'Continuer après le combat'),
(13, 1, 2, 'Suivre les instructions de la lettre et partir vers le nord'),
(14, 1, 6, 'Interroger le tavernier sur le marchand mentionné'),
(15, 4, 5, 'Entrer dans les ruines');

-- --------------------------------------------------------

--
-- Structure de la table `Monster`
--

CREATE TABLE `Monster` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `pv` int(11) NOT NULL,
  `mana` int(11) DEFAULT NULL,
  `initiative` int(11) NOT NULL,
  `strength` int(11) NOT NULL,
  `attack` text DEFAULT NULL,
  `xp` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `Hostilité` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Monster`
--

INSERT INTO `Monster` (`id`, `name`, `pv`, `mana`, `initiative`, `strength`, `attack`, `xp`, `image`, `Hostilité`) VALUES
(1, 'Gobelin', 5, 0, 4, 2, 'Frappe avec son gourdin', 10, 'https://via.placeholder.com/200/228B22/FFFFFF?text=Gobelin', 1),
(2, 'Loup sauvage', 6, 0, 6, 3, 'Morsure féroce', 15, 'https://via.placeholder.com/200/8B4513/FFFFFF?text=Loup', 1),
(3, 'Squelette', 4, 0, 3, 2, 'Coup d\'épée rouillée', 12, 'https://via.placeholder.com/200/DCDCDC/000000?text=Squelette', 1),
(4, 'Rat géant', 3, 0, 5, 1, 'Morsure infectée', 8, 'https://via.placeholder.com/200/696969/FFFFFF?text=Rat', 1),
(5, 'Orc guerrier', 12, 0, 4, 5, 'Hache de guerre', 30, 'https://via.placeholder.com/200/556B2F/FFFFFF?text=Orc', 1),
(6, 'Araignée géante', 8, 0, 7, 3, 'Morsure venimeuse', 25, 'https://via.placeholder.com/200/000000/FFFFFF?text=Araignée', 1),
(7, 'Sorcier noir', 10, 15, 5, 2, 'Sort de ténèbres', 40, 'https://via.placeholder.com/200/4B0082/FFFFFF?text=Sorcier', 1),
(8, 'Troll', 15, 0, 3, 6, 'Coup de massue', 45, 'https://via.placeholder.com/200/2F4F4F/FFFFFF?text=Troll', 1),
(9, 'Dragon rouge', 30, 20, 6, 10, 'Souffle de feu', 100, 'https://via.placeholder.com/200/DC143C/FFFFFF?text=Dragon', 1),
(10, 'Liche ancestrale', 25, 30, 7, 7, 'Magie noire ancienne', 120, 'https://via.placeholder.com/200/9370DB/FFFFFF?text=Liche', 1),
(11, 'Marchand itinérant', 10, 0, 2, 1, 'Aucune', 0, 'https://via.placeholder.com/200/FFD700/000000?text=Marchand', 0);

-- --------------------------------------------------------

--
-- Structure de la table `Monster_Loot`
--

CREATE TABLE `Monster_Loot` (
  `id` int(11) NOT NULL,
  `monster_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `drop_rate` decimal(5,2) DEFAULT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Monster_Loot`
--

INSERT INTO `Monster_Loot` (`id`, `monster_id`, `item_id`, `quantity`, `drop_rate`, `price`) VALUES
(1, 1, 50, 1, 50.00, NULL),
(2, 1, 13, 1, 20.00, NULL),
(3, 2, 50, 1, 40.00, NULL),
(4, 2, 40, 1, 15.00, NULL),
(5, 3, 10, 1, 30.00, NULL),
(6, 3, 50, 1, 35.00, NULL),
(7, 5, 51, 1, 60.00, NULL),
(8, 5, 11, 1, 25.00, NULL),
(9, 5, 12, 1, 15.00, NULL),
(10, 6, 55, 1, 70.00, NULL),
(11, 6, 13, 1, 20.00, NULL),
(12, 7, 52, 2, 80.00, NULL),
(13, 7, 43, 1, 30.00, NULL),
(14, 7, 61, 1, 50.00, NULL),
(15, 9, 51, 3, 100.00, NULL),
(16, 9, 53, 2, 100.00, NULL),
(17, 9, 12, 1, 80.00, NULL),
(18, 9, 42, 1, 60.00, NULL),
(19, 9, 63, 1, 100.00, NULL),
(20, 10, 51, 3, 100.00, NULL),
(21, 10, 53, 3, 100.00, NULL),
(22, 10, 14, 1, 90.00, NULL),
(23, 10, 64, 1, 100.00, NULL),
(24, 11, 50, 1, 100.00, 10),
(25, 11, 51, 1, 100.00, 25),
(26, 11, 52, 1, 100.00, 15),
(27, 11, 53, 1, 100.00, 35),
(28, 11, 10, 1, 100.00, 20),
(29, 11, 11, 1, 100.00, 50);

-- --------------------------------------------------------

--
-- Structure de la table `Posseder`
--

CREATE TABLE `Posseder` (
  `id_heros` int(11) NOT NULL,
  `id_traits` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Pouvoir`
--

CREATE TABLE `Pouvoir` (
  `id_heros` int(11) NOT NULL,
  `id_spell` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Pouvoir`
--

INSERT INTO `Pouvoir` (`id_heros`, `id_spell`) VALUES
(28, 1),
(28, 3),
(28, 7),
(32, 2),
(32, 3),
(32, 4);

-- --------------------------------------------------------

--
-- Structure de la table `Spell`
--

CREATE TABLE `Spell` (
  `id` int(11) NOT NULL,
  `cout_mana` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Spell`
--

INSERT INTO `Spell` (`id`, `cout_mana`, `nom`) VALUES
(1, 3, 'Boule de feu'),
(2, 5, 'Éclair de glace'),
(3, 2, 'Soin léger'),
(4, 8, 'Tempête de foudre'),
(5, 4, 'Bouclier magique'),
(6, 6, 'Téléportation'),
(7, 3, 'Détection de magie'),
(8, 10, 'Invocation mineure');

-- --------------------------------------------------------

--
-- Structure de la table `traits`
--

CREATE TABLE `traits` (
  `id` int(11) NOT NULL,
  `nom` varchar(32) DEFAULT NULL,
  `pointDeVie` int(11) DEFAULT NULL,
  `Strenght` int(11) DEFAULT NULL,
  `Initiative` int(11) DEFAULT NULL,
  `Mana` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `traits`
--

INSERT INTO `traits` (`id`, `nom`, `pointDeVie`, `Strenght`, `Initiative`, `Mana`) VALUES
(1, 'Robuste', 5, 0, 0, 0),
(2, 'Fort', 0, 3, 0, 0),
(3, 'Agile', 0, 0, 3, 0),
(4, 'Mystique', 0, 0, 0, 5),
(5, 'Équilibré', 2, 1, 1, 1),
(6, 'Régénération', 3, 0, 0, 2),
(7, 'Berserk', -2, 4, -1, 0),
(8, 'Érudit', 0, 0, 0, 8),
(9, 'Acrobate', 0, 0, 5, 0),
(10, 'Résilient', 8, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(32) DEFAULT NULL,
  `motDePasse` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `est_admin` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `motDePasse`, `email`, `est_admin`) VALUES
(3, 'Admin', '$2y$10$Kl1jATuAi9hnR7v75HGfKe59QCt2N4Kah4HlDlBa09KFPhhL36Ko6', 'admin@dungeonxplorer.fr', 1),
(11, 'Zulkran', '$2y$10$btQNUq2L6myLu8l8xxo32elAIGH4hSJXRotc7LBWRN7U/FRievyHC', 'ilhanmary427@gmail.com', 1),
(12, 'bidule', '$2y$10$SrxmUR2gMgdHWiOPSls0SevmwESYZxkyEqcwz6QcTGLr5g/ogvUH2', 'haxyos89@gmail.com', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Chapter`
--
ALTER TABLE `Chapter`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Chapter_Treasure`
--
ALTER TABLE `Chapter_Treasure`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chapter_id` (`chapter_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Index pour la table `Class`
--
ALTER TABLE `Class`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Encounter`
--
ALTER TABLE `Encounter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chapter_id` (`chapter_id`),
  ADD KEY `monster_id` (`monster_id`);

--
-- Index pour la table `Hero`
--
ALTER TABLE `Hero`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `armor_item_id` (`armor_item_id`),
  ADD KEY `primary_weapon_item_id` (`primary_weapon_item_id`),
  ADD KEY `secondary_weapon_item_id` (`secondary_weapon_item_id`),
  ADD KEY `shield_item_id` (`shield_item_id`),
  ADD KEY `fk_hero_users` (`user_id`);

--
-- Index pour la table `Hero_Progress`
--
ALTER TABLE `Hero_Progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hero_id` (`hero_id`),
  ADD KEY `chapter_id` (`chapter_id`);

--
-- Index pour la table `Inventory`
--
ALTER TABLE `Inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hero_id` (`hero_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Index pour la table `inventory_traits`
--
ALTER TABLE `inventory_traits`
  ADD PRIMARY KEY (`id`,`id_item`,`id_traits`),
  ADD KEY `fk_inventory_traits_item` (`id_item`),
  ADD KEY `fk_inventory_traits_traits` (`id_traits`);

--
-- Index pour la table `Items`
--
ALTER TABLE `Items`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Level`
--
ALTER TABLE `Level`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`);

--
-- Index pour la table `Links`
--
ALTER TABLE `Links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chapter_id` (`chapter_id`),
  ADD KEY `next_chapter_id` (`next_chapter_id`);

--
-- Index pour la table `Monster`
--
ALTER TABLE `Monster`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Monster_Loot`
--
ALTER TABLE `Monster_Loot`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `monster_id` (`monster_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Index pour la table `Posseder`
--
ALTER TABLE `Posseder`
  ADD PRIMARY KEY (`id_heros`,`id_traits`),
  ADD KEY `fk_posseder_traits` (`id_traits`);

--
-- Index pour la table `Pouvoir`
--
ALTER TABLE `Pouvoir`
  ADD PRIMARY KEY (`id_heros`,`id_spell`),
  ADD KEY `fk_spell_pouvoir` (`id_spell`);

--
-- Index pour la table `Spell`
--
ALTER TABLE `Spell`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `traits`
--
ALTER TABLE `traits`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Chapter_Treasure`
--
ALTER TABLE `Chapter_Treasure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `Class`
--
ALTER TABLE `Class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `Encounter`
--
ALTER TABLE `Encounter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `Hero`
--
ALTER TABLE `Hero`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `Hero_Progress`
--
ALTER TABLE `Hero_Progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `Inventory`
--
ALTER TABLE `Inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Items`
--
ALTER TABLE `Items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT pour la table `Level`
--
ALTER TABLE `Level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `Links`
--
ALTER TABLE `Links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `Monster`
--
ALTER TABLE `Monster`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `Monster_Loot`
--
ALTER TABLE `Monster_Loot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `traits`
--
ALTER TABLE `traits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Chapter_Treasure`
--
ALTER TABLE `Chapter_Treasure`
  ADD CONSTRAINT `Chapter_Treasure_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `Chapter` (`id`),
  ADD CONSTRAINT `Chapter_Treasure_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `Items` (`id`);

--
-- Contraintes pour la table `Encounter`
--
ALTER TABLE `Encounter`
  ADD CONSTRAINT `Encounter_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `Chapter` (`id`),
  ADD CONSTRAINT `Encounter_ibfk_2` FOREIGN KEY (`monster_id`) REFERENCES `Monster` (`id`);

--
-- Contraintes pour la table `Hero`
--
ALTER TABLE `Hero`
  ADD CONSTRAINT `Hero_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `Class` (`id`),
  ADD CONSTRAINT `Hero_ibfk_2` FOREIGN KEY (`armor_item_id`) REFERENCES `Items` (`id`),
  ADD CONSTRAINT `Hero_ibfk_3` FOREIGN KEY (`primary_weapon_item_id`) REFERENCES `Items` (`id`),
  ADD CONSTRAINT `Hero_ibfk_4` FOREIGN KEY (`secondary_weapon_item_id`) REFERENCES `Items` (`id`),
  ADD CONSTRAINT `Hero_ibfk_5` FOREIGN KEY (`shield_item_id`) REFERENCES `Items` (`id`),
  ADD CONSTRAINT `fk_hero_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `Hero_Progress`
--
ALTER TABLE `Hero_Progress`
  ADD CONSTRAINT `Hero_Progress_ibfk_1` FOREIGN KEY (`hero_id`) REFERENCES `Hero` (`id`),
  ADD CONSTRAINT `Hero_Progress_ibfk_2` FOREIGN KEY (`chapter_id`) REFERENCES `Chapter` (`id`);

--
-- Contraintes pour la table `Inventory`
--
ALTER TABLE `Inventory`
  ADD CONSTRAINT `Inventory_ibfk_1` FOREIGN KEY (`hero_id`) REFERENCES `Hero` (`id`),
  ADD CONSTRAINT `Inventory_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `Items` (`id`);

--
-- Contraintes pour la table `inventory_traits`
--
ALTER TABLE `inventory_traits`
  ADD CONSTRAINT `fk_inventory_traits_item` FOREIGN KEY (`id_item`) REFERENCES `Items` (`id`),
  ADD CONSTRAINT `fk_inventory_traits_traits` FOREIGN KEY (`id_traits`) REFERENCES `traits` (`id`);

--
-- Contraintes pour la table `Level`
--
ALTER TABLE `Level`
  ADD CONSTRAINT `Level_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `Class` (`id`);

--
-- Contraintes pour la table `Links`
--
ALTER TABLE `Links`
  ADD CONSTRAINT `Links_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `Chapter` (`id`),
  ADD CONSTRAINT `Links_ibfk_2` FOREIGN KEY (`next_chapter_id`) REFERENCES `Chapter` (`id`);

--
-- Contraintes pour la table `Monster_Loot`
--
ALTER TABLE `Monster_Loot`
  ADD CONSTRAINT `Monster_Loot_ibfk_1` FOREIGN KEY (`monster_id`) REFERENCES `Monster` (`id`),
  ADD CONSTRAINT `Monster_Loot_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `Items` (`id`);

--
-- Contraintes pour la table `Posseder`
--
ALTER TABLE `Posseder`
  ADD CONSTRAINT `fk_posseder_heros` FOREIGN KEY (`id_heros`) REFERENCES `Hero` (`id`),
  ADD CONSTRAINT `fk_posseder_traits` FOREIGN KEY (`id_traits`) REFERENCES `traits` (`id`);

--
-- Contraintes pour la table `Pouvoir`
--
ALTER TABLE `Pouvoir`
  ADD CONSTRAINT `fk_heros_pouvoir` FOREIGN KEY (`id_heros`) REFERENCES `Hero` (`id`),
  ADD CONSTRAINT `fk_spell_pouvoir` FOREIGN KEY (`id_spell`) REFERENCES `Spell` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
