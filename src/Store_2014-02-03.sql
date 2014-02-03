# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.14)
# Database: Store
# Generation Time: 2014-02-03 17:53:40 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `productId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `productName` varchar(255) NOT NULL DEFAULT '',
  `unitPrice` decimal(8,2) NOT NULL,
  PRIMARY KEY (`productId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;

INSERT INTO `products` (`productId`, `productName`, `unitPrice`)
VALUES
	(1,'Magic Mouse',70.00),
	(2,'Graphics Tablet',119.99),
	(3,'Keyboard',90.00),
	(4,'Macbook Pro',1599.99);

/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table promotions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `promotions`;

CREATE TABLE `promotions` (
  `promoId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `promoDesc` varchar(255) NOT NULL,
  `promoType` int(10) unsigned NOT NULL,
  `conditionUnits` int(11) DEFAULT NULL,
  `conditionCurrency` decimal(10,2) DEFAULT NULL,
  `conditionProductId` int(10) unsigned DEFAULT NULL,
  `discountPercent` decimal(4,3) DEFAULT NULL,
  `discountCurrency` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`promoId`),
  KEY `fkPromotion_promoTypes` (`promoType`),
  KEY `fkPromotion_products` (`conditionProductId`),
  CONSTRAINT `fkPromotion_products` FOREIGN KEY (`conditionProductId`) REFERENCES `products` (`productId`) ON DELETE CASCADE,
  CONSTRAINT `fkPromotion_promoTypes` FOREIGN KEY (`promoType`) REFERENCES `promoTypes` (`promoType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `promotions` WRITE;
/*!40000 ALTER TABLE `promotions` DISABLE KEYS */;

INSERT INTO `promotions` (`promoId`, `promoDesc`, `promoType`, `conditionUnits`, `conditionCurrency`, `conditionProductId`, `discountPercent`, `discountCurrency`)
VALUES
	(1,'Buy 3 Keyboards and get 1 free',1,3,100.00,3,0.330,0.03),
	(7,'Spend $100 and get 20% off whole order',5,3,100.00,1,0.200,15.00),
	(8,'Get $15 off Graphics Tablet',3,3,100.00,2,0.200,15.00),
	(11,'Get 20% off Macbook Pro',2,3,100.00,4,0.200,15.00),
	(12,'Buy a Magic Mouse and get 15% off whole order',4,3,100.00,1,0.150,15.00);

/*!40000 ALTER TABLE `promotions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table promoTypes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `promoTypes`;

CREATE TABLE `promoTypes` (
  `promoType` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `promotion` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`promoType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `promoTypes` WRITE;
/*!40000 ALTER TABLE `promoTypes` DISABLE KEYS */;

INSERT INTO `promoTypes` (`promoType`, `promotion`)
VALUES
	(1,'Buy x items, receive 1 additional item'),
	(2,'Buy 1 item, receive x% off that item'),
	(3,'Buy 1 item, receive $x off that item'),
	(4,'Buy 1 item, receive x% off your total order'),
	(5,'Spend $x or more, receive x% off total order');

/*!40000 ALTER TABLE `promoTypes` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
