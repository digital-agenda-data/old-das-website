-- phpMyAdmin SQL Dump
-- version 3.3.10
-- http://www.phpmyadmin.net
--
-- Host: prod1.aksw.org
-- Erstellungszeit: 15. Juni 2012 um 14:39
-- Server Version: 5.1.63
-- PHP-Version: 5.2.0-8+etch16

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `scoreboard_test`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sources`
--

CREATE TABLE IF NOT EXISTS `sources` (
  `source_code` varchar(12) DEFAULT NULL,
  `source_label` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `version_info` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `sources`
--

INSERT INTO `sources` (`source_code`, `source_label`, `url`, `version_info`) VALUES
('ESTAT HH-IND', 'Eurostat - Community survey on ICT usage in Households and by Individuals', 'http://epp.eurostat.ec.europa.eu/portal/page/portal/information_society/introduction', 'Extraction from HH/Indiv comprehensive database (ACCESS) version 15 MAY 2012'),
('COCOM', 'Electronic communications market indicators collected by Commission services, through National Regulatory Authorities, for the Communications Committee (COCOM) - January reports.', 'http://ec.europa.eu/information_society/digital-agenda/scoreboard/pillars/broadband/index_en.htm', 'extraction 31 May 2012'),
('Idate', 'Broadband Coverage in Europe, Studies for the EC realised by IDATE (2005-2010 data)', 'http://ec.europa.eu/information_society/digital-agenda/scoreboard/docs/pillar/broadband_coverage_2010.pdf', 'extraction 4 May 2011'),
('ESTAT ENT', 'Eurostat - Community survey on ICT usage and eCommerce in Enterprises', 'http://epp.eurostat.ec.europa.eu/portal/page/portal/information_society/introduction', 'Extraction from ENT2 comprehensive database (NACE Rev 2 in ACCESS 107 MB) version 23 March 2012, and from ENT (NACE Rev 1.1 in ACCESS 64 MB) version 8 Dec 2010'),
('CAPGEMINI', 'eGovernment Benchmarking Report, Study for the EC realised by Capgemini (2001-2010 data)', 'http://ec.europa.eu/information_society/eeurope/i2010/benchmarking/index_en.htm#e-Government_Benchmarking_Reports', 'Extraction 20 May 2011'),
('MIS', 'EC database of ICT research projects under the EU’s Seventh Framework Programme (FP7) - Cooperation program. Projects under the Capacities program or the CIP ICT PSP are not included.', 'http://cordis.europa.eu/fp7/ict/home_en.html', 'march 2012'),
('IPv6obs', 'IPv6 Observatory, Study for the EC realized by inno', 'http://www.ipv6observatory.eu/the-study/', 'extraction published 3 June 2012');
