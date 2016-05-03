ALTER TABLE `TitleIdentifier`
CHANGE COLUMN `identifierType` `identifierType` VARCHAR(30) NULL DEFAULT NULL;

CREATE TABLE `Version` (
  `version` varchar(10) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

INSERT INTO Version (version) values('1.2');
