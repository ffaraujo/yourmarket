-- MySQL Script generated by MySQL Workbench
-- 03/21/16 16:07:16
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `yourmarketdb`.`system`
-- -----------------------------------------------------
START TRANSACTION;
USE `yourmarketdb`;
INSERT INTO `yourmarketdb`.`system` (`sys_id`, `sys_name`, `sys_value`, `sys_desc`, `sys_active`) VALUES (1, 'online', '1', 'define se o site esta online ou offline', 0);
INSERT INTO `yourmarketdb`.`system` (`sys_id`, `sys_name`, `sys_value`, `sys_desc`, `sys_active`) VALUES (2, 'delete-mode', '0', 'define se o modo de delecao e real ou logico', 0);
INSERT INTO `yourmarketdb`.`system` (`sys_id`, `sys_name`, `sys_value`, `sys_desc`, `sys_active`) VALUES (3, 'expiration-time', '3600', 'define o tempo em segundos da sessao de usuario', 0);
INSERT INTO `yourmarketdb`.`system` (`sys_id`, `sys_name`, `sys_value`, `sys_desc`, `sys_active`) VALUES (4, 'log-mode', 'text', 'define se o log sera salvo em texto ou no banco', 0);
INSERT INTO `yourmarketdb`.`system` (`sys_id`, `sys_name`, `sys_value`, `sys_desc`, `sys_active`) VALUES (5, 'multi-language', '0', 'define se o site tem mais de um idioma', 0);

COMMIT;


-- -----------------------------------------------------
-- Data for table `yourmarketdb`.`acl_roles`
-- -----------------------------------------------------
START TRANSACTION;
USE `yourmarketdb`;
INSERT INTO `yourmarketdb`.`acl_roles` (`rol_id`, `rol_desc`, `rol_active`) VALUES (1, 'ROOT', 1);
INSERT INTO `yourmarketdb`.`acl_roles` (`rol_id`, `rol_desc`, `rol_active`) VALUES (2, 'ADMIN', 1);
INSERT INTO `yourmarketdb`.`acl_roles` (`rol_id`, `rol_desc`, `rol_active`) VALUES (3, 'USER', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `yourmarketdb`.`users`
-- -----------------------------------------------------
START TRANSACTION;
USE `yourmarketdb`;
INSERT INTO `yourmarketdb`.`users` (`use_id`, `use_name`, `use_email`, `use_password`, `use_image`, `use_insert_date`, `use_last_access`, `use_role`, `use_parent`, `use_active`) VALUES (1, 'ADMIN', 'fabio.flc@gmail.com', sha1('123123'), NULL, NOW(), NULL, 1, NULL, 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `yourmarketdb`.`acl_resources`
-- -----------------------------------------------------
START TRANSACTION;
USE `yourmarketdb`;
INSERT INTO `yourmarketdb`.`acl_resources` (`res_id`, `res_desc`, `res_active`) VALUES (1, 'users', 1);
INSERT INTO `yourmarketdb`.`acl_resources` (`res_id`, `res_desc`, `res_active`) VALUES (2, 'system', 1);
INSERT INTO `yourmarketdb`.`acl_resources` (`res_id`, `res_desc`, `res_active`) VALUES (3, 'auditorship', 1);
INSERT INTO `yourmarketdb`.`acl_resources` (`res_id`, `res_desc`, `res_active`) VALUES (4, 'access', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `yourmarketdb`.`acl_privileges`
-- -----------------------------------------------------
START TRANSACTION;
USE `yourmarketdb`;
INSERT INTO `yourmarketdb`.`acl_privileges` (`pri_id`, `pri_desc`, `pri_active`) VALUES ('A', 'ACTIVATE', 1);
INSERT INTO `yourmarketdb`.`acl_privileges` (`pri_id`, `pri_desc`, `pri_active`) VALUES ('C', 'CREATE', 1);
INSERT INTO `yourmarketdb`.`acl_privileges` (`pri_id`, `pri_desc`, `pri_active`) VALUES ('D', 'DELETE', 1);
INSERT INTO `yourmarketdb`.`acl_privileges` (`pri_id`, `pri_desc`, `pri_active`) VALUES ('R', 'READ', 1);
INSERT INTO `yourmarketdb`.`acl_privileges` (`pri_id`, `pri_desc`, `pri_active`) VALUES ('U', 'UPDATE', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `yourmarketdb`.`acl_access`
-- -----------------------------------------------------
START TRANSACTION;
USE `yourmarketdb`;
INSERT INTO `yourmarketdb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 1, 'A', 1);
INSERT INTO `yourmarketdb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 1, 'C', 1);
INSERT INTO `yourmarketdb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 1, 'D', 1);
INSERT INTO `yourmarketdb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 1, 'R', 1);
INSERT INTO `yourmarketdb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 1, 'U', 1);
INSERT INTO `yourmarketdb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (2, 1, 'U', 1);
INSERT INTO `yourmarketdb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 2, 'A', 1);
INSERT INTO `yourmarketdb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 2, 'C', 1);
INSERT INTO `yourmarketdb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 2, 'D', 1);
INSERT INTO `yourmarketdb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 2, 'R', 1);
INSERT INTO `yourmarketdb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (1, 2, 'U', 1);
INSERT INTO `yourmarketdb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (2, 2, 'U', 0);
INSERT INTO `yourmarketdb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (3, 1, 'R', 1);
INSERT INTO `yourmarketdb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (3, 2, 'R', 1);
INSERT INTO `yourmarketdb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (4, 1, 'U', 1);
INSERT INTO `yourmarketdb`.`acl_access` (`acc_resource`, `acc_role`, `acc_privilege`, `acc_allow`) VALUES (4, 2, 'U', 0);

COMMIT;

