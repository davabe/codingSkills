



-- ---
-- Globals
-- ---

-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- SET FOREIGN_KEY_CHECKS=0;

-- ---
-- Table 'categories'
-- 
-- ---

DROP TABLE IF EXISTS `categories`;
		
CREATE TABLE `categories` (
  `idcategory` INT(255) NOT NULL AUTO_INCREMENT,
  `namecategory` CHAR(255) NOT NULL,
  PRIMARY KEY (`idcategory`)
);


-- ---
-- Table 'consequences'
-- 
-- ---

DROP TABLE IF EXISTS `consequences`;
		
CREATE TABLE `consequences` (
  `idConsequence` INT(255) NOT NULL AUTO_INCREMENT,
  `nameconsequence` CHAR(255) NOT NULL,
  PRIMARY KEY (`idConsequence`)
);


-- ---
-- Table 'risks'
-- 
-- ---

DROP TABLE IF EXISTS `risks`;
		
CREATE TABLE `risks`(
  `idRisks` INT(255) NOT NULL AUTO_INCREMENT,
  `namerisks` CHAR(255) NOT NULL,
  PRIMARY KEY (`idRisks`)
);


-- ---
-- Table 'roles'
-- 
-- ---

DROP TABLE IF EXISTS `roles`;
		
CREATE TABLE `roles` (
  `idRole` INT(255) NOT NULL AUTO_INCREMENT,
  `nameRole` CHAR(255) NOT NULL,
  PRIMARY KEY (`idRole`)
);



-- ---
-- Table 'levelspersonal'
-- 
-- ---

DROP TABLE IF EXISTS `levelspersonal`;
		
CREATE TABLE `levelspersonal` (
  `idLevelpersonal` INT(255) NOT NULL AUTO_INCREMENT,
  `min` DECIMAL NOT NULL,
  `max` DECIMAL NOT NULL,
  PRIMARY KEY (`idLevelpersonal`)
);



-- ---
-- Table 'levels'
-- 
-- ---

DROP TABLE IF EXISTS `levels`;
		
CREATE TABLE `levels` (
  `idLevel` INT(255) NOT NULL AUTO_INCREMENT,
  `max` DECIMAL NOT NULL,
  `min` DECIMAL NOT NULL,
  PRIMARY KEY (`idLevel`)
);

-- ---
-- Table 'medicaldevices'
-- 
-- ---

DROP TABLE IF EXISTS `medicaldevices`;
		
CREATE TABLE `medicaldevices` (
  `idMedicaldevice` INT(255) NOT NULL AUTO_INCREMENT,
  `type` CHAR(255) NOT NULL,
  `model` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`idMedicaldevice`)
);


-- ---
-- Table 'diabetes'
-- 
-- ---

DROP TABLE IF EXISTS `diabetes`;
		
CREATE TABLE `diabetes` (
  `idDiabetes` INT(255) NOT NULL AUTO_INCREMENT,
  `type` INT(255) NOT NULL,
  `insulinDip` CHAR(255) NOT NULL,
  `fkLevel` INT(255) NOT NULL,
  PRIMARY KEY (`idDiabetes`),
FOREIGN KEY (fkLevel) REFERENCES `levels` (`idLevel`)
);

-- ---
-- Table 'pregnancy'
-- 
-- ---

DROP TABLE IF EXISTS `pregnancy`;
		
CREATE TABLE `pregnancy` (
  `idPregnancy` INT(255) NOT NULL AUTO_INCREMENT,
  `dateStart` DATE NOT NULL,
  `dateFinish` DATE NOT NULL,
  PRIMARY KEY (`idPregnancy`)
);

-- ---
-- Table 'medicaldevices'
-- 
-- ---

DROP TABLE IF EXISTS `medicaldevices`;
		
CREATE TABLE `medicaldevices` (
  `idMedicaldevice` INT(255) NOT NULL AUTO_INCREMENT,
  `type` CHAR(255) NOT NULL,
  `model` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`idMedicaldevice`)
);


-- ---
-- Table 'devices'
-- 
-- ---

DROP TABLE IF EXISTS `devices`;
		
CREATE TABLE `devices` (
  `idDevice` INT(255) NOT NULL AUTO_INCREMENT,
  `serial` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`idDevice`)
);

-- ---
-- Table 'glycemiamensurations'
-- 
-- ---

DROP TABLE IF EXISTS `glycemiamensurations`;
		
CREATE TABLE `glycemiamensurations` (
  `idMensuration` INT(255) NOT NULL AUTO_INCREMENT,
  `measure` DECIMAL NOT NULL,
  `date` DATE NOT NULL,
  `fkDevice` INT(255) NOT NULL,
  `fkUser` INT(255) NOT NULL,
  PRIMARY KEY (`idMensuration`),
FOREIGN KEY (fkDevice) REFERENCES `devices` (`idDevice`)

);

-- ---
-- Table 'pressuremensurations'
-- 
-- ---

DROP TABLE IF EXISTS `pressuremensurations`;
		
CREATE TABLE `pressuremensurations` (
  `idMensuration` INT(255) NOT NULL AUTO_INCREMENT,
  `measure` DECIMAL NOT NULL,
  `date` DATE NOT NULL,
  `fkDevice` INT(255) NOT NULL,
  `fkUser` INT(255) NOT NULL,
  PRIMARY KEY (`idMensuration`),
FOREIGN KEY (fkDevice) REFERENCES `devices` (`idDevice`)

);

-- ---
-- Table 'weightmensurations'
-- 
-- ---

DROP TABLE IF EXISTS `weightmensurations`;

    CREATE TABLE `weightmensurations` (
  `idMensuration` INT(255) NOT NULL AUTO_INCREMENT,
  `measure` DECIMAL NOT NULL,
  `date` DATE NOT NULL,
  `fkDevice` INT(255) NOT NULL,
  `fkUser` INT(255) NOT NULL,
  PRIMARY KEY (`idMensuration`),
FOREIGN KEY (fkDevice) REFERENCES `devices` (`idDevice`)

);


-- ---
-- Table 'pulseoximetrymensurations'
-- 
-- ---

DROP TABLE IF EXISTS `pulseoximetrymensurations`;

    CREATE TABLE `pulseoximetrymensurations` (
  `idMensuration` INT(255) NOT NULL AUTO_INCREMENT,
  `measure` DECIMAL NOT NULL,
  `date` DATE NOT NULL,
  `fkDevice` INT(255) NOT NULL,
  `fkUser` INT(255) NOT NULL,
  PRIMARY KEY (`idMensuration`),
  FOREIGN KEY (fkDevice) REFERENCES `devices` (`idDevice`)

);


-- ---
-- Table 'spirometrymensurations'
-- 
-- ---

DROP TABLE IF EXISTS `spirometrymensurations`;

    CREATE TABLE `spirometrymensurations` (
  `idMensuration` INT(255) NOT NULL AUTO_INCREMENT,
  `measure` DECIMAL NOT NULL,
  `date` DATE NOT NULL,
  `fkDevice` INT(255) NOT NULL,
  `fkUser` INT(255) NOT NULL,
  PRIMARY KEY (`idMensuration`),
  FOREIGN KEY (fkDevice) REFERENCES `devices` (`idDevice`)

);



-- ---
-- Table 'stateusers'
-- 
-- ---

DROP TABLE IF EXISTS `stateusers`;
		
CREATE TABLE `stateusers` (
  `idStateusers` INT NOT NULL AUTO_INCREMENT,
  `namestate` CHAR(255) NOT NULL,
  PRIMARY KEY (`idStateusers`)
);


-- ---
-- Table 'bridgeCategoriesRisks'
-- 
-- ---

DROP TABLE IF EXISTS `bridgeCategoriesRisks`;
		
CREATE TABLE `bridgeCategoriesRisks` (
  `idBridgeCategoryRisk` INT(255) NOT NULL AUTO_INCREMENT,
  `fkRisks` INT(255) NOT NULL,
  `fkCategory` INT(255) NOT NULL,
  PRIMARY KEY (`idBridgeCategoryRisk`),
FOREIGN KEY (fkRisks) REFERENCES `risks` (`idRisks`),
FOREIGN KEY (fkCategory) REFERENCES `categories` (`idcategory`),
  UNIQUE KEY (`fkRisks`, `fkCategory`)
);

-- ---
-- Table 'bridgeCategoriesConsequences'
-- 
-- ---

DROP TABLE IF EXISTS `bridgeCategoriesConsequences`;
		
CREATE TABLE `bridgeCategoriesConsequences` (
  `idBridgeCategoryConsequence` INT(255) NOT NULL AUTO_INCREMENT,
  `fkCategory` INT(255) NOT NULL,
  `fkConsequence` INT(255) NOT NULL,
  PRIMARY KEY (`idBridgeCategoryConsequence`),
FOREIGN KEY (fkCategory) REFERENCES `categories` (`idcategory`),
FOREIGN KEY (fkConsequence) REFERENCES `consequences` (`idConsequence`),
  UNIQUE KEY (`fkCategory`, `fkConsequence`)
);


-- ---
-- Table 'users'
-- 
-- ---

DROP TABLE IF EXISTS `users`;
		
CREATE TABLE `users` (
  `idUser` INT(255) NOT NULL AUTO_INCREMENT,
  `name` CHAR(255) NOT NULL,
  `surname` CHAR(255) NOT NULL,
  `gender` CHAR(255) NOT NULL,
  `age` INT(255) NOT NULL,
  `residency` VARCHAR(255) NOT NULL,
  `domicile` VARCHAR(255) NOT NULL,
  `codeIdentifier` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NULL,
  `tell` VARCHAR(255) NULL,
  `fkPregnancy` INT(255) NULL,
  `fkDiabetes` INT(255) NULL,
  `fkDevice` INT(255) NULL,
  `fkDoctor` INT(255) NULL,
  `fklevelpersonal` INT(255) NULL,
  PRIMARY KEY (`idUser`),

  FOREIGN KEY (fkPregnancy) REFERENCES `pregnancy` (`idPregnancy`),
  FOREIGN KEY (fkDiabetes) REFERENCES `diabetes` (`idDiabetes`),
  FOREIGN KEY (fkDevice) REFERENCES `devices` (`idDevice`),
  FOREIGN KEY (fklevelpersonal) REFERENCES `levelspersonal` (`idLevelpersonal`)
);


-- ---
-- Table 'bridgePatientsParentsDoctors'
-- 
-- ---

DROP TABLE IF EXISTS `bridgePatientsParentsDoctors`;
		
CREATE TABLE `bridgePatientsParentsDoctors` (
  `idBridgePatientsParentsDoctors` INT(255) NOT NULL AUTO_INCREMENT,
  `fkDoctor` INT(255) NOT NULL,
  `fkPatients` INT(255) NOT NULL,
  `fkParents` INT(255) NOT NULL,
  PRIMARY KEY (`idBridgePatientsParentsDoctors`),
FOREIGN KEY (fkDoctor) REFERENCES `users` (`idUser`),
FOREIGN KEY (fkPatients) REFERENCES `users` (`idUser`),
FOREIGN KEY (fkParents) REFERENCES `users` (`idUser`)

);



-- ---
-- Table 'bridgeUsersRoles'
-- 
-- ---

DROP TABLE IF EXISTS `bridgeUsersRoles`;
		
CREATE TABLE `bridgeUsersRoles` (
  `idBridgeUserRole` INT(255) NOT NULL AUTO_INCREMENT,
  `fkUser` INT(255) NOT NULL,
  `fkRole` INT(255) NOT NULL,
  PRIMARY KEY (`idBridgeUserRole`),
FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`),
FOREIGN KEY (fkRole) REFERENCES `roles` (`idRole`),
  UNIQUE KEY (`fkUser`, `fkRole`)
);

-- ---
-- Table 'bridgeUserMedicaldevices'
-- 
-- ---

DROP TABLE IF EXISTS `bridgeUserMedicaldevices`;
		
CREATE TABLE `bridgeUserMedicaldevices` (
  `idBridgeUserMedicaldevice` INT(255) NOT NULL AUTO_INCREMENT,
  `fkMedicaldevice` INT(255) NOT NULL,
  `fkUser` INT(255) NOT NULL,
  PRIMARY KEY (`idBridgeUserMedicaldevice`),
FOREIGN KEY (fkMedicaldevice) REFERENCES `medicaldevices` (`idMedicaldevice`),
FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`),
  UNIQUE KEY (`fkMedicaldevice`, `fkUser`)
);


-- ---
-- Table 'firsttest'
-- 
-- ---

DROP TABLE IF EXISTS `firsttest`;
		
CREATE TABLE `firsttest` (
  `idFirsttest` INT(255) NOT NULL AUTO_INCREMENT,
  `fkPatient` INT(255) NOT NULL,
  `fkDoctor` INT(255) NOT NULL,
  `fkMensuration` INT(255) NOT NULL,
  PRIMARY KEY (`idFirsttest`),

FOREIGN KEY (fkPatient) REFERENCES `users` (`idUser`),
FOREIGN KEY (fkDoctor) REFERENCES `users` (`idUser`),
FOREIGN KEY (fkMensuration) REFERENCES `glycemiamensurations` (`idMensuration`)
);

-- ---
-- Table 'bridgeConsequencesUsers'
-- 
-- ---

DROP TABLE IF EXISTS `bridgeConsequencesUsers`;
		
CREATE TABLE `bridgeConsequencesUsers` (
  `idBridgeConsequenceUser` INT(255) NOT NULL AUTO_INCREMENT,
  `fkUser` INT(255) NOT NULL,
  `fkConsequence` INT(255) NOT NULL,
  PRIMARY KEY (`idBridgeConsequenceUser`),
FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`),
FOREIGN KEY (fkConsequence) REFERENCES `consequences` (`idConsequence`),
  UNIQUE KEY (`fkUser`, `fkConsequence`)
);

-- ---
-- Table 'bridgeRisksUsers'
-- 
-- ---

DROP TABLE IF EXISTS `bridgeRisksUsers`;
		
CREATE TABLE `bridgeRisksUsers` (
  `idBridgeRisksUser` INT(255) NOT NULL AUTO_INCREMENT,
  `fkUser` INT(255) NOT NULL,
  `fkRisks` INT(255) NOT NULL,
  PRIMARY KEY (`idBridgeRisksUser`),
FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`),
FOREIGN KEY (fkRisks) REFERENCES `risks` (`idRisks`),
  UNIQUE KEY (`fkUser`, `fkRisks`)
);

-- ---
-- Table 'messages'
-- 
-- ---
DROP TABLE IF EXISTS `messages`;
		
CREATE TABLE `messages` (
  `idMessage` INT(255) NOT NULL AUTO_INCREMENT,
  `textmessage` MEDIUMTEXT NOT NULL,
  `fkSender` INT(255) NOT NULL,
  `fkReceive` INT(255) NOT NULL,
  `dategeneration` DATE NOT NULL,
  `datesend` DATE NOT NULL,
  `type` CHAR(255) NOT NULL COMMENT 'automatico o manuale',
  PRIMARY KEY (`idMessage`),
 FOREIGN KEY (fkSender) REFERENCES `users` (`idUser`),
 FOREIGN KEY (fkReceive) REFERENCES `users` (`idUser`)
);

-- ---
-- Table 'notes'
-- 
-- ---

DROP TABLE IF EXISTS `notes`;
		
CREATE TABLE `notes` (
  `idNote` INT(255) NOT NULL,
  `textnote` MEDIUMTEXT NOT NULL,
  `fkSender` INT(255) NOT NULL,
  `fkReceive` INT(255) NOT NULL,
  `state` VARCHAR(255) NOT NULL COMMENT 'terapia o futuri interventi',
  `date` DATE NOT NULL,
  PRIMARY KEY (`idNote`),
FOREIGN KEY (fkSender) REFERENCES `users` (`idUser`),
FOREIGN KEY (fkReceive) REFERENCES `users` (`idUser`)
);
-- ---
-- Table 'logins'
-- 
-- ---

DROP TABLE IF EXISTS `logins`;
		
CREATE TABLE `logins` (
  `idLogin` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `fkStateusers` INT(255) NOT NULL,
  `fkBridgeUserRoles` INT(255) NOT NULL,
  PRIMARY KEY (`idLogin`),

FOREIGN KEY (fkStateusers) REFERENCES `stateusers` (`idStateusers`),
FOREIGN KEY (fkBridgeUserRoles) REFERENCES `bridgeUsersRoles` (`idBridgeUserRole`)
);

ALTER TABLE `glycemiamensurations` ADD FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`);
ALTER TABLE `pressuremensurations` ADD FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`);
ALTER TABLE `pulseoximetrymensurations` ADD FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`);
ALTER TABLE `spirometrymensurations` ADD FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`);
ALTER TABLE `weightmensurations` ADD FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`);

-- ---
-- Foreign Keys 
-- ---

-- ALTER TABLE `users` ADD FOREIGN KEY (fkPregnancy) REFERENCES `pregnancy` (`idPregnancy`);
-- ALTER TABLE `users` ADD FOREIGN KEY (fkDiabetes) REFERENCES `diabetes` (`idDiabetes`);
-- ALTER TABLE `users` ADD FOREIGN KEY (fkDevice) REFERENCES `devices` (`idDevice`);
-- ALTER TABLE `users` ADD FOREIGN KEY (fkDoctor) REFERENCES `users` (`idUser`);
-- ALTER TABLE `users` ADD FOREIGN KEY (fklevelpersonal) REFERENCES `levelspersonal` (`idLevelpersonal`);
-- ALTER TABLE `diabetes` ADD FOREIGN KEY (fkLevel) REFERENCES `levels` (`idLevel`);
-- ALTER TABLE `bridgePatientsParents` ADD FOREIGN KEY (fkPatient) REFERENCES `users` (`idUser`);
-- ALTER TABLE `bridgePatientsParents` ADD FOREIGN KEY (fkParent) REFERENCES `users` (`idUser`);
-- ALTER TABLE `bridgeUserMedicaldevices` ADD FOREIGN KEY (fkMedicaldevice) REFERENCES `medicaldevices` (`idMedicaldevice`);
-- ALTER TABLE `bridgeUserMedicaldevices` ADD FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`);
-- ALTER TABLE `bridgeUsersRoles` ADD FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`);
-- ALTER TABLE `bridgeUsersRoles` ADD FOREIGN KEY (fkRole) REFERENCES `roles` (`idRole`);
-- ALTER TABLE `messages` ADD FOREIGN KEY (fkSender) REFERENCES `users` (`idUser`);
-- ALTER TABLE `messages` ADD FOREIGN KEY (fkReceive) REFERENCES `users` (`idUser`);
-- ALTER TABLE `notes` ADD FOREIGN KEY (fkSender) REFERENCES `users` (`idUser`);
-- ALTER TABLE `notes` ADD FOREIGN KEY (fkReceive) REFERENCES `users` (`idUser`);
-- ALTER TABLE `mensurations` ADD FOREIGN KEY (fkDevice) REFERENCES `devices` (`idDevice`);
-- ALTER TABLE `mensurations` ADD FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`);
-- ALTER TABLE `firsttest` ADD FOREIGN KEY (fkPatient) REFERENCES `users` (`idUser`);
-- ALTER TABLE `firsttest` ADD FOREIGN KEY (fkDoctor) REFERENCES `users` (`idUser`);
-- ALTER TABLE `firsttest` ADD FOREIGN KEY (fkMensuration) REFERENCES `mensurations` (`idMensuration`);
-- ALTER TABLE `bridgeRisksUsers` ADD FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`);
-- ALTER TABLE `bridgeRisksUsers` ADD FOREIGN KEY (fkRisks) REFERENCES `risks` (`idRisks`);
-- ALTER TABLE `bridgeConsequencesUsers` ADD FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`);
-- ALTER TABLE `bridgeConsequencesUsers` ADD FOREIGN KEY (fkConsequence) REFERENCES `consequences` (`idConsequence`);
-- ALTER TABLE `bridgeCategoriesRisks` ADD FOREIGN KEY (fkRisks) REFERENCES `risks` (`idRisks`);
-- ALTER TABLE `bridgeCategoriesRisks` ADD FOREIGN KEY (fkCategory) REFERENCES `categories` (`idcategory`);
-- ALTER TABLE `bridgeCategoriesConsequences` ADD FOREIGN KEY (fkCategory) REFERENCES `categories` (`idcategory`);
-- ALTER TABLE `bridgeCategoriesConsequences` ADD FOREIGN KEY (fkConsequence) REFERENCES `consequences` (`idConsequence`);
-- ALTER TABLE `login` ADD FOREIGN KEY (fkStateusers) REFERENCES `stateusers` (`idStateusers`);
-- ALTER TABLE `login` ADD FOREIGN KEY (fkBridgeUserRoles) REFERENCES `bridgeUsersRoles` (`idBridgeUserRole`);

-- ---
-- Table Properties
-- ---

-- ALTER TABLE `users` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `diabetes` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `pregnancy` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `medicaldevices` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `levels` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `bridgePatientsParents` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `bridgeUserMedicaldevices` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `roles` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `bridgeUsersRoles` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `messages` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `notes` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `devices` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `mensurations` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `firsttest` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `levelspersonal` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `consequences` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `risks` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `categories` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `bridgeRisksUsers` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `bridgeConsequencesUsers` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `bridgeCategoriesRisks` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `bridgeCategoriesConsequences` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `login` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `stateusers` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Test Data
-- ---

-- INSERT INTO `users` (`idUser`,`name`,`surname`,`gender`,`age`,`residency`,`domicile`,`codeIdentifier`,`email`,`tell`,`fkPregnancy`,`fkDiabetes`,`fkDevice`,`fkDoctor`,`fklevelpersonal`) VALUES
-- ('','','','','','','','','','','','','','','');
-- INSERT INTO `diabetes` (`idDiabetes`,`type`,`insulinDip`,`fkLevel`) VALUES
-- ('','','','');
-- INSERT INTO `pregnancy` (`idPregnancy`,`dateStart`,`dateFinish`) VALUES
-- ('','','');
-- INSERT INTO `medicaldevices` (`idMedicaldevice`,`type`,`model`) VALUES
-- ('','','');
-- INSERT INTO `levels` (`idLevel`,`max`,`min`) VALUES
-- ('','','');
-- INSERT INTO `bridgePatientsParents` (`idBridgePatientParent`,`fkPatient`,`fkParent`) VALUES
-- ('','','');
-- INSERT INTO `bridgeUserMedicaldevices` (`idBridgeUserMedicaldevice`,`fkMedicaldevice`,`fkUser`) VALUES
-- ('','','');
-- INSERT INTO `roles` (`idRole`,`nameRole`) VALUES
-- ('','');
-- INSERT INTO `bridgeUsersRoles` (`idBridgeUserRole`,`fkUser`,`fkRole`) VALUES
-- ('','','');
-- INSERT INTO `messages` (`idMessage`,`textmessage`,`fkSender`,`fkReceive`,`dategeneration`,`datesend`,`type`) VALUES
-- ('','','','','','','');
-- INSERT INTO `notes` (`idNote`,`textnote`,`fkSender`,`fkReceive`,`state`,`date`) VALUES
-- ('','','','','','');
-- INSERT INTO `devices` (`idDevice`,`serial`) VALUES
-- ('','');
-- INSERT INTO `mensurations` (`idMensuration`,`measure`,`date`,`fkDevice`,`fkUser`) VALUES
-- ('','','','','');
-- INSERT INTO `firsttest` (`idFirsttest`,`fkPatient`,`fkDoctor`,`fkMensuration`) VALUES
-- ('','','','');
-- INSERT INTO `levelspersonal` (`idLevelpersonal`,`min`,`max`) VALUES
-- ('','','');
-- INSERT INTO `consequences` (`idConsequence`,`nameconsequence`) VALUES
-- ('','');
-- INSERT INTO `risks` (`idRisks`,`namerisks`) VALUES
-- ('','');
-- INSERT INTO `categories` (`idcategory`,`namecategory`) VALUES
-- ('','');
-- INSERT INTO `bridgeRisksUsers` (`idBridgeRisksUser`,`fkUser`,`fkRisks`) VALUES
-- ('','','');
-- INSERT INTO `bridgeConsequencesUsers` (`idBridgeConsequenceUser`,`fkUser`,`fkConsequence`) VALUES
-- ('','','');
-- INSERT INTO `bridgeCategoriesRisks` (`idBridgeCategoryRisk`,`fkRisks`,`fkCategory`) VALUES
-- ('','','');
-- INSERT INTO `bridgeCategoriesConsequences` (`idBridgeCategoryConsequence`,`fkCategory`,`fkConsequence`) VALUES
-- ('','','');
-- INSERT INTO `login` (`idLogin`,`username`,`password`,`fkStateusers`,`fkBridgeUserRoles`) VALUES
-- ('','','','','');
-- INSERT INTO `stateusers (`idStateusers`,`namestate`) VALUES
-- ('','');

