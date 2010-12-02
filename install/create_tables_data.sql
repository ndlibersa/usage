DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ExternalLogin`;
CREATE TABLE  `_DATABASE_NAME_`.`ExternalLogin` (
  `externalLoginID` int(10) unsigned NOT NULL auto_increment,
  `publisherPlatformID` int(10) unsigned default NULL,
  `platformID` int(10) unsigned default NULL,
  `username` varchar(45) default NULL,
  `password` varchar(45) default NULL,
  `loginURL` varchar(245) default NULL,
  `noteText` text,
  PRIMARY KEY  USING BTREE (`externalLoginID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ImportLog`;
CREATE TABLE  `_DATABASE_NAME_`.`ImportLog` (
  `importLogID` int(10) unsigned NOT NULL auto_increment,
  `loginID` varchar(45) NOT NULL,
  `importDateTime` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `fileName` varchar(45) NOT NULL,
  `archiveFileURL` varchar(145) NOT NULL,
  `logFileURL` varchar(145) NOT NULL,
  `details` varchar(245) NOT NULL,
  PRIMARY KEY  USING BTREE (`importLogID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`LogEmailAddress`;
CREATE TABLE  `_DATABASE_NAME_`.`LogEmailAddress` (
  `logEmailAddressID` int(11) NOT NULL auto_increment,
  `emailAddress` varchar(50) default NULL,
  PRIMARY KEY  (`logEmailAddressID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`MonthlyUsageSummary`;
CREATE TABLE  `_DATABASE_NAME_`.`MonthlyUsageSummary` (
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
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Outlier`;
CREATE TABLE  `_DATABASE_NAME_`.`Outlier` (
  `outlierID` int(11) NOT NULL auto_increment,
  `outlierLevel` int(11) default NULL,
  `overageCount` int(11) default NULL,
  `overagePercent` int(3) default NULL,
  `color` varchar(45) NOT NULL,
  PRIMARY KEY  (`outlierID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Platform`;
CREATE TABLE  `_DATABASE_NAME_`.`Platform` (
  `platformID` int(11) NOT NULL auto_increment,
  `organizationID` int(10) unsigned default NULL,
  `name` varchar(150) NOT NULL,
  `reportDisplayName` varchar(150) default NULL,
  `reportDropDownInd` tinyint(1) unsigned default '0',
  PRIMARY KEY  (`platformID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`PlatformNote`;
CREATE TABLE  `_DATABASE_NAME_`.`PlatformNote` (
  `platformNoteID` int(11) NOT NULL auto_increment,
  `platformID` int(11) default NULL,
  `startYear` int(4) default NULL,
  `endYear` int(4) default NULL,
  `counterCompliantInd` tinyint(1) unsigned default NULL,
  `noteText` text,
  PRIMARY KEY  USING BTREE (`platformNoteID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Privilege`;
CREATE TABLE  `_DATABASE_NAME_`.`Privilege` (
  `privilegeID` int(10) unsigned NOT NULL auto_increment,
  `shortName` varchar(50) default NULL,
  PRIMARY KEY  USING BTREE (`privilegeID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Publisher`;
CREATE TABLE  `_DATABASE_NAME_`.`Publisher` (
  `publisherID` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL,
  PRIMARY KEY  (`publisherID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`PublisherPlatform`;
CREATE TABLE  `_DATABASE_NAME_`.`PublisherPlatform` (
  `publisherPlatformID` int(11) NOT NULL auto_increment,
  `publisherID` int(11) default NULL,
  `platformID` int(11) default NULL,
  `organizationID` int(10) unsigned default NULL,
  `reportDisplayName` varchar(150) NOT NULL,
  `reportDropDownInd` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`publisherPlatformID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`PublisherPlatformNote`;
CREATE TABLE  `_DATABASE_NAME_`.`PublisherPlatformNote` (
  `publisherPlatformNoteID` int(10) unsigned NOT NULL auto_increment,
  `publisherPlatformID` int(10) unsigned NOT NULL,
  `startYear` int(4) unsigned default NULL,
  `endYear` int(4) unsigned default NULL,
  `noteText` text,
  PRIMARY KEY  USING BTREE (`publisherPlatformNoteID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Title`;
CREATE TABLE  `_DATABASE_NAME_`.`Title` (
  `titleID` int(11) NOT NULL auto_increment,
  `title` varchar(100) default NULL,
  PRIMARY KEY  (`titleID`),
  KEY `Index_title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`TitleISSN`;
CREATE TABLE  `_DATABASE_NAME_`.`TitleISSN` (
  `titleISSNID` int(11) NOT NULL auto_increment,
  `titleID` int(11) default NULL,
  `issn` varchar(10) default NULL,
  `issnType` varchar(20) default NULL,
  PRIMARY KEY  (`titleISSNID`),
  KEY `Index_titleID` (`titleID`),
  KEY `Index_issn` USING BTREE (`issn`),
  KEY `Index_ISSNType` USING BTREE (`issnType`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`User`;
CREATE TABLE  `_DATABASE_NAME_`.`User` (
  `loginID` varchar(50) NOT NULL,
  `lastName` varchar(45) default NULL,
  `firstName` varchar(45) default NULL,
  `privilegeID` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`YearlyUsageSummary`;
CREATE TABLE  `_DATABASE_NAME_`.`YearlyUsageSummary` (
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
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;





DELETE FROM `_DATABASE_NAME_`.Privilege;
INSERT INTO `_DATABASE_NAME_`.Privilege (privilegeID, shortName) values (1, 'admin');
INSERT INTO `_DATABASE_NAME_`.Privilege (privilegeID, shortName) values (2, 'add/edit');


DELETE FROM `_DATABASE_NAME_`.Outlier;
INSERT INTO `_DATABASE_NAME_`.Outlier (outlierID, outlierLevel, overageCount, overagePercent, color) values (1, 1, 50, 200, "yellow");
INSERT INTO `_DATABASE_NAME_`.Outlier (outlierID, outlierLevel, overageCount, overagePercent, color) values (2, 2, 100, 300, "orange");
INSERT INTO `_DATABASE_NAME_`.Outlier (outlierID, outlierLevel, overageCount, overagePercent, color) values (3, 3, 200, 400, "red");