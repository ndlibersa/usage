CREATE TABLE `Layout` (
  `layoutID` int(11) NOT NULL AUTO_INCREMENT,
  `layoutCode` varchar(45) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `resourceType` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`layoutID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

INSERT INTO Layout (layoutCode, name, resourceType) values('JR1_R3', 'Journals (JR1) R3', 'Journal');
INSERT INTO Layout (layoutCode, name, resourceType) values('JR1a_R3', 'Journals (JR1) R3 archive', 'Journal');
INSERT INTO Layout (layoutCode, name, resourceType) values('JR1_R4', 'Journals (JR1) R4', 'Journal');
INSERT INTO Layout (layoutCode, name, resourceType) values('JR1a_R4', 'Journals (JR1) R4 archive', 'Journal');
INSERT INTO Layout (layoutCode, name, resourceType) values('BR1_R3', 'Books (BR1) R3', 'Book');
INSERT INTO Layout (layoutCode, name, resourceType) values('BR1_R4', 'Books (BR1) R4', 'Book');
INSERT INTO Layout (layoutCode, name, resourceType) values('BR2_R3', 'Book Sections (BR2) R3', 'Book');
INSERT INTO Layout (layoutCode, name, resourceType) values('BR2_R4', 'Book Sections (BR2) R4', 'Book');
INSERT INTO Layout (layoutCode, name, resourceType) values('DB1_R3', 'Database (DB1) R3', 'Database');
INSERT INTO Layout (layoutCode, name, resourceType) values('DB1_R4', 'Database (DB1) R4', 'Database');

ALTER TABLE `ImportLog` ADD COLUMN `layoutCode` VARCHAR(45) NULL  AFTER `importDateTime` ;
ALTER TABLE `ImportLog` ADD COLUMN `sushiServiceID` INT NULL  AFTER `loginID` ;
ALTER TABLE `ImportLog` CHANGE COLUMN `fileName` `fileName` VARCHAR(145) NULL  ;
ALTER TABLE `ImportLog` CHANGE COLUMN `archiveFileURL` `archiveFileURL` VARCHAR(145) NULL  ;
ALTER TABLE `ImportLog` CHANGE COLUMN `logFileURL` `logFileURL` VARCHAR(145) NULL  ;
ALTER TABLE `ImportLog` CHANGE COLUMN `details` `details` VARCHAR(245) NULL  ;

ALTER TABLE `Title` 
ADD COLUMN `resourceType` VARCHAR(45) NULL AFTER `title` ;

UPDATE Title SET resourceType="Journal";

ALTER TABLE `TitleISSN` 
CHANGE COLUMN `titleISSNID` `titleIdentifierID` INT(11) NOT NULL AUTO_INCREMENT  , 
CHANGE COLUMN `issn` `identifier` VARCHAR(20) NULL DEFAULT NULL  , 
CHANGE COLUMN `issnType` `identifierType` VARCHAR(30) NULL DEFAULT NULL  , 
RENAME TO  `TitleIdentifier` ;

UPDATE TitleIdentifier SET identifierType="ISSN" where identifierType="print";
UPDATE TitleIdentifier SET identifierType="eISSN" where identifierType="online";

ALTER TABLE `MonthlyUsageSummary` ADD COLUMN `activityType` VARCHAR(45) NULL  AFTER `mergeInd` ;
ALTER TABLE `MonthlyUsageSummary` ADD COLUMN `sectionType` VARCHAR(45) NULL  AFTER `activityType` ;
ALTER TABLE `YearlyUsageSummary` ADD COLUMN `activityType` VARCHAR(45) NULL  AFTER `mergeInd` ;
ALTER TABLE `YearlyUsageSummary` ADD COLUMN `sectionType` VARCHAR(45) NULL  AFTER `activityType` ;

CREATE TABLE `SushiService` (
  `sushiServiceID` int(11) NOT NULL AUTO_INCREMENT,
  `platformID` int(11) DEFAULT NULL,
  `publisherPlatformID` int(11) DEFAULT NULL,
  `serviceURL` varchar(300) DEFAULT NULL,
  `wsdlURL` varchar(300) DEFAULT NULL,
  `requestorID` varchar(300) DEFAULT NULL,
  `customerID` varchar(300) DEFAULT NULL,
  `login` varchar(300) DEFAULT NULL,
  `password` varchar(300) DEFAULT NULL,
  `security` varchar(300) DEFAULT NULL,
  `serviceDayOfMonth` varchar(300) DEFAULT NULL,
  `noteText` varchar(300) DEFAULT NULL,
  `releaseNumber` varchar(45) DEFAULT NULL,
  `reportLayouts` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`sushiServiceID`),
  KEY `Index_publisherPlatformID` (`publisherPlatformID`),
  KEY `Index_platformID` (`platformID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

CREATE TABLE `ImportLogPlatformLink` (
  `importLogPlatformLinkID` int(11) NOT NULL AUTO_INCREMENT,
  `platformID` int(11) DEFAULT NULL,
  `importLogID` int(11) DEFAULT NULL,
  PRIMARY KEY (`importLogPlatformLinkID`),
  KEY `Index_platformID` (`platformID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
