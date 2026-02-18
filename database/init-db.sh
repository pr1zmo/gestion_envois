#!/bin/bash
# =============================================================================
# init-db.sh — Initialise the "poste_maroc" MySQL database
# Creates all tables, triggers, and seed data if the database doesn't exist.
# Generated from a full analysis of every PHP file in backend/
# =============================================================================
set -e

# ── Create and initialize MySQL directories ──────────────────────────────────
mkdir -p /var/lib/mysql
mkdir -p /var/run/mysqld
chown -R mysql:mysql /var/lib/mysql
chown -R mysql:mysql /var/run/mysqld
chmod 755 /var/run/mysqld

# Initialize the MySQL data directory using mysql_install_db or mysqld --initialize
if [ ! -d /var/lib/mysql/mysql ]; then
    if command -v mysql_install_db &> /dev/null; then
        mysql_install_db --user=mysql --datadir=/var/lib/mysql --skip-test-db 2>&1 || true
    else
        mysqld --user=mysql --initialize-insecure --datadir=/var/lib/mysql 2>&1 || true
    fi
fi

# ── Start MySQL in the background so we can run SQL statements ───────────────
echo "Starting MySQL server..."
mysqld --user=mysql --datadir=/var/lib/mysql --skip-grant-tables --bind-address=127.0.0.1 &
MYSQLD_PID=$!

# Wait until MySQL is ready
echo "Waiting for MySQL to start..."
for i in $(seq 1 60); do
    if mysqladmin ping --silent 2>/dev/null; then
        echo "MySQL is ready."
        break
    fi
    sleep 1
done

# ── Run the SQL bootstrap ────────────────────────────────────────────────────
mysql --user=root <<'EOSQL'
-- ============================================================================
-- DATABASE
-- ============================================================================
CREATE DATABASE IF NOT EXISTS `poste_maroc`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `poste_maroc`;

-- ============================================================================
-- 1. agency  (postal offices / branches)
--    Referenced by: agent.code_agency, package.code_agency
--    Used in: adduser.php (SELECT nom_agency, code_agency)
--            proceed.php (SELECT * FROM agency — destination dropdown)
--            userControl.php (SELECT nom_agency WHERE code_agency = ...)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `agency` (
    `code_agency`  INT           NOT NULL AUTO_INCREMENT,
    `nom_agency`   VARCHAR(255)  NOT NULL,
    PRIMARY KEY (`code_agency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 2. admin  (back-office administrator accounts)
--    Used in: controllers/admin.php
--      SELECT * FROM admin WHERE username = :username AND password = :password
--    Session keys: admin_id, username, password
-- ============================================================================
CREATE TABLE IF NOT EXISTS `admin` (
    `id`        INT           NOT NULL AUTO_INCREMENT,
    `username`  VARCHAR(100)  NOT NULL,
    `password`  VARCHAR(255)  NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_admin_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 3. agent  (employees / front-desk agents)
--    status is an ENUM – PHP reads its allowed values with SHOW COLUMNS.
--    Triggers enforce unique name / email and raise custom SQLSTATE codes:
--      45001 = duplicate name
--      45002 = duplicate email
--      45003 = both duplicate
--    Used in: controllers/login.php, adduser.php, editUser.php, deleteUser.php
--            views/userControl.php, editUser.php, adduser.php
-- ============================================================================
CREATE TABLE IF NOT EXISTS `agent` (
    `code_agent`   INT                              NOT NULL AUTO_INCREMENT,
    `name`         VARCHAR(255)                     NOT NULL,
    `email`        VARCHAR(255)                     NOT NULL,
    `password`     VARCHAR(255)                     NOT NULL,
    `code_agency`  INT                              NOT NULL,
    `status`       ENUM('active','inactive')        NOT NULL DEFAULT 'active',
    PRIMARY KEY (`code_agent`),
    UNIQUE KEY `uq_agent_name`  (`name`),
    UNIQUE KEY `uq_agent_email` (`email`),
    CONSTRAINT `fk_agent_agency`
        FOREIGN KEY (`code_agency`) REFERENCES `agency` (`code_agency`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Triggers: duplicate-check with custom SQLSTATE codes ────────────────────
DELIMITER $$

DROP TRIGGER IF EXISTS `trg_agent_before_insert`$$
CREATE TRIGGER `trg_agent_before_insert`
BEFORE INSERT ON `agent`
FOR EACH ROW
BEGIN
    DECLARE name_exists  INT DEFAULT 0;
    DECLARE email_exists INT DEFAULT 0;

    SELECT COUNT(*) INTO name_exists
      FROM `agent` WHERE `name` = NEW.`name`;

    SELECT COUNT(*) INTO email_exists
      FROM `agent` WHERE `email` = NEW.`email`;

    IF name_exists > 0 AND email_exists > 0 THEN
        SIGNAL SQLSTATE '45003'
            SET MESSAGE_TEXT = 'Both name and email already exist';
    ELSEIF name_exists > 0 THEN
        SIGNAL SQLSTATE '45001'
            SET MESSAGE_TEXT = 'This name already exists';
    ELSEIF email_exists > 0 THEN
        SIGNAL SQLSTATE '45002'
            SET MESSAGE_TEXT = 'This email already exists';
    END IF;
END$$

DROP TRIGGER IF EXISTS `trg_agent_before_update`$$
CREATE TRIGGER `trg_agent_before_update`
BEFORE UPDATE ON `agent`
FOR EACH ROW
BEGIN
    DECLARE name_exists  INT DEFAULT 0;
    DECLARE email_exists INT DEFAULT 0;

    SELECT COUNT(*) INTO name_exists
      FROM `agent`
     WHERE `name` = NEW.`name`
       AND `code_agent` <> OLD.`code_agent`;

    SELECT COUNT(*) INTO email_exists
      FROM `agent`
     WHERE `email` = NEW.`email`
       AND `code_agent` <> OLD.`code_agent`;

    IF name_exists > 0 AND email_exists > 0 THEN
        SIGNAL SQLSTATE '45003'
            SET MESSAGE_TEXT = 'Both name and email already exist';
    ELSEIF name_exists > 0 THEN
        SIGNAL SQLSTATE '45001'
            SET MESSAGE_TEXT = 'This name already exists';
    ELSEIF email_exists > 0 THEN
        SIGNAL SQLSTATE '45002'
            SET MESSAGE_TEXT = 'This email already exists';
    END IF;
END$$

DELIMITER ;

-- ============================================================================
-- 4. package  (shipments — colis & courier)
--    code_package is generated AFTER insert in PHP (controllers/proceed.php):
--      colis   → 'CL' + zero-padded lastInsertId + 'MA'
--      courier → 'CR' + zero-padded lastInsertId + 'MA'
--    old_price stores pre-discount price (0 when no discount applied).
--    A trigger adds +12 DH for fragile and +15 DH for cache_en_delivery.
--    Used in: controllers/proceed.php (INSERT), controllers/filter_dates.php
--            views/liste_envoie.php (SELECT), views/reciept.php (SELECT)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `package` (
    `id`                  INT                                      NOT NULL AUTO_INCREMENT,
    `code_package`        VARCHAR(15)                              DEFAULT NULL,
    `expediteur`          VARCHAR(255)                             NOT NULL,
    `type`                VARCHAR(50)                              NOT NULL COMMENT 'colis or courier',
    `prix`                DECIMAL(10,2)                            NOT NULL DEFAULT 0.00,
    `old_price`           DECIMAL(10,2)                            NOT NULL DEFAULT 0.00,
    `status`              ENUM('pending','delivered','cancelled')  NOT NULL DEFAULT 'pending',
    `destination`         VARCHAR(255)                             NOT NULL,
    `date`                DATE                                     NOT NULL,
    `code_agent`          INT                                      NOT NULL,
    `code_agency`         INT                                      NOT NULL,
    `fragile`             TINYINT(1)                               NOT NULL DEFAULT 0,
    `cache_en_delivery`   TINYINT(1)                               NOT NULL DEFAULT 0,
    `destinataire`        VARCHAR(255)                             NOT NULL,
    `destinataire_adress` VARCHAR(255)                             DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_package_date`       (`date`),
    KEY `idx_package_code_agent` (`code_agent`),
    CONSTRAINT `fk_package_agent`
        FOREIGN KEY (`code_agent`)  REFERENCES `agent` (`code_agent`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT `fk_package_agency`
        FOREIGN KEY (`code_agency`) REFERENCES `agency` (`code_agency`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Trigger: apply surcharges for fragile / cash-on-delivery ────────────────
DELIMITER $$

DROP TRIGGER IF EXISTS `trg_package_before_insert`$$
CREATE TRIGGER `trg_package_before_insert`
BEFORE INSERT ON `package`
FOR EACH ROW
BEGIN
    IF NEW.`fragile` = 1 THEN
        SET NEW.`prix` = NEW.`prix` + 12;
    END IF;
    IF NEW.`cache_en_delivery` = 1 THEN
        SET NEW.`prix` = NEW.`prix` + 15;
    END IF;
END$$

DELIMITER ;

-- ============================================================================
-- 5. discount_table  (promotional discounts — percentage based)
--    status is a VIRTUAL generated column derived from start_date/end_date.
--    Values: 'not yet started', 'ongoing', 'ended'
--    Used in: controllers/addPromo.php (INSERT), controllers/deletePromo.php
--            views/promoControl.php (SELECT *)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `discount_table` (
    `id`          INT            NOT NULL AUTO_INCREMENT,
    `amount`      INT            NOT NULL COMMENT 'discount percentage (e.g. 10 = 10%)',
    `start_date`  DATE           NOT NULL,
    `end_date`    DATE           NOT NULL,
    `status`      VARCHAR(50)    GENERATED ALWAYS AS (
                      CASE
                          WHEN CURDATE() < `start_date`                       THEN 'not yet started'
                          WHEN CURDATE() BETWEEN `start_date` AND `end_date`  THEN 'ongoing'
                          ELSE 'ended'
                      END
                  ) VIRTUAL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 6. tarif_par_produit_colis  (price grid for colis by weight range in KG)
--    Queried as: WHERE `from` < :weight AND `to` >= :weight
--    Used in: views/dashboard.php, controllers/tarifControl_colis.php
--            components/tarifControl_colis.php
-- ============================================================================
CREATE TABLE IF NOT EXISTS `tarif_par_produit_colis` (
    `id`     INT            NOT NULL AUTO_INCREMENT,
    `from`   DECIMAL(10,3)  NOT NULL COMMENT 'weight range lower bound (KG, exclusive)',
    `to`     DECIMAL(10,3)  NOT NULL COMMENT 'weight range upper bound (KG, inclusive)',
    `price`  DECIMAL(10,2)  NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 7. tarif_par_produit_courier  (price grid for courier by weight range in KG)
--    Queried as: WHERE `from` < :weight AND `to` >= :weight
--    Used in: views/dashboard.php, controllers/tarifControl_courier.php
--            components/tarifControl_courier.php
-- ============================================================================
CREATE TABLE IF NOT EXISTS `tarif_par_produit_courier` (
    `id`     INT            NOT NULL AUTO_INCREMENT,
    `from`   DECIMAL(10,3)  NOT NULL COMMENT 'weight range lower bound (KG, exclusive)',
    `to`     DECIMAL(10,3)  NOT NULL COMMENT 'weight range upper bound (KG, inclusive)',
    `price`  DECIMAL(10,2)  NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- SEED DATA
-- ============================================================================

-- Agencies (Moroccan postal offices)
INSERT IGNORE INTO `agency` (`code_agency`, `nom_agency`) VALUES
    (1,  'Rabat'),
    (2,  'Casablanca'),
    (3,  'Marrakech'),
    (4,  'Fes'),
    (5,  'Tanger'),
    (6,  'Agadir'),
    (7,  'Oujda'),
    (8,  'Meknes'),
    (9,  'Kenitra'),
    (10, 'Tetouan');

-- Default admin account (matches controllers/admin.php login)
INSERT IGNORE INTO `admin` (`id`, `username`, `password`) VALUES
    (1, 'admin', 'admin');

-- Tarif colis (weight ranges in KG)
INSERT IGNORE INTO `tarif_par_produit_colis` (`id`, `from`, `to`, `price`) VALUES
    (1,  0.000,  0.500,  25.00),
    (2,  0.500,  1.000,  40.00),
    (3,  1.000,  2.000,  55.00),
    (4,  2.000,  5.000,  75.00),
    (5,  5.000, 10.000, 100.00),
    (6, 10.000, 20.000, 150.00),
    (7, 20.000, 30.000, 200.00);

-- Tarif courier (weight ranges in KG)
INSERT IGNORE INTO `tarif_par_produit_courier` (`id`, `from`, `to`, `price`) VALUES
    (1, 0.000, 0.020,  5.00),
    (2, 0.020, 0.050, 10.00),
    (3, 0.050, 0.100, 15.00),
    (4, 0.100, 0.250, 20.00),
    (5, 0.250, 0.500, 30.00),
    (6, 0.500, 1.000, 45.00),
    (7, 1.000, 2.000, 60.00);

FLUSH PRIVILEGES;

EOSQL

echo "Database 'poste_maroc' initialised successfully."

# ── Stop the background mysqld and restart it in the foreground ──────────────
mysqladmin shutdown 2>/dev/null || true
wait "$MYSQLD_PID" 2>/dev/null || true

echo "Starting MySQL in foreground..."
exec mysqld --user=mysql --datadir=/var/lib/mysql --bind-address=127.0.0.1
