INSERT INTO roles (idRole,nameRole) VALUES (1,"AMMINISTRATORE");
INSERT INTO roles (idRole,nameRole) VALUES (2,"MEDICO");
INSERT INTO roles (idRole,nameRole) VALUES (3,"PAZIENTE");
INSERT INTO roles (idRole,nameRole) VALUES (4,"PARENTE");

INSERT INTO userStates (idUserState, nameState) VALUES ( 1, "ATTIVO");
INSERT INTO userStates (idUserState, nameState) VALUES ( 2, "DISATTIVO");
INSERT INTO userStates (idUserState, nameState) VALUES ( 3, "CANCELLATO");
INSERT INTO userStates (idUserState, nameState) VALUES ( 4, "UTENTE_PROFILO_INCOMPLETO");

insert into diabetes ( idDiabete, type, insulinDip ) VALUES ( 1, 1, 0);
insert into diabetes ( idDiabete, type, insulinDip ) VALUES ( 2, 2, 0);
insert into diabetes ( idDiabete, type, insulinDip ) VALUES ( 3, 3, 1);

insert into doctorsTypes (idDoctorType, type) VALUES (1,'MEDICO DI BASE');
insert into doctorsTypes (idDoctorType, type) VALUES (2,'DIABETOLOGO');


insert into measurationType VALUES (1,"glicemia");
INSERT INTO measurationType VALUES (2,"peso");
INSERT INTO measurationType VALUES (3,"pressione");
insert into measurationType VALUES ( 4, "capacita respiratoria");
insert into measurationType VALUES ( 5, "emoglobina");


insert into medicalDevices (idMedicalDevice , brand, model , fkMeasurationType ) VALUES ("1", "Care Sense", "N", 1);
insert into medicalDevices (idMedicalDevice , brand, model , fkMeasurationType ) VALUES ("2", "Menarini", "Glucocar", 1);
insert into medicalDevices (idMedicalDevice , brand, model , fkMeasurationType ) VALUES ("3", "Beurer", "BF 100", 2);
insert into medicalDevices (idMedicalDevice , brand, model , fkMeasurationType ) VALUES ("4", "Beurer", "BM 58", 3);
insert into medicalDevices (idMedicalDevice , brand, model , fkMeasurationType ) VALUES ("5", "Vitalograph", "Asma1", 4);
insert into medicalDevices (idMedicalDevice , brand, model , fkMeasurationType ) VALUES ("6", "Beurer","po80",5);

insert into medicalDevices (idMedicalDevice , brand, model , fkMeasurationType ) VALUES ("7", "Bayer", "Ascensia Breeze", 1);
insert into medicalDevices (idMedicalDevice , brand, model , fkMeasurationType ) VALUES ("8", "Bayer", "Countur Next", 1);
insert into medicalDevices (idMedicalDevice , brand, model , fkMeasurationType ) VALUES ("9", "Bayer", "Contour XT", 1);
insert into medicalDevices (idMedicalDevice , brand, model , fkMeasurationType ) VALUES ("10", "Lifescan", "OneTouch VerioIQ", 1);
insert into medicalDevices (idMedicalDevice , brand, model , fkMeasurationType ) VALUES ("11", "Lifescan", "OneTouch Ultra2", 1);
insert into medicalDevices (idMedicalDevice , brand, model , fkMeasurationType ) VALUES ("12", "Lifescan", "OneTouch UltraEasy", 1);
insert into medicalDevices (idMedicalDevice , brand, model , fkMeasurationType ) VALUES ("13", "Menarini", "Glucomen Lx Plus", 1);
insert into medicalDevices (idMedicalDevice , brand, model , fkMeasurationType ) VALUES ("14", "Bioseven", "LineaD EVO", 1);
insert into medicalDevices (idMedicalDevice , brand, model , fkMeasurationType ) VALUES ("15", "Abbott", "Freedom Lite", 1);
insert into medicalDevices (idMedicalDevice , brand, model , fkMeasurationType ) VALUES ("16", "Sanofi","BG Star",1);
insert into medicalDevices (idMedicalDevice , brand, model , fkMeasurationType ) VALUES ("17", "Bayer","Breeze 2",1);

/*
insert into bridgeDeviceSerials (idBridgeDeviceSerials, fkMedicalDevice , serial) VALUES (2, 1, "glu_abb_car");
insert into bridgeDeviceSerials (idBridgeDeviceSerials, fkMedicalDevice , serial) VALUES (3, 3, "bil_beu_bf100");
insert into bridgeDeviceSerials (idBridgeDeviceSerials, fkMedicalDevice , serial) VALUES (4, 3, "pul_beu_po80");
insert into bridgeDeviceSerials (idBridgeDeviceSerials, fkMedicalDevice , serial) VALUES (5, 4, "bm58");
insert into bridgeDeviceSerials (idBridgeDeviceSerials, fkMedicalDevice , serial) VALUES (6, 5, "0001502693");
insert into bridgeDeviceSerials (idBridgeDeviceSerials, fkMedicalDevice , serial) VALUES (7, 6, "spi_beu_po80");
*/
	
insert into users (name, surname, gender, age, date, residency, domicile, codeIdentifier, email, tel, pregnancy, fkDiabetes, fkDevice, fkDoctor, fkDoctorType ) VALUES ('Alessandro', 'Vitale', 'm', '32', '19820322', 'Via delle Genziane 13,  00012 Guidonia Montecelio (RM)', 'Via delle Genziane 13,  00012 Guidonia Montecelio (RM)', 'xxx', 'info@alessandrovitale.net', '0774312433', 0, NULL, NULL, NULL, NULL );

INSERT INTO bridgeUsersRoles (fkUser, fkRole) VALUES (1,1);

insert into login (username, password,fkUserState,fkBridgeUserRole ) VALUES ( "vitale", md5('alessandro'), 1, 1 );


INSERT INTO `relativesTypes` (`idRelativeType`, `type`) VALUES (1, 'genitore');
INSERT INTO `relativesTypes` (`idRelativeType`, `type`) VALUES (2, 'fratello');
INSERT INTO `relativesTypes` (`idRelativeType`, `type`) VALUES (3, 'sorella');
INSERT INTO `relativesTypes` (`idRelativeType`, `type`) VALUES (4, 'nipote');
INSERT INTO `relativesTypes` (`idRelativeType`, `type`) VALUES (5, 'zio/a');
INSERT INTO `relativesTypes` (`idRelativeType`, `type`) VALUES (6, 'altro');
