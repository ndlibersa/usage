INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('JR1_R3', 'Journals (JR1) R3', 'Journal');
INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('JR1a_R3', 'Journals (JR1) R3 archive', 'Journal');
INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('JR1_R4', 'Journals (JR1) R4', 'Journal');
INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('JR1a_R4', 'Journals (JR1) R4 archive', 'Journal');
INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('BR1_R3', 'Books (BR1) R3', 'Book');
INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('BR1_R4', 'Books (BR1) R4', 'Book');
INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('BR2_R3', 'Book Sections (BR2) R3', 'Book');
INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('BR2_R4', 'Book Sections (BR2) R4', 'Book');
INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('DB1_R3', 'Database (DB1) R3', 'Database');
INSERT INTO `_DATABASE_NAME_`.Layout (layoutCode, name, resourceType) values('DB1_R4', 'Database (DB1) R4', 'Database');


DELETE FROM `_DATABASE_NAME_`.Privilege;
INSERT INTO `_DATABASE_NAME_`.Privilege (privilegeID, shortName) values (1, 'admin');
INSERT INTO `_DATABASE_NAME_`.Privilege (privilegeID, shortName) values (2, 'add/edit');