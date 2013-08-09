
CREATE  TABLE `coral_usage_prod`.`Layout` (
  `layoutCode` VARCHAR(45) NOT NULL ,
  `name` VARCHAR(45) NULL ,
  PRIMARY KEY (`layoutCode`) );



INSERT INTO Layout values('JR1_R3', 'JR1 Release 3)
INSERT INTO Layout values('JR1a_R3', 'JR1 Archive Release 3)
INSERT INTO Layout values('JR1_R4', 'JR1 Release 4)
INSERT INTO Layout values('JR1a_R4', 'JR1 Release 4)
INSERT INTO Layout values('BR1_R4', 'BR1 Release 4)
INSERT INTO Layout values('BR2_R4', 'BR2 Release 4)
INSERT INTO Layout values('DB1_R4', 'DB1 Release 4)

ALTER TABLE `coral_usage_prod`.`ImportLog` ADD COLUMN `layoutCode` VARCHAR(45) NULL  AFTER `importDateTime` ;

