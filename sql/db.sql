-- **Suppression et création de la base de données**
DROP DATABASE IF EXISTS `jra`;
CREATE DATABASE IF NOT EXISTS `jra` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

-- **Création de l'utilisateur**
DROP USER IF EXISTS 'username'@'localhost';
CREATE USER IF NOT EXISTS 'username'@'localhost' IDENTIFIED BY 'password';
GRANT SELECT, INSERT, UPDATE, DELETE ON `jra`.* TO 'username'@'localhost';

-- **Sélection de la base de données**
USE `jra`;

-- **Création de la table contacts**
DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `modified_by` INT(11) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `modified_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- **Création de la table models**
DROP TABLE IF EXISTS `models`;
CREATE TABLE IF NOT EXISTS `models` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `brand` VARCHAR(255) NOT NULL,
    `model` VARCHAR(255) NOT NULL,
    `version` INT(11) NOT NULL DEFAULT 0,
    `year` YEAR NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    `category` ENUM('car', 'suv', 'other') NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `modified_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- **Création de la table vehicles**
DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE IF NOT EXISTS `vehicles` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `contact_id` INT(11) DEFAULT NULL,
    `name` VARCHAR(255) NOT NULL,
    `model_id` INT(11) NOT NULL,
    `buy_price` DECIMAL(10, 2) NOT NULL,
    `buy_date` DATE NOT NULL,
    `type` ENUM('new', 'used') NOT NULL,
    `release_date` DATE NOT NULL,
    `fuel` ENUM('diesel', 'essence', 'electric', 'hybrid') NOT NULL,
    `km` INT(11) UNSIGNED NOT NULL,
    `cv` INT(11) UNSIGNED NOT NULL,
    `color` VARCHAR(50) NOT NULL,
    `transmission` ENUM('manual', 'automatic') NOT NULL,
    `doors` TINYINT(1) UNSIGNED NOT NULL,
    `seats` TINYINT(2) UNSIGNED NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `modified_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`contact_id`) REFERENCES `contacts`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`model_id`) REFERENCES `models`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- **Création de la table users**
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `hash` VARCHAR(255) NOT NULL,
    `roles` JSON NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `modified_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- **Création de la table files**
DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `type` ENUM('pdf', 'png', 'jpeg', 'jpg') NOT NULL,
    `path` VARCHAR(512) DEFAULT NULL, -- Chemin menant au fichier sur le disque
    `blob` LONGBLOB DEFAULT NULL,     -- Contenu binaire du fichier
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `modified_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_file_path_or_blob CHECK (
        (`path` IS NOT NULL AND `blob` IS NULL) OR (`blob` IS NOT NULL AND `path` IS NULL)
    ) -- Soit un chemin, soit un blob, mais pas les deux
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- **Création de la table pivot contact_files**
DROP TABLE IF EXISTS `contact_files`;
CREATE TABLE IF NOT EXISTS `contact_files` (
    `contact_id` INT(11) NOT NULL,
    `file_id` INT(11) NOT NULL,
    `linked_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`contact_id`, `file_id`),
    FOREIGN KEY (`contact_id`) REFERENCES `contacts`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`file_id`) REFERENCES `files`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- **Création de la table historics**
DROP TABLE IF EXISTS `historics`;
CREATE TABLE IF NOT EXISTS `historics` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `contact_id` INT(11) NOT NULL,
    `column_name` VARCHAR(255) NOT NULL,
    `old_value` TEXT DEFAULT NULL,
    `new_value` TEXT DEFAULT NULL,
    `modified_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `modified_by` INT(11) DEFAULT NULL,
    FOREIGN KEY (`contact_id`) REFERENCES `contacts`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`modified_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- **Insertion de données de test dans users**
INSERT INTO `users` (`name`, `hash`, `roles`) VALUES 
    ('admin', '$2y$10$QvoqS3Fw06QXTIPwCcTjrOuDBvU9VZlcxS90DFo3.xBR8jUPMnnIm', '["ADMIN","USER"]'),
    ('user', '$2y$10$FcriKoMptL6y.wEOQsXZLeXQPTLpmY1cVBNd26eeRTxVVpreE4.RC', '["USER"]');

-- **Insertion de données de test dans contacts**
INSERT INTO `contacts` (`name`, `email`) VALUES 
    ('John Doe', 'jo.doe@jra.com'),
    ('Jane Doe', 'ja.doe@jra.com');

-- **Insertion de données de test dans models**
INSERT INTO `models` (`name`, `brand`, `model`, `version`, `year`, `price`, `category`) VALUES 
    ('audi-a3-2020', 'Audi', 'A3', 1, 2020, 35000.00, 'car'),
    ('audi-a4-2023', 'Audi', 'A4', 1, 2023, 45000.00, 'car'),
    ('renault-clio-5', 'Renault', 'Clio', 5, 2020, 15000.00, 'car'),
    ('alpine-a290-2024', 'Alpine', 'A290', 1, 2024, 60000.00, 'car');

-- **Insertion de données de test dans vehicles**
INSERT INTO `vehicles` (`name`, `model_id`, `buy_price`, `buy_date`, `type`, `release_date`, `fuel`, `km`, `cv`, `color`, `transmission`, `doors`, `seats`) VALUES 
    ('audi-a3', (SELECT `id` FROM `models` WHERE `name` = 'audi-a3-2020'), 35000.00, '2020-01-01', 'new', '2020-01-15', 'essence', 0, 5, 'black', 'manual', 5, 5),
    ('audi-a4', (SELECT `id` FROM `models` WHERE `name` = 'audi-a4-2023'), 45000.00, '2023-01-01', 'new', '2023-01-20', 'essence', 0, 5, 'black', 'manual', 5, 5),
    ('renault-clio', (SELECT `id` FROM `models` WHERE `name` = 'renault-clio-5'), 15000.00, '2020-01-01', 'new', '2020-03-10', 'electric', 0, 5, 'black', 'automatic', 5, 5),
    ('alpine-a290', (SELECT `id` FROM `models` WHERE `name` = 'alpine-a290-2024'), 60000.00, '2024-01-01', 'new', '2024-02-01', 'electric', 0, 5, 'black', 'automatic', 5, 5);

-- **Insertion de données de test dans files**
INSERT INTO `files` (`name`, `type`, `path`) VALUES 
    ('contrat-john.pdf', 'pdf', '/files/contrats/contrat-john.pdf'),
    ('photo-john.png', 'png', '/files/photos/photo-john.png'),
    ('photo-jane.jpg', 'jpg', '/files/photos/photo-jane.jpg');

-- **Association entre contacts et fichiers dans contact_files**
INSERT INTO `contact_files` (`contact_id`, `file_id`) VALUES 
    ((SELECT `id` FROM `contacts` WHERE `name` = 'John Doe'), (SELECT `id` FROM `files` WHERE `name` = 'contrat-john.pdf')),
    ((SELECT `id` FROM `contacts` WHERE `name` = 'John Doe'), (SELECT `id` FROM `files` WHERE `name` = 'photo-john.png')),
    ((SELECT `id` FROM `contacts` WHERE `name` = 'Jane Doe'), (SELECT `id` FROM `files` WHERE `name` = 'photo-jane.jpg'));

-- **Création des index pour optimiser les performances**
CREATE INDEX idx_vehicle_model ON `vehicles` (`model_id`);
CREATE INDEX idx_contact_email ON `contacts` (`email`);
CREATE INDEX idx_release_date ON `vehicles` (`release_date`);
CREATE INDEX idx_km ON `vehicles` (`km`);
CREATE INDEX idx_contact_files ON `contact_files` (`contact_id`, `file_id`);
CREATE INDEX idx_contact_name ON `contacts` (`name`);
CREATE INDEX idx_historic_contact_id ON `historics` (`contact_id`);
CREATE INDEX idx_historic_modified_at ON `historics` (`modified_at`);
CREATE INDEX idx_file_type ON `files` (`type`);
CREATE INDEX idx_file_path ON `files` (`path`);
CREATE INDEX idx_contact_files ON `contact_files` (`contact_id`, `file_id`);

DROP TRIGGER IF EXISTS `before_contacts_update`;

DELIMITER //

CREATE TRIGGER `before_contacts_update`
BEFORE UPDATE ON `contacts`
FOR EACH ROW
BEGIN
    -- Vérification des colonnes modifiées
    IF OLD.name <> NEW.name THEN
        INSERT INTO `historics` (`contact_id`, `column_name`, `old_value`, `new_value`, `modified_by`)
        VALUES (OLD.id, 'name', OLD.name, NEW.name, NEW.modified_by);
    END IF;

    IF OLD.email <> NEW.email THEN
        INSERT INTO `historics` (`contact_id`, `column_name`, `old_value`, `new_value`, `modified_by`)
        VALUES (OLD.id, 'email', OLD.email, NEW.email, NEW.modified_by);
    END IF;
END//

CREATE TRIGGER `before_contacts_delete`
BEFORE DELETE ON `contacts`
FOR EACH ROW
BEGIN
    -- Historisation de la suppression du contact
    INSERT INTO `historics` (`contact_id`, `column_name`, `old_value`, `new_value`, `modified_by`)
    VALUES (OLD.id, 'deleted', CONCAT('Name: ', OLD.name, ', Email: ', OLD.email), 'deleted', NULL);
END//

DELIMITER ;
