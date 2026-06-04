-- ============================================================
-- ACCESS INFORMATIQUE — SCHÉMA DE BASE DE DONNÉES
-- Version : 1.0 | Moteur : MySQL 8+ | Encodage : utf8mb4
-- ============================================================
-- Ce fichier crée toutes les tables dans l'ordre correct
-- (les tables sans dépendances en premier).
-- Exécuter UNE SEULE FOIS sur une base vide.
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- BASE DE DONNÉES
-- Décommenter si la base n'existe pas encore
-- ------------------------------------------------------------
-- CREATE DATABASE IF NOT EXISTS `access_informatique`
--   CHARACTER SET utf8mb4
--   COLLATE utf8mb4_unicode_ci;
-- USE `access_informatique`;

-- ============================================================
-- TABLE : admins
-- Comptes administrateurs autorisés à accéder au dashboard
-- ============================================================
CREATE TABLE IF NOT EXISTS `admins` (
  `id`            INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `name`          VARCHAR(100)  NOT NULL,
  `email`         VARCHAR(150)  NOT NULL,
  `password_hash` VARCHAR(255)  NOT NULL COMMENT 'Haché via password_hash() PHP',
  `created_at`    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_admin_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : contents
-- Textes statiques du site modifiables depuis le dashboard.
-- Chaque ligne = un texte identifiable par (page + key_name).
-- Le frontend Vue.js charge ces textes via l'API publique.
-- ============================================================
CREATE TABLE IF NOT EXISTS `contents` (
  `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `page`       VARCHAR(50)   NOT NULL COMMENT 'Ex : home, about, contact, formation, hackathon, global',
  `key_name`   VARCHAR(100)  NOT NULL COMMENT 'Ex : hero.title, cta.button',
  `label`      VARCHAR(150)  NOT NULL COMMENT 'Libellé lisible affiché dans le dashboard admin',
  `value`      TEXT          NOT NULL COMMENT 'Contenu du texte (peut être long)',
  `updated_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_page_key` (`page`, `key_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : solutions
-- Logiciels édités par Access Informatique.
-- Une ligne par produit (SoluMed, MySchool, Matrix, etc.).
-- ============================================================
CREATE TABLE IF NOT EXISTS `solutions` (
  `id`                  INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `slug`                VARCHAR(100)  NOT NULL COMMENT 'Identifiant URL (ex : solumed)',
  `name`                VARCHAR(100)  NOT NULL,
  `tagline`             VARCHAR(255)  NOT NULL COMMENT 'Sous-titre court',
  `category`            VARCHAR(80)   NOT NULL COMMENT 'Ex : Santé, Éducation, Comptabilité...',
  `accent_color`        VARCHAR(20)   NOT NULL DEFAULT '#16a34a' COMMENT 'Couleur hex du thème',
  `accent_color_light`  VARCHAR(20)   NOT NULL DEFAULT '#dcfce7' COMMENT 'Couleur hex légère pour les fonds',
  `hero_image`          VARCHAR(500)  NOT NULL COMMENT 'URL ou chemin local (après upload)',
  `price`               VARCHAR(50)   NOT NULL COMMENT 'Ex : 650 000 FCFA',
  `short_description`   TEXT          NOT NULL COMMENT 'Affiché sur la carte de la liste',
  `full_description`    TEXT          NOT NULL COMMENT 'Affiché sur la page détail',
  `brochure_url`        VARCHAR(500)  DEFAULT NULL,
  `demo_url`            VARCHAR(500)  DEFAULT NULL,
  `stat1_value`         VARCHAR(50)   DEFAULT NULL,
  `stat1_label`         VARCHAR(100)  DEFAULT NULL,
  `stat2_value`         VARCHAR(50)   DEFAULT NULL,
  `stat2_label`         VARCHAR(100)  DEFAULT NULL,
  `stat3_value`         VARCHAR(50)   DEFAULT NULL,
  `stat3_label`         VARCHAR(100)  DEFAULT NULL,
  `is_active`           TINYINT(1)    NOT NULL DEFAULT 1 COMMENT '1 = visible, 0 = masqué',
  `sort_order`          TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at`          TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_solution_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : solution_modules
-- Modules fonctionnels de chaque logiciel (ex : Dossier patient,
-- Gestion des rendez-vous, Facturation...).
-- Liés à la table solutions par solution_id.
-- ============================================================
CREATE TABLE IF NOT EXISTS `solution_modules` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `solution_id` INT UNSIGNED  NOT NULL,
  `title`       VARCHAR(150)  NOT NULL,
  `description` TEXT          NOT NULL,
  `sort_order`  TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_sm_solution`
    FOREIGN KEY (`solution_id`) REFERENCES `solutions` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : solution_advantages
-- Points forts / avantages compétitifs de chaque logiciel.
-- ============================================================
CREATE TABLE IF NOT EXISTS `solution_advantages` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `solution_id` INT UNSIGNED  NOT NULL,
  `text`        VARCHAR(255)  NOT NULL,
  `sort_order`  TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_sa_solution`
    FOREIGN KEY (`solution_id`) REFERENCES `solutions` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : solution_tags
-- Mots-clés / fonctionnalités clés associés à chaque logiciel
-- (affichés sous forme de badges sur les cartes).
-- ============================================================
CREATE TABLE IF NOT EXISTS `solution_tags` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `solution_id` INT UNSIGNED  NOT NULL,
  `tag`         VARCHAR(80)   NOT NULL,
  `sort_order`  TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_st_solution`
    FOREIGN KEY (`solution_id`) REFERENCES `solutions` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : formations
-- Catalogue des formations proposées par Access Informatique.
-- ============================================================
CREATE TABLE IF NOT EXISTS `formations` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `slug`        VARCHAR(100)  NOT NULL COMMENT 'Identifiant URL (ex : developpement-web-full-stack)',
  `title`       VARCHAR(150)  NOT NULL,
  `category`    VARCHAR(80)   NOT NULL COMMENT 'Ex : Développement, Design, IA...',
  `duration`    VARCHAR(50)   NOT NULL COMMENT 'Ex : 6 mois',
  `level`       VARCHAR(80)   NOT NULL COMMENT 'Ex : Débutant à intermédiaire',
  `price`       VARCHAR(50)   NOT NULL COMMENT 'Ex : 150 000 FCFA',
  `description` TEXT          NOT NULL,
  `image_url`   VARCHAR(500)  NOT NULL,
  `is_active`   TINYINT(1)    NOT NULL DEFAULT 1,
  `sort_order`  TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at`  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_formation_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : formation_skills
-- Technologies / compétences enseignées dans chaque formation
-- (affichées sous forme de badges).
-- ============================================================
CREATE TABLE IF NOT EXISTS `formation_skills` (
  `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `formation_id` INT UNSIGNED  NOT NULL,
  `skill`        VARCHAR(80)   NOT NULL,
  `sort_order`   TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_fsk_formation`
    FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : formation_modules
-- Programme pédagogique détaillé de chaque formation
-- (chapitres / semaines de cours).
-- ============================================================
CREATE TABLE IF NOT EXISTS `formation_modules` (
  `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `formation_id` INT UNSIGNED  NOT NULL,
  `title`        VARCHAR(150)  NOT NULL,
  `description`  TEXT          NOT NULL,
  `duration`     VARCHAR(50)   DEFAULT NULL COMMENT 'Ex : 3 semaines',
  `sort_order`   TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_fmo_formation`
    FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : formation_benefits
-- Avantages / bénéfices mis en avant pour chaque formation.
-- ============================================================
CREATE TABLE IF NOT EXISTS `formation_benefits` (
  `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `formation_id` INT UNSIGNED  NOT NULL,
  `text`         VARCHAR(255)  NOT NULL,
  `sort_order`   TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_fb_formation`
    FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : formation_outcomes
-- Ce que l'apprenant sera capable de faire à l'issue
-- de la formation (compétences acquises).
-- ============================================================
CREATE TABLE IF NOT EXISTS `formation_outcomes` (
  `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `formation_id` INT UNSIGNED  NOT NULL,
  `text`         VARCHAR(255)  NOT NULL,
  `sort_order`   TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_fo_formation`
    FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : partners
-- Logos des partenaires / références clients affichés
-- sur la page d'accueil et la page À propos.
-- ============================================================
CREATE TABLE IF NOT EXISTS `partners` (
  `id`        INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `name`      VARCHAR(150)  NOT NULL,
  `logo_url`  VARCHAR(500)  NOT NULL COMMENT 'Chemin local ou URL externe',
  `is_active` TINYINT(1)    NOT NULL DEFAULT 1,
  `sort_order` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : hackathon_themes
-- Thèmes proposés lors du hackathon (modifiables depuis l'admin).
-- ============================================================
CREATE TABLE IF NOT EXISTS `hackathon_themes` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `icon`        VARCHAR(10)   NOT NULL COMMENT 'Emoji ou code unicode',
  `title`       VARCHAR(100)  NOT NULL,
  `description` TEXT          NOT NULL,
  `sort_order`  TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `is_active`   TINYINT(1)    NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : hackathon_timeline
-- Programme jour par jour du hackathon.
-- ============================================================
CREATE TABLE IF NOT EXISTS `hackathon_timeline` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `day_label`   VARCHAR(50)   NOT NULL COMMENT 'Ex : Jour 1, Jour 2...',
  `title`       VARCHAR(150)  NOT NULL,
  `description` TEXT          NOT NULL,
  `sort_order`  TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : hackathon_rewards
-- Récompenses / prix remis lors du hackathon.
-- ============================================================
CREATE TABLE IF NOT EXISTS `hackathon_rewards` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `emoji`       VARCHAR(10)   NOT NULL,
  `title`       VARCHAR(50)   NOT NULL COMMENT 'Ex : 1er Prix',
  `amount`      VARCHAR(50)   NOT NULL COMMENT 'Ex : 3 000 000 FCFA',
  `is_featured` TINYINT(1)    NOT NULL DEFAULT 0 COMMENT '1 = mis en avant visuellement',
  `sort_order`  TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : contact_submissions
-- Messages reçus via le formulaire "Nous contacter".
-- Chaque ligne = un message entrant d'un visiteur.
-- ============================================================
CREATE TABLE IF NOT EXISTS `contact_submissions` (
  `id`         INT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(150)   NOT NULL,
  `email`      VARCHAR(150)   NOT NULL,
  `phone`      VARCHAR(30)    DEFAULT NULL,
  `subject`    VARCHAR(255)   DEFAULT NULL,
  `message`    TEXT           NOT NULL,
  `ip_address` VARCHAR(45)    DEFAULT NULL COMMENT 'IPv4 ou IPv6',
  `status`     ENUM('new','read','replied') NOT NULL DEFAULT 'new',
  `created_at` TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_cs_status`     (`status`),
  KEY `idx_cs_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : inscription_submissions
-- Demandes d'inscription aux formations.
-- Chaque ligne = une demande d'un candidat.
-- ============================================================
CREATE TABLE IF NOT EXISTS `inscription_submissions` (
  `id`                  INT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `prenom`              VARCHAR(100)   NOT NULL,
  `nom`                 VARCHAR(100)   NOT NULL,
  `email`               VARCHAR(150)   NOT NULL,
  `telephone`           VARCHAR(30)    DEFAULT NULL,
  `ville`               VARCHAR(100)   DEFAULT NULL,
  `formation_requested` VARCHAR(150)   NOT NULL COMMENT 'Intitulé exact de la formation choisie',
  `format_prefere`      ENUM('Présentiel','En ligne','Hybride') NOT NULL,
  `niveau`              ENUM('Débutant','Intermédiaire','Avancé') NOT NULL,
  `message`             TEXT           DEFAULT NULL,
  `ip_address`          VARCHAR(45)    DEFAULT NULL,
  `status`              ENUM('new','contacted','enrolled','cancelled') NOT NULL DEFAULT 'new',
  `created_at`          TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_is_status`     (`status`),
  KEY `idx_is_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
