INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('JR1_R3', 'Journaux (JR1) R3', 'Journal');
INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('JR1a_R3', 'Journaux (JR1) R3 en fichier', 'Journal');
INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('JR1_R4', 'Journaux (JR1) R4', 'Journal');
INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('JR1a_R4', 'Journaux (JR1) R4 en fichier', 'Journal');
INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('BR1_R3', 'Livres (BR1) R3', 'Book');
INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('BR1_R4', 'Livres (BR1) R4', 'Book');
INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('BR2_R3', 'Sections de livre (BR2) R3', 'Book');
INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('BR2_R4', 'Sections de livre (BR2) R4', 'Book');
INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('DB1_R3', 'Base de données (DB1) R3', 'Database');
INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('DB1_R4', 'Base de données (DB1) R4', 'Database');


DELETE FROM `_DATABASE_NAME_`.Privilege;
INSERT INTO `_DATABASE_NAME_`.Privilege (privilegeID, shortName) values (1, 'admin');
INSERT INTO `_DATABASE_NAME_`.Privilege (privilegeID, shortName) values (2, 'ajouter/modifier');