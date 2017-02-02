-- MySQL dump 10.13  Distrib 5.7.17, for macos10.12 (x86_64)
--
-- Host: localhost    Database: pse
-- ------------------------------------------------------
-- Server version	5.7.17

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `active_trade`
--

DROP TABLE IF EXISTS `active_trade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_trade` (
  `symbol` varchar(10) NOT NULL,
  `average_price` float NOT NULL,
  `total_shares` float NOT NULL,
  `target_price` float DEFAULT NULL,
  `pattern` varchar(45) DEFAULT NULL,
  `indicator` varchar(45) DEFAULT NULL,
  `reco` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`symbol`),
  UNIQUE KEY `symbol` (`symbol`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `daily`
--

DROP TABLE IF EXISTS `daily`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daily` (
  `symbol` varchar(10) NOT NULL,
  `recdate` date NOT NULL,
  `lastprice` float NOT NULL,
  `open` float NOT NULL,
  `volume` float NOT NULL,
  `pricechange` float NOT NULL,
  `low` float NOT NULL,
  `value` float NOT NULL,
  `percentchange` float NOT NULL,
  `high` float NOT NULL,
  `netforeign` float NOT NULL,
  `prevclose` float NOT NULL,
  `aveprice` float NOT NULL,
  PRIMARY KEY (`symbol`,`recdate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fundamental`
--

DROP TABLE IF EXISTS `fundamental`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fundamental` (
  `symbol` varchar(10) NOT NULL,
  `recdate` datetime NOT NULL,
  `weekhigh` float NOT NULL,
  `eps` float NOT NULL,
  `epspercent` float NOT NULL,
  `bookvalue` float NOT NULL,
  `weeklow` float NOT NULL,
  `earnratio` float NOT NULL,
  `roe` float NOT NULL,
  `fairvalue` float NOT NULL,
  `fairvaluepercent` float NOT NULL,
  `dps` float NOT NULL,
  PRIMARY KEY (`symbol`,`recdate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stockticker`
--

DROP TABLE IF EXISTS `stockticker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stockticker` (
  `symbol` varchar(10) NOT NULL,
  `name` text NOT NULL,
  `amount` float NOT NULL,
  `percent_change` float NOT NULL,
  `volume` float NOT NULL,
  PRIMARY KEY (`symbol`),
  UNIQUE KEY `symbol` (`symbol`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stockticker_int`
--

DROP TABLE IF EXISTS `stockticker_int`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stockticker_int` (
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `symbol` varchar(10) NOT NULL,
  `amount` float NOT NULL,
  `percent_change` float NOT NULL,
  `volume` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `technical_full`
--

DROP TABLE IF EXISTS `technical_full`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technical_full` (
  `symbol` varchar(10) NOT NULL,
  `recdate` datetime NOT NULL,
  `support1` float NOT NULL,
  `resistance1` float NOT NULL,
  `shorttrend` varchar(25) NOT NULL,
  `support2` float NOT NULL,
  `resistance2` float NOT NULL,
  `yeartodate` float NOT NULL,
  `monthtodate` float NOT NULL,
  `ma20s` float NOT NULL,
  `ma20s_stat` varchar(25) NOT NULL,
  `ma20e` float NOT NULL,
  `ma20e_stat` varchar(25) NOT NULL,
  `ma50s` float NOT NULL,
  `ma50s_stat` varchar(25) NOT NULL,
  `ma50e` float NOT NULL,
  `ma50e_stat` varchar(25) NOT NULL,
  `ma100s` float NOT NULL,
  `ma100s_stat` varchar(25) NOT NULL,
  `ma100e` float NOT NULL,
  `ma100e_stat` varchar(25) NOT NULL,
  `ma200s` float NOT NULL,
  `ma200s_stat` varchar(25) NOT NULL,
  `ma200e` float NOT NULL,
  `ma200e_stat` varchar(25) NOT NULL,
  `rsi` float NOT NULL,
  `rsi_stat` varchar(25) NOT NULL,
  `macd` float NOT NULL,
  `macd_signal` float NOT NULL,
  `macd_stat` varchar(25) NOT NULL,
  `atr` float NOT NULL,
  `atr_percent` float NOT NULL,
  `atr_stat` varchar(25) NOT NULL,
  `cci` float NOT NULL,
  `cci_stat` varchar(25) NOT NULL,
  `sts` float NOT NULL,
  `sts_stat` varchar(25) NOT NULL,
  `williams` float NOT NULL,
  `williams_stat` varchar(25) NOT NULL,
  `volumesma` float NOT NULL,
  `volumesma_stat` varchar(25) NOT NULL,
  `candlestick` varchar(25) NOT NULL,
  `candlestick_stat` varchar(25) NOT NULL,
  PRIMARY KEY (`symbol`,`recdate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `technical_indicator`
--

DROP TABLE IF EXISTS `technical_indicator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technical_indicator` (
  `symbol` varchar(10) NOT NULL,
  `recdate` datetime NOT NULL,
  `indicator` varchar(25) NOT NULL,
  `value1` float DEFAULT NULL,
  `value2` float DEFAULT NULL,
  `tvalue` text,
  `status` varchar(25) NOT NULL,
  PRIMARY KEY (`symbol`,`recdate`,`indicator`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `technical_summary`
--

DROP TABLE IF EXISTS `technical_summary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technical_summary` (
  `symbol` varchar(10) NOT NULL,
  `recdate` datetime NOT NULL,
  `support1` float NOT NULL,
  `resistance1` float NOT NULL,
  `shorttrend` varchar(25) NOT NULL,
  `support2` float NOT NULL,
  `resistance2` float NOT NULL,
  `yeartodate` float NOT NULL,
  `monthtodate` float NOT NULL,
  PRIMARY KEY (`symbol`,`recdate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `watchlist`
--

DROP TABLE IF EXISTS `watchlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `watchlist` (
  `symbol` varchar(10) NOT NULL,
  `psei` tinyint(1) NOT NULL,
  `col` tinyint(1) NOT NULL,
  `trc` tinyint(1) NOT NULL,
  `group` varchar(30) NOT NULL,
  `sector` varchar(20) NOT NULL,
  `weight` float NOT NULL,
  UNIQUE KEY `symbol` (`symbol`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-02-02 16:03:39
