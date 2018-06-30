CREATE TABLE `userStates` (
    `idUserState` TINYINT (1) NOT NULL,
    `nameState` VARCHAR(25) NOT NULL,
    PRIMARY KEY (`idUserState`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `roles` (
    `idRole` TINYINT(1) NOT NULL,
    `nameRole` VARCHAR(15) NOT NULL,
    PRIMARY KEY (`idRole`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*CREATE TABLE `levels` (
    `idLevel` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `max` FLOAT(2,2) NOT NULL,
    `min` FLOAT(2,2) NOT NULL,
    PRIMARY KEY (`idLevel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;*/

CREATE TABLE `diabetes` (
    `idDiabete` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `type` TINYINT(2) NOT NULL,
    `insulinDip` TINYINT(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`idDiabete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `devices` (
    `idDevice` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `serial` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`idDevice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*CREATE TABLE `personalLevels` (
    `idPersonalLevel` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `min` FLOAT(2,2) NOT NULL,
    `max` FLOAT(2,2) NOT NULL,
    PRIMARY KEY (`idPersonalLevel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;*/

CREATE TABLE `doctorsTypes` (
        `idDoctorType` INT(1) NOT NULL,
        `type` VARCHAR(15) NOT NULL,
         PRIMARY KEY (`idDoctorType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
    `idUser` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `surname` VARCHAR(100) NOT NULL,
    `gender` VARCHAR(1) NOT NULL,
    `age` TINYINT(3) UNSIGNED NOT NULL,
    `date` DATE NOT NULL,
    `residency` VARCHAR(255) NOT NULL,
    `domicile` VARCHAR(255) NOT NULL,
    `codeIdentifier` VARCHAR(30) NOT NULL,
    `email` VARCHAR(100) NULL,
    `tel` VARCHAR(30) NOT NULL,
    `pregnancy` TINYINT(1) UNSIGNED NOT NULL,
    `fkDiabetes` INT(255) UNSIGNED NULL,
    `fkDevice` INT(255) UNSIGNED NULL,
    `fkDoctor` INT(255) UNSIGNED NULL,
    `fkDoctorType` INT(1) NULL,
    PRIMARY KEY (`idUser`),
    FOREIGN KEY (fkDoctor) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (fkDiabetes) REFERENCES `diabetes` (`idDiabete`),
    FOREIGN KEY (fkDoctorType) REFERENCES `doctorsTypes` (`idDoctorType`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (fkDevice) REFERENCES `devices` (`idDevice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    

CREATE TABLE `bridgeUsersRoles` (
    `idBridgeUserRole` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `fkUser` INT(255) UNSIGNED NOT NULL,
    `fkRole` TINYINT(1) NOT NULL,
    PRIMARY KEY (`idBridgeUserRole`),
    FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`),
    FOREIGN KEY (fkRole) REFERENCES `roles` (`idRole`) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE KEY (`fkUser`, `fkRole`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    


CREATE TABLE `login` (
    `idLogin` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(50) NOT NULL,
    `fkUserState` TINYINT (1) NOT NULL DEFAULT '1',
    `fkBridgeUserRole` INT(255) UNSIGNED NOT NULL,
    PRIMARY KEY (`idLogin`),

    FOREIGN KEY (fkUserState) REFERENCES `userStates` (`idUserState`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (fkBridgeUserRole) REFERENCES `bridgeUsersRoles` (`idBridgeUserRole`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `pregnancy` (
    `idPregnancy` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `dateStart` DATE NOT NULL,
    `dateFinish` DATE NOT NULL,
    `fkUser` INT(255) UNSIGNED NOT NULL,
    PRIMARY KEY (`idPregnancy`),
    FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`) ON UPDATE NO ACTION ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




CREATE TABLE `relativesTypes` (
        `idRelativeType` INT(1) NOT NULL,
        `type` VARCHAR(15) NOT NULL,
        PRIMARY KEY (`idRelativeType`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;




CREATE TABLE `bridgePatientsDoctors` (
    `idBridgePatientDoctor` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `fkDoctor` INT(255) UNSIGNED NOT NULL,
    `fkPatient` INT(255) UNSIGNED NOT NULL,
    `fkDoctorType` INT(1) NOT NULL,
    PRIMARY KEY (`idBridgePatientDoctor`),
    FOREIGN KEY (fkDoctor) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (fkPatient) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (fkDoctorType) REFERENCES `doctorsTypes` (`idDoctorType`) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE KEY (`fkPatient`, `fkDoctor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
   


CREATE TABLE `bridgePatientsRelatives` (
    `idBridgePatientRelative` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `fkPatient` INT(255) UNSIGNED NOT NULL,
    `fkRelative` INT(255) UNSIGNED NOT NULL,
    `fkRelativeType` INT(1) NOT NULL,
    `fkDoctor` INT(255) UNSIGNED NOT NULL,
    PRIMARY KEY (`idBridgePatientRelative`),
    FOREIGN KEY (fkPatient) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (fkRelative) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (fkRelativeType) REFERENCES `relativesTypes` (`idRelativeType`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (fkDoctor) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE KEY (`fkPatient`, `fkRelative`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `measurationType` (
    `idMeasurationType` TINYINT (5) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`idMeasurationType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `medicalDevices` (
    `idMedicalDevice` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `brand` VARCHAR(100) NOT NULL,
    `model` VARCHAR(100) NOT NULL,
    `fkMeasurationType` TINYINT (5) NOT NULL,
    PRIMARY KEY (`idMedicaldevice`),
    FOREIGN KEY (fkMeasurationType) REFERENCES `measurationType` (`idMeasurationType`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bridgeDeviceSerials` (
    `idBridgeDeviceSerials` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `fkMedicalDevice` INT(255) UNSIGNED NOT NULL,
    `serial` VARCHAR(255) DEFAULT NULL,
    `fkUser` INT(255) UNSIGNED NOT NULL,
    PRIMARY KEY (`idBridgeDeviceSerials`),
    FOREIGN KEY (`fkMedicaldevice`) REFERENCES `medicalDevices` (`idMedicaldevice`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (`fkUser`) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE KEY (`fkMedicalDevice`, `serial`,`fkUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bridgeUserMedicalDevices` (
    `idBridgeUserMedicalDevice` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `fkBridgeDeviceSerials` INT(255) UNSIGNED NOT NULL,
    `fkUser` INT(255) UNSIGNED NOT NULL,
    /*`serial` VARCHAR(255) NOT NULL,*/
    PRIMARY KEY (`idBridgeUserMedicaldevice`),
    FOREIGN KEY (fkBridgeDeviceSerials) REFERENCES `bridgeDeviceSerials` (`idBridgeDeviceSerials`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE KEY (`fkBridgeDeviceSerials`, `fkUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*CREATE TABLE `categories` (
    `idCategory` TINYINT(3) NOT NULL AUTO_INCREMENT,
    `nameCategory` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`idCategory`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		
CREATE TABLE `risks`(
    `idRisk` TINYINT(3) NOT NULL AUTO_INCREMENT,
    `nameRisk` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`idRisk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bridgeCategoriesRisks` (
    `idBridgeCategoryRisk` TINYINT(6) NOT NULL AUTO_INCREMENT,
    `fkRisk` TINYINT(3) NOT NULL,
    `fkCategory` TINYINT(3) NOT NULL,
    PRIMARY KEY (`idBridgeCategoryRisk`),
    FOREIGN KEY (fkRisk) REFERENCES `risks` (`idRisk`),
    FOREIGN KEY (fkCategory) REFERENCES `categories` (`idcategory`),
    UNIQUE KEY (`fkRisk`, `fkCategory`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bridgeRiskUsers` (
    `idBridgeRiskUser` INT(255) NOT NULL AUTO_INCREMENT,
    `fkUser` INT(255) UNSIGNED NOT NULL,
    `fkRisk` TINYINT(3) NOT NULL,
    PRIMARY KEY (`idBridgeRiskUser`),
    FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`) ON UPDATE NO ACTION ON DELETE CASCADE,
    FOREIGN KEY (fkRisk) REFERENCES `risks` (`idRisk`) ON UPDATE NO ACTION ON DELETE CASCADE,
    UNIQUE KEY (`fkUser`, `fkRisk`)
);

CREATE TABLE `consequences` (
    `idConsequence` TINYINT(3) NOT NULL AUTO_INCREMENT,
    `nameConsequence` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`idConsequence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bridgeCategoriesConsequences` (
    `idBridgeCategoryConsequence` TINYINT(6) NOT NULL AUTO_INCREMENT,
    `fkCategory` TINYINT(3) NOT NULL,
    `fkConsequence` TINYINT(3) NOT NULL,
    PRIMARY KEY (`idBridgeCategoryConsequence`),
    FOREIGN KEY (fkCategory) REFERENCES `categories` (`idcategory`),
    FOREIGN KEY (fkConsequence) REFERENCES `consequences` (`idConsequence`),
    UNIQUE KEY (`fkCategory`, `fkConsequence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bridgeConsequenceUsers` (
    `idBridgeConsequenceUser` INT(255) NOT NULL AUTO_INCREMENT,
    `fkUser` INT(255) UNSIGNED NOT NULL,
    `fkConsequence` TINYINT(3) NOT NULL,
    PRIMARY KEY (`idBridgeConsequenceUser`),
    FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`) ON UPDATE NO ACTION ON DELETE CASCADE,
    FOREIGN KEY (fkConsequence) REFERENCES `consequences` (`idConsequence`) ON UPDATE NO ACTION ON DELETE CASCADE,
    UNIQUE KEY (`fkUser`, `fkConsequence`)
);*/

CREATE TABLE `glycemiaMensurations` (
    `idGlycemiaMensuration` INT(255) NOT NULL AUTO_INCREMENT,
    `measure` FLOAT(5,2) NOT NULL,
    `measureUnit` VARCHAR(50) DEFAULT NULL,
    `date` DATETIME NOT NULL,
    `fkDevice` INT(255) UNSIGNED DEFAULT NULL,
    `fkMedicalDevice` INT(255) UNSIGNED DEFAULT NULL,
    `serialMedicalDevice` VARCHAR(50) DEFAULT NULL,
    `fkUser` INT(255) UNSIGNED NOT NULL,
    PRIMARY KEY (`idGlycemiaMensuration`),
    FOREIGN KEY (fkDevice) REFERENCES `devices` (`idDevice`) ON UPDATE CASCADE ON DELETE NO ACTION,
    FOREIGN KEY (fkMedicalDevice) REFERENCES `medicalDevices` (`idMedicalDevice`) ON UPDATE NO ACTION ON DELETE NO ACTION,
    FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `pressureMensurations` (
    `idPressureMensuration` INT(255) NOT NULL AUTO_INCREMENT,
    `minMeaPress` FLOAT(5,2) NOT NULL,
    `maxMeaPress` FLOAT(5,2) NOT NULL,
    `pulse` FLOAT(5,2) DEFAULT NULL,
    `measureUnit` VARCHAR(50) DEFAULT NULL,
    `date` DATETIME NOT NULL,
    `fkDevice` INT(255) UNSIGNED DEFAULT NULL,
    `fkMedicalDevice` INT(255) UNSIGNED DEFAULT NULL,
    `serialMedicalDevice` VARCHAR(50) DEFAULT NULL,
    `fkUser` INT(255) UNSIGNED NOT NULL,
    PRIMARY KEY (`idPressureMensuration`),
    FOREIGN KEY (fkDevice) REFERENCES `devices` (`idDevice`) ON UPDATE CASCADE ON DELETE NO ACTION,
    FOREIGN KEY (fkMedicalDevice) REFERENCES `medicalDevices` (`idMedicalDevice`) ON UPDATE NO ACTION ON DELETE NO ACTION,
    FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `weightMensurations` (
    `idWeightMensuration` INT(255) NOT NULL AUTO_INCREMENT,
    `measure` FLOAT(5,2) NOT NULL,
    `measureUnit` VARCHAR(50) DEFAULT NULL,
    `date` DATETIME NOT NULL,
    `fkDevice` INT(255) UNSIGNED DEFAULT NULL,
    `fkMedicalDevice` INT(255) UNSIGNED DEFAULT NULL,
    `serialMedicalDevice` VARCHAR(50) DEFAULT NULL,
    `fkUser` INT(255) UNSIGNED NOT NULL,
    PRIMARY KEY (`idWeightMensuration`),
    FOREIGN KEY (fkDevice) REFERENCES `devices` (`idDevice`) ON UPDATE CASCADE ON DELETE NO ACTION,
    FOREIGN KEY (fkMedicalDevice) REFERENCES `medicalDevices` (`idMedicalDevice`) ON UPDATE NO ACTION ON DELETE NO ACTION,
    FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `pulseoximetryMensurations` (
    `idPulseoximetryMensuration` INT(255) NOT NULL AUTO_INCREMENT,
    `measure` FLOAT(5,2) NOT NULL,
    `measureUnit` VARCHAR(50) DEFAULT NULL,
    `date` DATETIME NOT NULL,
    `fkDevice` INT(255) UNSIGNED DEFAULT NULL,
    `fkMedicalDevice` INT(255) UNSIGNED DEFAULT NULL,
    `serialMedicalDevice` VARCHAR(50) DEFAULT NULL,
    `fkUser` INT(255) UNSIGNED NOT NULL,
    PRIMARY KEY (`idPulseoximetryMensuration`),
    FOREIGN KEY (fkDevice) REFERENCES `devices` (`idDevice`) ON UPDATE CASCADE ON DELETE NO ACTION,
    FOREIGN KEY (fkMedicalDevice) REFERENCES `medicalDevices` (`idMedicalDevice`) ON UPDATE NO ACTION ON DELETE NO ACTION,
    FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `spirometryMensurations` (
    `idSpirometryMensuration` INT(255) NOT NULL AUTO_INCREMENT,
    `pef` FLOAT(5,2) NOT NULL,
    `fev1` FLOAT(5,2) NOT NULL,
    `pefUnit` VARCHAR(5) NOT NULL,
    `fev1Unit` VARCHAR(5) NOT NULL,
    `date` DATETIME NOT NULL,
    `fkDevice` INT(255) UNSIGNED DEFAULT NULL,
    `fkMedicalDevice` INT(255) UNSIGNED DEFAULT NULL,
    `serialMedicalDevice` VARCHAR(50) DEFAULT NULL,
    `fkUser` INT(255) UNSIGNED NOT NULL,
    PRIMARY KEY (`idSpirometryMensuration`),
    FOREIGN KEY (fkDevice) REFERENCES `devices` (`idDevice`) ON UPDATE CASCADE ON DELETE NO ACTION,
    FOREIGN KEY (fkMedicalDevice) REFERENCES `medicalDevices` (`idMedicalDevice`) ON UPDATE NO ACTION ON DELETE NO ACTION,
    FOREIGN KEY (fkUser) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*CREATE TABLE `firstTest` (
    `idFirstTest` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `fkPatient` INT(255) UNSIGNED NOT NULL,
    `fkMensuration` BIGINT(255) UNSIGNED NOT NULL,
    `fkMeasurationType` TINYINT (5) NOT NULL,
    PRIMARY KEY (`idFirstTest`),
    FOREIGN KEY (fkPatient) REFERENCES `users` (`idUser`),
    FOREIGN KEY (fkMeasurationType) REFERENCES `measurationType` (`idMeasurationType`) ON UPDATE NO ACTION ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;*/

CREATE TABLE `messages` (
    `idMessage` SERIAL,
    `textMessage` VARCHAR(255) NOT NULL,
    `fkSender` INT(255) UNSIGNED NOT NULL,
    `fkReceive` INT(255) UNSIGNED NOT NULL,
    `date` DATETIME NOT NULL,
    `type` VARCHAR(15) NOT NULL COMMENT 'automatico o manuale',
    `state` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'letto(1) o non letto(0)',
    PRIMARY KEY (`idMessage`),
    FOREIGN KEY (fkSender) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (fkReceive) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `typeNote` (
    `idTypeNote` TINYINT(5) NOT NULL,
    `name` VARCHAR(150) NOT NULL COMMENT 'terapia o futuri interventi',
    PRIMARY KEY (`idTypeNote`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `notes` (
    `idNote` SERIAL,
    `textnote` MEDIUMTEXT NOT NULL,
    `fkSender` INT(255) UNSIGNED NOT NULL,
    `fkReceive` INT(255) UNSIGNED NOT NULL,
    `fkTypeNote` TINYINT(5) NOT NULL,
    `date` DATETIME NOT NULL,
    PRIMARY KEY (`idNote`),
    FOREIGN KEY (fkTypeNote) REFERENCES `typeNote` (`idTypeNote`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (fkSender) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (fkReceive) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `levelAlerts` (
    `idLevelAlert` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `fkUser` INT(255) UNSIGNED NOT NULL,
    `fkDoctor` INT(255) UNSIGNED NOT NULL,
    `minWeightMensuration` VARCHAR(10) DEFAULT NULL,
    `maxWeightMensuration` VARCHAR(10) DEFAULT NULL,
    `minGlycemiaMensuration` VARCHAR(10) DEFAULT NULL,
    `maxGlycemiaMensuration` VARCHAR(10) DEFAULT NULL,
    `minPulseoximetryMensuration` VARCHAR(10) DEFAULT NULL,
    `maxPulseoximetryMensuration` VARCHAR(10) DEFAULT NULL,
    `minPressureMensurationsMinimum`  VARCHAR(10) DEFAULT NULL,
    `maxPressureMensurationsMinimum`  VARCHAR(10) DEFAULT NULL,
    `minPressureMensurationsMaximum`  VARCHAR(10) DEFAULT NULL,
    `maxPressureMensurationsMaximum`  VARCHAR(10) DEFAULT NULL,
    `minPefMensuration` VARCHAR(10) DEFAULT NULL,
    `maxPefMensuration` VARCHAR(10) DEFAULT NULL,
    `minFev1Mensuration` VARCHAR(10) DEFAULT NULL,
    `maxFev1Mensuration` VARCHAR(10) DEFAULT NULL,
    `typeContact` VARCHAR(100) NOT NULL,
    `period` INT(3) UNSIGNED NOT NULL DEFAULT 8,
    `typeAlert` TINYINT(1) UNSIGNED NOT NULL,
    `date` DATETIME NOT NULL,
    `state` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'attivo o non attivo',
    `receiverMessageRelative` TINYINT(1) DEFAULT 0,
    `receiverMessagePatient` TINYINT(1) DEFAULT 0,
    PRIMARY KEY (`idLevelAlert`),
    FOREIGN KEY (`fkUser`) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (`fkDoctor`) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `notificationsAlert` (
    `idNotificationsAlert` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `fkLevelAlert` INT(255) UNSIGNED NOT NULL,
    `fkUser` INT(255) UNSIGNED NOT NULL,
    `fkDoctor` INT(255) UNSIGNED NOT NULL,
    `type` VARCHAR(10) NOT NULL DEFAULT 'email',
    `date` DATETIME NOT NULL,
    `state` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'letta(1) o non letta(0)',
    PRIMARY KEY (`idNotificationsAlert`),
    FOREIGN KEY (`fkUser`) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (`fkDoctor`) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (`fkLevelAlert`) REFERENCES `levelAlerts` (`idLevelAlert`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `smsAlert` (
    `idSmsAlert` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `text` varchar(160) NOT NULL,
    `fkUser` INT(255) UNSIGNED NOT NULL,
    `fkDoctor` INT(255) UNSIGNED NOT NULL,
    `fkLevelAlert` INT(255) UNSIGNED NOT NULL,
    `dateInsert` DATETIME NOT NULL,
    `dateSent` DATETIME NULL,
    `result` varchar(255) DEFAULT NULL,
    `sent` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'inviato(1) o non inviato(0)',
    PRIMARY KEY (`idSmsAlert`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `logNotificationsAlert` (
    `idLogNotificationsAlert` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `fkNotificationsAlert` INT(255) UNSIGNED NOT NULL,
    `fkGlycemiaMensuration` INT(255) NULL,
    `fkPressureMensuration` INT(255) NULL,
    `fkWeightMensuration` INT(255) NULL,
    `fkPulseoximetryMensuration` INT(255) NULL,
    `fkSpirometryMensuration` INT(255) NULL,
    PRIMARY KEY (`idLogNotificationsAlert`),
    FOREIGN KEY (`fkNotificationsAlert`) REFERENCES `notificationsAlert` (`idNotificationsAlert`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `numberMeasurementsHours`(
    `idNumberMeasurementsHours` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT,
    `fkUser` INT(255) UNSIGNED NOT NULL,
    `fkDoctor` INT(255) UNSIGNED NOT NULL,
    `fkMedicalDevice` INT(255) UNSIGNED NOT NULL,
    `numMesurations` INT(2) NOT NULL,
    `time1` time NOT NULL,
    `time2` time DEFAULT NULL,
    `time3` time DEFAULT NULL,
    `time4` time DEFAULT NULL,
    `time5` time DEFAULT NULL,
    `time6` time DEFAULT NULL,
    `time7` time DEFAULT NULL,
    `time8` time DEFAULT NULL,
    `time9` time DEFAULT NULL,
    `time10` time DEFAULT NULL,
     PRIMARY KEY (`idNumberMeasurementsHours`),
     FOREIGN KEY (`fkUser`) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE,
     FOREIGN KEY (`fkDoctor`) REFERENCES `users` (`idUser`) ON UPDATE CASCADE ON DELETE CASCADE,
     FOREIGN KEY (`fkMedicalDevice`) REFERENCES `medicalDevices` (`idMedicalDevice`) ON UPDATE NO ACTION ON DELETE NO ACTION,
     UNIQUE KEY (`fkUser`, `fkDoctor`,`fkMedicalDevice`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

