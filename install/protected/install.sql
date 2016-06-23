DROP TABLE IF EXISTS `ExternalLogin`;
CREATE TABLE  `ExternalLogin` (
  `externalLoginID` int(10) unsigned NOT NULL auto_increment,
  `publisherPlatformID` int(10) unsigned default NULL,
  `platformID` int(10) unsigned default NULL,
  `username` varchar(45) default NULL,
  `password` varchar(45) default NULL,
  `loginURL` varchar(245) default NULL,
  `noteText` text,
  PRIMARY KEY  USING BTREE (`externalLoginID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ImportLog`;

CREATE TABLE  `ImportLog` (
  `importLogID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `loginID` varchar(45) NOT NULL,
  `importDateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `layoutCode` varchar(45) DEFAULT NULL,
  `fileName` varchar(45) DEFAULT NULL,
  `archiveFileURL` varchar(145) DEFAULT NULL,
  `logFileURL` varchar(145) DEFAULT NULL,
  `details` varchar(245) DEFAULT NULL,
  PRIMARY KEY (`importLogID`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `LogEmailAddress`;
CREATE TABLE  `LogEmailAddress` (
  `logEmailAddressID` int(11) NOT NULL auto_increment,
  `emailAddress` varchar(50) default NULL,
  PRIMARY KEY  (`logEmailAddressID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `MonthlyUsageSummary`;
CREATE TABLE  `MonthlyUsageSummary` (
  `monthlyUsageSummaryID` int(11) NOT NULL auto_increment,
  `titleID` int(11) NOT NULL,
  `publisherPlatformID` int(11) NOT NULL,
  `year` int(4) NOT NULL,
  `month` int(2) NOT NULL,
  `archiveInd` tinyint(1) default NULL,
  `usageCount` int(11) default NULL,
  `overrideUsageCount` int(11) default NULL,
  `outlierID` int(10) unsigned default NULL,
  `ignoreOutlierInd` tinyint(3) unsigned default '0',
  `mergeInd` tinyint(1) unsigned default '0',
  PRIMARY KEY  USING BTREE (`monthlyUsageSummaryID`),
  KEY `Index_titleID` (`titleID`),
  KEY `Index_publisherPlatformID` (`publisherPlatformID`),
  KEY `Index_year` (`year`),
  KEY `Index_TPPYMA` (`titleID`,`publisherPlatformID`,`year`,`month`,`archiveInd`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Outlier`;
CREATE TABLE  `Outlier` (
  `outlierID` int(11) NOT NULL auto_increment,
  `outlierLevel` int(11) default NULL,
  `overageCount` int(11) default NULL,
  `overagePercent` int(3) default NULL,
  `color` varchar(45) NOT NULL,
  PRIMARY KEY  (`outlierID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Platform`;
CREATE TABLE  `Platform` (
  `platformID` int(11) NOT NULL auto_increment,
  `organizationID` int(10) unsigned default NULL,
  `name` varchar(150) NOT NULL,
  `reportDisplayName` varchar(150) default NULL,
  `reportDropDownInd` tinyint(1) unsigned default '0',
  PRIMARY KEY  (`platformID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `PlatformNote`;
CREATE TABLE  `PlatformNote` (
  `platformNoteID` int(11) NOT NULL auto_increment,
  `platformID` int(11) default NULL,
  `startYear` int(4) default NULL,
  `endYear` int(4) default NULL,
  `counterCompliantInd` tinyint(1) unsigned default NULL,
  `noteText` text,
  PRIMARY KEY  USING BTREE (`platformNoteID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Privilege`;
CREATE TABLE  `Privilege` (
  `privilegeID` int(10) unsigned NOT NULL auto_increment,
  `shortName` varchar(50) default NULL,
  PRIMARY KEY  USING BTREE (`privilegeID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Publisher`;
CREATE TABLE  `Publisher` (
  `publisherID` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL,
  PRIMARY KEY  (`publisherID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `PublisherPlatform`;
CREATE TABLE  `PublisherPlatform` (
  `publisherPlatformID` int(11) NOT NULL auto_increment,
  `publisherID` int(11) default NULL,
  `platformID` int(11) default NULL,
  `organizationID` int(10) unsigned default NULL,
  `reportDisplayName` varchar(150) NOT NULL,
  `reportDropDownInd` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`publisherPlatformID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `PublisherPlatformNote`;
CREATE TABLE  `PublisherPlatformNote` (
  `publisherPlatformNoteID` int(10) unsigned NOT NULL auto_increment,
  `publisherPlatformID` int(10) unsigned NOT NULL,
  `startYear` int(4) unsigned default NULL,
  `endYear` int(4) unsigned default NULL,
  `noteText` text,
  PRIMARY KEY  USING BTREE (`publisherPlatformNoteID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Title`;
CREATE TABLE  `Title` (
  `titleID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `resourceType` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`titleID`),
  KEY `Index_title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `TitleIdentifier`;
CREATE TABLE  `TitleIdentifier` (
  `titleIdentifierID` int(11) NOT NULL AUTO_INCREMENT,
  `titleID` int(11) DEFAULT NULL,
  `identifier` varchar(25) DEFAULT NULL,
  `identifierType` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`titleIdentifierID`),
  KEY `Index_titleID` (`titleID`),
  KEY `Index_issn` (`identifier`) USING BTREE,
  KEY `Index_ISSNType` (`identifierType`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `User`;
CREATE TABLE  `User` (
  `loginID` varchar(50) NOT NULL,
  `lastName` varchar(45) default NULL,
  `firstName` varchar(45) default NULL,
  `privilegeID` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `YearlyUsageSummary`;
CREATE TABLE  `YearlyUsageSummary` (
  `yearlyUsageSummaryID` int(11) NOT NULL auto_increment,
  `titleID` int(11) NOT NULL,
  `publisherPlatformID` int(11) NOT NULL,
  `year` int(4) default NULL,
  `archiveInd` tinyint(1) default NULL,
  `totalCount` int(11) default NULL,
  `ytdHTMLCount` int(11) default NULL,
  `ytdPDFCount` int(11) default NULL,
  `overrideTotalCount` int(10) unsigned default NULL,
  `overrideHTMLCount` int(10) unsigned default NULL,
  `overridePDFCount` int(10) unsigned default NULL,
  `mergeInd` tinyint(1) unsigned default '0',
  PRIMARY KEY  USING BTREE (`yearlyUsageSummaryID`),
  KEY `Index_titleID` (`titleID`),
  KEY `Index_publisherPlatformID` (`publisherPlatformID`),
  KEY `Index_year` (`year`),
  KEY `Index_TPPYA` (`titleID`,`publisherPlatformID`,`year`,`archiveInd`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

ALTER TABLE `MonthlyUsageSummary` ADD COLUMN `activityType` VARCHAR(45) NULL  AFTER `mergeInd` ;
ALTER TABLE `MonthlyUsageSummary` ADD COLUMN `sectionType` VARCHAR(45) NULL  AFTER `activityType` ;
ALTER TABLE `YearlyUsageSummary` ADD COLUMN `activityType` VARCHAR(45) NULL  AFTER `mergeInd` ;
ALTER TABLE `YearlyUsageSummary` ADD COLUMN `sectionType` VARCHAR(45) NULL  AFTER `activityType` ;

DROP TABLE IF EXISTS `Layout`;
CREATE TABLE `Layout` (
  `layoutID` int(11) NOT NULL AUTO_INCREMENT,
  `layoutCode` varchar(45) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `resourceType` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`layoutID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `SushiService`;
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
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ImportLogPlatformLink`;
CREATE TABLE `ImportLogPlatformLink` (
  `importLogPlatformLinkID` int(11) NOT NULL AUTO_INCREMENT,
  `platformID` int(11) DEFAULT NULL,
  `importLogID` int(11) DEFAULT NULL,
  PRIMARY KEY (`importLogPlatformLinkID`),
  KEY `Index_platformID` (`platformID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Version`;
CREATE TABLE `Version` (
  `version` varchar(10) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
INSERT INTO Version (version) values('1.2');

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

DELETE FROM Privilege;
INSERT INTO Privilege (privilegeID, shortName) values (1, 'admin');
INSERT INTO Privilege (privilegeID, shortName) values (2, 'add/edit');

DELETE FROM Outlier;
INSERT INTO Outlier (outlierID, outlierLevel, overageCount, overagePercent, color) values (1, 1, 50, 200, "yellow");
INSERT INTO Outlier (outlierID, outlierLevel, overageCount, overagePercent, color) values (2, 2, 100, 300, "orange");
INSERT INTO Outlier (outlierID, outlierLevel, overageCount, overagePercent, color) values (3, 3, 200, 400, "red");
