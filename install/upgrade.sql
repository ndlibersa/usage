//NOT MATCHING!!!
CREATE  TABLE `coral_usage_prod`.`Layout` (
  `layoutCode` VARCHAR(45) NOT NULL ,
  `name` VARCHAR(45) NULL ,
  PRIMARY KEY (`layoutCode`) );

ALTER TABLE `coral_usage_prod`.`Layout` ADD COLUMN `resourceType` VARCHAR(45) NULL  AFTER `name` ;

INSERT INTO Layout (layoutCode, name) values('JR1_R3', 'JR1 Release 3', 'Journal');
INSERT INTO Layout (layoutCode, name) values('JR1a_R3', 'JR1 Release 3 (archive)', 'Journal');
INSERT INTO Layout (layoutCode, name) values('JR1_R4', 'JR1 Release 4', 'Journal');
INSERT INTO Layout (layoutCode, name) values('JR1a_R4', 'JR1 Release 4 (archive)', 'Journal');
INSERT INTO Layout (layoutCode, name) values('BR1_R4', 'BR1 Release 4', 'Book');
INSERT INTO Layout (layoutCode, name) values('BR2_R4', 'BR2 Release 4', 'Book');
INSERT INTO Layout (layoutCode, name) values('DB1_R4', 'DB1 Release 4', 'Database');

ALTER TABLE `coral_usage_prod`.`ImportLog` ADD COLUMN `layoutCode` VARCHAR(45) NULL  AFTER `importDateTime` ;

ALTER TABLE `coral_usage_prod`.`Title` 
ADD COLUMN `resourceType` VARCHAR(45) NULL AFTER `title` ;


ALTER TABLE `coral_usage_prod`.`TitleISSN` 
CHANGE COLUMN `titleISSNID` `titleIdentifierID` INT(11) NOT NULL AUTO_INCREMENT  , 
CHANGE COLUMN `issn` `identifier` VARCHAR(20) NULL DEFAULT NULL  , 
CHANGE COLUMN `issnType` `identifierType` VARCHAR(20) NULL DEFAULT NULL  , 
RENAME TO  `coral_usage_prod`.`TitleIdentifier` ;

UPDATE TitleIdentifier SET identifierType="ISSN" where identifierType="print";
UPDATE TitleIdentifier SET identifierType="eISSN" where identifierType="online";

ALTER TABLE `coral_usage_prod`.`MonthlyUsageSummary` ADD COLUMN `activityOrSectionType` VARCHAR(45) NULL  AFTER `mergeInd` ;
ALTER TABLE `coral_usage_prod`.`YearlyUsageSummary` ADD COLUMN `activityOrSectionType` VARCHAR(45) NULL  AFTER `mergeInd` ;



CREATE  TABLE `coral_usage_prod`.`SushiService` (
  `sushiServiceID` INT(11)  NOT NULL AUTO_INCREMENT ,
  `platformID` INT(11) NOT NULL ,
  `serviceURL` VARCHAR(300) NULL ,
  `wsdlURL` VARCHAR(300) NULL ,
  `requestorID` VARCHAR(300) NULL ,
  `customerID` VARCHAR(300) NULL ,
  `login` VARCHAR(300) NULL ,
  `password` VARCHAR(300) NULL ,
  `security` VARCHAR(300) NULL ,
  `serviceDayOfMonth` VARCHAR(300) NULL ,
  `noteText` VARCHAR(300) NULL ,
  PRIMARY KEY (`sushiServiceID`) );

CREATE  TABLE `coral_usage_prod`.`ImportLogPlatformLink` (
  `importLogPlatformLinkID` INT NOT NULL AUTO_INCREMENT ,
  `platformID` INT NULL ,
  `importLogID` INT NULL ,
  PRIMARY KEY (`importLogPlatformLinkID`) );


