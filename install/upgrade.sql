
CREATE  TABLE `coral_usage_devl`.`Layout` (
  `layoutID` INT(11) NOT NULL AUTO_INCREMENT,
  `layoutCode` VARCHAR(45) NOT NULL ,
  `name` VARCHAR(45) NULL ,
  `resourceType` VARCHAR(45) NULL ,
  PRIMARY KEY (`layoutID`) );

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

ALTER TABLE `coral_usage_devl`.`ImportLog` ADD COLUMN `layoutCode` VARCHAR(45) NULL  AFTER `importDateTime` ;
ALTER TABLE `coral_usage_devl`.`ImportLog` ADD COLUMN `sushiServiceID` INT NULL  AFTER `loginID` ;

ALTER TABLE `coral_usage_devl`.`Title` 
ADD COLUMN `resourceType` VARCHAR(45) NULL AFTER `title` ;


ALTER TABLE `coral_usage_devl`.`TitleISSN` 
CHANGE COLUMN `titleISSNID` `titleIdentifierID` INT(11) NOT NULL AUTO_INCREMENT  , 
CHANGE COLUMN `issn` `identifier` VARCHAR(20) NULL DEFAULT NULL  , 
CHANGE COLUMN `issnType` `identifierType` VARCHAR(20) NULL DEFAULT NULL  , 
RENAME TO  `coral_usage_devl`.`TitleIdentifier` ;

UPDATE TitleIdentifier SET identifierType="ISSN" where identifierType="print";
UPDATE TitleIdentifier SET identifierType="eISSN" where identifierType="online";

ALTER TABLE `coral_usage_devl`.`MonthlyUsageSummary` ADD COLUMN `activityType` VARCHAR(45) NULL  AFTER `mergeInd` ;
ALTER TABLE `coral_usage_devl`.`MonthlyUsageSummary` ADD COLUMN `sectionType` VARCHAR(45) NULL  AFTER `activityType` ;
ALTER TABLE `coral_usage_devl`.`YearlyUsageSummary` ADD COLUMN `activityType` VARCHAR(45) NULL  AFTER `mergeInd` ;
ALTER TABLE `coral_usage_devl`.`YearlyUsageSummary` ADD COLUMN `sectionType` VARCHAR(45) NULL  AFTER `activityType` ;



CREATE  TABLE `coral_usage_devl`.`SushiService` (
  `sushiServiceID` INT(11)  NOT NULL AUTO_INCREMENT ,
  `platformID` INT(11) NULL ,
  `publisherPlatformID` INT(11) NULL ,
  `serviceURL` VARCHAR(300) NULL ,
  `wsdlURL` VARCHAR(300) NULL ,
  `reportLayouts` VARCHAR(300) NULL ,
  `release` VARCHAR(300) NULL ,
  `requestorID` VARCHAR(300) NULL ,
  `customerID` VARCHAR(300) NULL ,
  `login` VARCHAR(300) NULL ,
  `password` VARCHAR(300) NULL ,
  `security` VARCHAR(300) NULL ,
  `serviceDayOfMonth` VARCHAR(300) NULL ,
  `noteText` VARCHAR(300) NULL ,
  PRIMARY KEY (`sushiServiceID`) );

CREATE  TABLE `coral_usage_devl`.`ImportLogPlatformLink` (
  `importLogPlatformLinkID` INT NOT NULL AUTO_INCREMENT ,
  `platformID` INT NULL ,
  `importLogID` INT NULL ,
  PRIMARY KEY (`importLogPlatformLinkID`) );

