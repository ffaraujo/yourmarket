SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `basedb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `basedb` ;

-- -----------------------------------------------------
-- Table `basedb`.`system`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `basedb`.`system` (
  `sys_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sys_name` VARCHAR(80) NOT NULL,
  `sys_value` VARCHAR(80) NULL DEFAULT NULL,
  `sys_desc` VARCHAR(200) NULL DEFAULT NULL,
  `sys_active` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`sys_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `basedb`.`acl_roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `basedb`.`acl_roles` (
  `rol_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `rol_desc` VARCHAR(30) NOT NULL,
  `rol_active` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`rol_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `basedb`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `basedb`.`users` (
  `use_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `use_name` VARCHAR(80) NULL,
  `use_email` VARCHAR(150) NOT NULL,
  `use_password` VARCHAR(45) NOT NULL,
  `use_image` VARCHAR(45) NULL DEFAULT NULL,
  `use_insert_date` DATETIME NOT NULL,
  `use_last_access` DATETIME NULL,
  `use_role` INT UNSIGNED NOT NULL,
  `use_parent` INT UNSIGNED NULL,
  `use_active` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`use_id`),
  INDEX `fk_usuarios_acl_roles1_idx` (`use_role` ASC),
  INDEX `fk_users_users1_idx` (`use_parent` ASC),
  CONSTRAINT `fk_usuarios_acl_roles1`
    FOREIGN KEY (`use_role`)
    REFERENCES `basedb`.`acl_roles` (`rol_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_users1`
    FOREIGN KEY (`use_parent`)
    REFERENCES `basedb`.`users` (`use_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `basedb`.`acl_resources`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `basedb`.`acl_resources` (
  `res_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `res_desc` VARCHAR(30) NOT NULL,
  `res_active` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`res_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `basedb`.`acl_privileges`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `basedb`.`acl_privileges` (
  `pri_id` VARCHAR(4) NOT NULL,
  `pri_desc` VARCHAR(30) NOT NULL,
  `pri_active` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`pri_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `basedb`.`acl_access`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `basedb`.`acl_access` (
  `acc_resource` INT UNSIGNED NOT NULL,
  `acc_role` INT UNSIGNED NOT NULL,
  `acc_privilege` VARCHAR(4) NOT NULL,
  `acc_allow` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`acc_resource`, `acc_role`, `acc_privilege`),
  INDEX `fk_acl_access_acl_roles1_idx` (`acc_role` ASC),
  INDEX `fk_acl_access_acl_privileges1_idx` (`acc_privilege` ASC),
  CONSTRAINT `fk_acl_access_acl_resources`
    FOREIGN KEY (`acc_resource`)
    REFERENCES `basedb`.`acl_resources` (`res_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_acl_access_acl_roles1`
    FOREIGN KEY (`acc_role`)
    REFERENCES `basedb`.`acl_roles` (`rol_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_acl_access_acl_privileges1`
    FOREIGN KEY (`acc_privilege`)
    REFERENCES `basedb`.`acl_privileges` (`pri_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `basedb`.`auditorship`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `basedb`.`auditorship` (
  `aud_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `aud_user` INT UNSIGNED NOT NULL,
  `aud_action` VARCHAR(100) NOT NULL,
  `aud_controller` VARCHAR(100) NOT NULL,
  `aud_object_id` INT UNSIGNED NULL,
  `aud_insert_date` DATETIME NOT NULL,
  PRIMARY KEY (`aud_id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `basedb`.`system`
-- -----------------------------------------------------
START TRANSACTION;
USE `basedb`;
INSERT INTO `basedb`.`system` (`sys_id`, `sys_name`, `sys_value`, `sys_desc`, `sys_active`) VALUES (1, 'online', '1', 'define se o site esta online ou offline', 0);
INSERT INTO `basedb`.`system` (`sys_id`, `sys_name`, `sys_value`, `sys_desc`, `sys_active`) VALUES (2, 'delete-mode', '0', 'define se o modo de delecao e real ou logico', 0);
INSERT INTO `basedb`.`system` (`sys_id`, `sys_name`, `sys_value`, `sys_desc`, `sys_active`) VALUES (3, 'expiration-time', '3600', 'define o tempo em segundos da sessao de usuario', 0);
INSERT INTO `basedb`.`system` (`sys_id`, `sys_name`, `sys_value`, `sys_desc`, `sys_active`) VALUES (4, 'log-mode', 'text', 'define se o log sera salvo em texto ou no banco', 0);
INSERT INTO `basedb`.`system` (`sys_id`, `sys_name`, `sys_value`, `sys_desc`, `sys_active`) VALUES (5, 'multi-language', '0', 'define se o site tem mais de um idioma', 0);

COMMIT;


-- -----------------------------------------------------
-- Data for table `basedb`.`acl_roles`
-- -----------------------------------------------------
START TRANSACTION;
USE `basedb`;
INSERT INTO `basedb`.`acl_roles` (`rol_id`, `rol_desc`, `rol_active`) VALUES (1, 'ROOT', 1);
INSERT INTO `basedb`.`acl_roles` (`rol_id`, `rol_desc`, `rol_active`) VALUES (2, 'ADMIN', 1);
INSERT INTO `basedb`.`acl_roles` (`rol_id`, `rol_desc`, `rol_active`) VALUES (3, 'USER', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `basedb`.`users`
-- -----------------------------------------------------
START TRANSACTION;
USE `basedb`;
INSERT INTO `basedb`.`users` (`use_id`, `use_name`, `use_email`, `use_password`, `use_image`, `use_insert_date`, `use_last_access`, `use_role`, `use_parent`, `use_active`) VALUES (1, 'ADMIN', 'fabio.flc@gmail.com', sha1('123123'), NULL, NOW(), NULL, 1, NULL, 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `basedb`.`acl_resources`
-- -----------------------------------------------------
START TRANSACTION;
USE `basedb`;
INSERT INTO `basedb`.`acl_resources` (`res_id`, `res_desc`, `res_active`) VALUES (1, 'users', 1);
INSERT INTO `basedb`.`acl_resources` (`res_id`, `res_desc`, `res_active`) VALUES (2, 'system', 1);
INSERT INTO `basedb`.`acl_resources` (`res_id`, `res_desc`, `res_active`) VALUES (3, 'auditorship', 1);
INSERT INTO `basedb`.`acl_resources` (`res_id`, `res_desc`, `res_active`) VALUES (4, 'access', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `basedb`.`acl_privileges`
-- -----------------------------------------------------
START TRANSACTION;
USE `basedb`;
INSERT INTO `basedb`.`acl_privileges` (`pri_id`, `pri_desc`, `pri_active`) VALUES ('A', 'ACTIVATE', 1);
INSERT INTO `basedb`.`acl_privileges` (`pri_id`, `pri_desc`, `pri_active`) VALUES ('C', 'CREATE', 1);
INSERT INTO `basedb`.`acl_privileges` (`pri_id`, `pri_desc`, `pri_active`) VALUES ('D', 'DELETE', 1);
INSERT INTO `basedb`.`acl_privileges` (`pri_id`, `pri_desc`, `pri_active`) VALUES ('R', 'READ', 1);
INSERT INTO `basedb`.`acl_privileges` (`pri_id`, `pri_desc`, `pri_active`) VALUES ('U', 'UPDATE', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `basedb`.`acl_access`
-- -----------------------------------------------------
START TRANSACTION;
USE `basedb`;
INSERT INTO `basedb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 1, 'A', 1);
INSERT INTO `basedb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 1, 'C', 1);
INSERT INTO `basedb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 1, 'D', 1);
INSERT INTO `basedb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 1, 'R', 1);
INSERT INTO `basedb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 1, 'U', 1);
INSERT INTO `basedb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (2, 1, 'U', 1);
INSERT INTO `basedb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 2, 'A', 1);
INSERT INTO `basedb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 2, 'C', 1);
INSERT INTO `basedb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 2, 'D', 1);
INSERT INTO `basedb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 2, 'R', 1);
INSERT INTO `basedb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 2, 'U', 1);
INSERT INTO `basedb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (2, 2, 'U', 0);
INSERT INTO `basedb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (3, 1, 'R', 1);
INSERT INTO `basedb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (3, 2, 'R', 1);
INSERT INTO `basedb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (4, 1, 'U', 1);
INSERT INTO `basedb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (4, 2, 'U', 0);

COMMIT;

