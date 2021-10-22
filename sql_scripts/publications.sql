-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 22, 2021 at 03:21 PM
-- Server version: 10.6.4-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `publications`
--
CREATE DATABASE IF NOT EXISTS `publications` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `publications`;

-- --------------------------------------------------------

--
-- Table structure for table `Clients`
--

CREATE TABLE `Clients` (
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Modified` timestamp NULL DEFAULT NULL,
  `Version` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `CreatedBy` int(11) UNSIGNED NOT NULL,
  `ModifiedBy` int(11) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `DeletedBy` int(11) UNSIGNED DEFAULT NULL,
  `ClientID` int(11) NOT NULL,
  `Client` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `Clients`
--
DELIMITER $$
CREATE TRIGGER `before_clients_update` BEFORE UPDATE ON `Clients` FOR EACH ROW BEGIN
	SET new.Version = old.Version + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `CostCentres`
--

CREATE TABLE `CostCentres` (
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Modified` timestamp NULL DEFAULT NULL,
  `Version` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `CreatedBy` int(11) UNSIGNED NOT NULL,
  `ModifiedBy` int(11) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `DeletedBy` int(11) UNSIGNED DEFAULT NULL,
  `CostCentreID` int(11) NOT NULL,
  `CostCentre` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `CostCentres`
--
DELIMITER $$
CREATE TRIGGER `before_costCentres_update` BEFORE UPDATE ON `CostCentres` FOR EACH ROW BEGIN
	SET new.Version = old.Version + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `FiscalYears`
--

CREATE TABLE `FiscalYears` (
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Modified` timestamp NULL DEFAULT NULL,
  `Version` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `CreatedBy` int(11) UNSIGNED NOT NULL,
  `ModifiedBy` int(11) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `DeletedBy` int(11) UNSIGNED DEFAULT NULL,
  `FiscalYearID` int(11) NOT NULL,
  `FiscalYear` char(11) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `FiscalYears`
--
DELIMITER $$
CREATE TRIGGER `before_fiscalYears_update` BEFORE UPDATE ON `FiscalYears` FOR EACH ROW BEGIN
	SET new.Version = old.Version + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Journals`
--

CREATE TABLE `Journals` (
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Modified` timestamp NULL DEFAULT NULL,
  `Version` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `CreatedBy` int(11) UNSIGNED NOT NULL,
  `ModifiedBy` int(11) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `DeletedBy` int(11) UNSIGNED DEFAULT NULL,
  `JournalID` int(11) NOT NULL,
  `Journal` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `Journals`
--
DELIMITER $$
CREATE TRIGGER `before_journals_update` BEFORE UPDATE ON `Journals` FOR EACH ROW BEGIN
	SET new.Version = old.Version + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Keywords`
--

CREATE TABLE `Keywords` (
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Modified` timestamp NULL DEFAULT NULL,
  `Version` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `CreatedBy` int(11) UNSIGNED NOT NULL,
  `ModifiedBy` int(11) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `DeletedBy` int(11) UNSIGNED DEFAULT NULL,
  `KeywordID` int(11) NOT NULL,
  `KeywordEnglish` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `KeywordFrench` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `Keywords`
--
DELIMITER $$
CREATE TRIGGER `before_keywords_update` BEFORE UPDATE ON `Keywords` FOR EACH ROW BEGIN
	SET new.Version = old.Version + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `LinkTypes`
--

CREATE TABLE `LinkTypes` (
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Modified` timestamp NULL DEFAULT NULL,
  `Version` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `CreatedBy` int(11) UNSIGNED NOT NULL,
  `ModifiedBy` int(11) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `DeletedBy` int(11) UNSIGNED DEFAULT NULL,
  `LinkTypeID` int(11) NOT NULL,
  `LinkType` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `LinkTypes`
--
DELIMITER $$
CREATE TRIGGER `before_linkTypes_update` BEFORE UPDATE ON `LinkTypes` FOR EACH ROW BEGIN
	SET new.Version = old.Version + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Organizations`
--

CREATE TABLE `Organizations` (
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Modified` timestamp NULL DEFAULT NULL,
  `Version` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `CreatedBy` int(11) UNSIGNED NOT NULL,
  `ModifiedBy` int(11) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `DeletedBy` int(11) UNSIGNED DEFAULT NULL,
  `OrganizationID` int(11) NOT NULL,
  `Organization` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `Organizations`
--
DELIMITER $$
CREATE TRIGGER `before_organizations_update` BEFORE UPDATE ON `Organizations` FOR EACH ROW BEGIN
	SET new.Version = old.Version + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `People`
--

CREATE TABLE `People` (
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Modified` timestamp NULL DEFAULT NULL,
  `Version` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `CreatedBy` int(11) UNSIGNED NOT NULL,
  `ModifiedBy` int(11) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `DeletedBy` int(11) UNSIGNED DEFAULT NULL,
  `PersonID` int(11) NOT NULL,
  `LastName` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `FirstName` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DisplayName` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `OrganizationID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `People`
--
DELIMITER $$
CREATE TRIGGER `before_people_update` BEFORE UPDATE ON `People` FOR EACH ROW BEGIN
	SET new.Version = old.Version + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Publications`
--

CREATE TABLE `Publications` (
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Modified` timestamp NULL DEFAULT NULL,
  `Version` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `CreatedBy` int(11) UNSIGNED NOT NULL,
  `ModifiedBy` int(11) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `DeletedBy` int(11) UNSIGNED DEFAULT NULL,
  `PublicationID` int(11) NOT NULL,
  `PrimaryTitle` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `SecondaryTitle` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PublicationDate` date DEFAULT NULL,
  `FiscalYearID` int(11) DEFAULT NULL,
  `Volume` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `StartPage` int(11) DEFAULT NULL,
  `EndPage` int(11) DEFAULT NULL,
  `ClientID` int(11) DEFAULT NULL,
  `OrganizationID` int(11) DEFAULT NULL,
  `AbstractEnglish` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AbstractFrench` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PLSEnglish` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PLSFrench` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PRSEnglish` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PRSFrench` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ISBN` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AgreementNumber` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IPDNumber` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CrossReferenceNumber` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ProjectCode` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ReportNumber` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ManuscriptNumber` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CostCentreID` int(11) DEFAULT NULL,
  `JournalID` int(11) DEFAULT NULL,
  `ReportTypeID` int(11) NOT NULL,
  `StatusID` int(11) NOT NULL,
  `StatusPersonID` int(11) UNSIGNED DEFAULT NULL,
  `StatusDueDate` date DEFAULT NULL,
  `DOI` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `JournalSubmissionDate` date DEFAULT NULL,
  `JournalAcceptanceDate` date DEFAULT NULL,
  `ConferenceSubmissionDate` date DEFAULT NULL,
  `ConferenceAcceptanceDate` date DEFAULT NULL,
  `EmbargoPeriod` int(11) DEFAULT NULL,
  `EmbargoEndDate` date DEFAULT NULL,
  `WebPublicationDate` date DEFAULT NULL,
  `SentToClient` tinyint(4) DEFAULT NULL,
  `SentToClientDate` date DEFAULT NULL,
  `ReportFormatted` tinyint(4) DEFAULT NULL,
  `RecordNumber` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RushPublication` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `Publications`
--
DELIMITER $$
CREATE TRIGGER `before_publications_update` BEFORE UPDATE ON `Publications` FOR EACH ROW BEGIN
	SET new.Version = old.Version + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `PublicationsAuthors`
--

CREATE TABLE `PublicationsAuthors` (
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Modified` timestamp NULL DEFAULT NULL,
  `Version` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `CreatedBy` int(11) UNSIGNED NOT NULL,
  `ModifiedBy` int(11) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `DeletedBy` int(11) UNSIGNED DEFAULT NULL,
  `PublicationsAuthorsID` int(11) NOT NULL,
  `PublicationID` int(11) NOT NULL,
  `PersonID` int(11) NOT NULL,
  `PrimaryAuthor` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `PublicationsAuthors`
--
DELIMITER $$
CREATE TRIGGER `before_publicationsAuthors_update` BEFORE UPDATE ON `PublicationsAuthors` FOR EACH ROW BEGIN
	SET new.Version = old.Version + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `PublicationsComments`
--

CREATE TABLE `PublicationsComments` (
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Modified` timestamp NULL DEFAULT NULL,
  `Version` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `CreatedBy` int(11) UNSIGNED NOT NULL,
  `ModifiedBy` int(11) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `DeletedBy` int(11) UNSIGNED DEFAULT NULL,
  `PublicationsCommentsID` int(11) NOT NULL,
  `PublicationID` int(11) NOT NULL,
  `DateEntered` datetime NOT NULL,
  `Comment` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `PublicationsComments`
--
DELIMITER $$
CREATE TRIGGER `before_publicationsComments_update` BEFORE UPDATE ON `PublicationsComments` FOR EACH ROW BEGIN
	SET new.Version = old.Version + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `PublicationsKeywords`
--

CREATE TABLE `PublicationsKeywords` (
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Modified` timestamp NULL DEFAULT NULL,
  `Version` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `CreatedBy` int(11) UNSIGNED NOT NULL,
  `ModifiedBy` int(11) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `DeletedBy` int(11) UNSIGNED DEFAULT NULL,
  `PublicationsKeywordsID` int(11) NOT NULL,
  `PublicationID` int(11) NOT NULL,
  `KeywordID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `PublicationsKeywords`
--
DELIMITER $$
CREATE TRIGGER `before_publicationsKeywords_update` BEFORE UPDATE ON `PublicationsKeywords` FOR EACH ROW BEGIN
	SET new.Version = old.Version + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `PublicationsLinks`
--

CREATE TABLE `PublicationsLinks` (
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Modified` timestamp NULL DEFAULT NULL,
  `Version` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `CreatedBy` int(11) UNSIGNED NOT NULL,
  `ModifiedBy` int(11) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `DeletedBy` int(11) UNSIGNED DEFAULT NULL,
  `PublicationsLinksID` int(11) NOT NULL,
  `PublicationID` int(11) NOT NULL,
  `LinkTypeID` int(11) NOT NULL,
  `Link` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `PublicationsLinks`
--
DELIMITER $$
CREATE TRIGGER `before_publicationsLinks_update` BEFORE UPDATE ON `PublicationsLinks` FOR EACH ROW BEGIN
	SET new.Version = old.Version + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `PublicationsReviewers`
--

CREATE TABLE `PublicationsReviewers` (
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Modified` timestamp NULL DEFAULT NULL,
  `Version` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `CreatedBy` int(11) UNSIGNED NOT NULL,
  `ModifiedBy` int(11) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `DeletedBy` int(11) UNSIGNED DEFAULT NULL,
  `PublicationsReviewersID` int(11) NOT NULL,
  `PublicationID` int(11) NOT NULL,
  `PersonID` int(11) NOT NULL,
  `LeadReviewer` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `PublicationsReviewers`
--
DELIMITER $$
CREATE TRIGGER `before_publicationsReviewers_update` BEFORE UPDATE ON `PublicationsReviewers` FOR EACH ROW BEGIN
	SET new.Version = old.Version + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `PublicationsStatuses`
--

CREATE TABLE `PublicationsStatuses` (
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Modified` timestamp NULL DEFAULT NULL,
  `Version` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `CreatedBy` int(11) UNSIGNED NOT NULL,
  `ModifiedBy` int(11) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `DeletedBy` int(11) UNSIGNED DEFAULT NULL,
  `PublicationsStatusesID` int(11) NOT NULL,
  `PublicationID` int(11) NOT NULL,
  `StatusID` int(11) NOT NULL,
  `DateModified` datetime NOT NULL,
  `DueDate` date DEFAULT NULL,
  `StatusPersonID` int(11) UNSIGNED DEFAULT NULL,
  `CompletionDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `PublicationsStatuses`
--
DELIMITER $$
CREATE TRIGGER `before_publicationsStatuses_update` BEFORE UPDATE ON `PublicationsStatuses` FOR EACH ROW BEGIN
	SET new.Version = old.Version + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ReportTypes`
--

CREATE TABLE `ReportTypes` (
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Modified` timestamp NULL DEFAULT NULL,
  `Version` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `CreatedBy` int(11) UNSIGNED NOT NULL,
  `ModifiedBy` int(11) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `DeletedBy` int(11) UNSIGNED DEFAULT NULL,
  `ReportTypeID` int(11) NOT NULL,
  `ReportType` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Abbreviation` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `ReportTypes`
--
DELIMITER $$
CREATE TRIGGER `before_reportTypes_update` BEFORE UPDATE ON `ReportTypes` FOR EACH ROW BEGIN
	SET new.Version = old.Version + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Statuses`
--

CREATE TABLE `Statuses` (
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Modified` timestamp NULL DEFAULT NULL,
  `Version` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `CreatedBy` int(11) UNSIGNED NOT NULL,
  `ModifiedBy` int(11) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `DeletedBy` int(11) UNSIGNED DEFAULT NULL,
  `StatusID` int(11) NOT NULL,
  `Status` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ExpectedDuration` int(11) DEFAULT NULL,
  `DefaultStatus` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `Statuses`
--
DELIMITER $$
CREATE TRIGGER `before_statuses_update` BEFORE UPDATE ON `Statuses` FOR EACH ROW BEGIN
	SET new.Version = old.Version + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vActivePeople`
-- (See below for the actual view)
--
CREATE TABLE `vActivePeople` (
`Created` timestamp
,`Modified` timestamp
,`Version` int(11) unsigned
,`CreatedBy` int(11) unsigned
,`ModifiedBy` int(11) unsigned
,`deleted_at` timestamp
,`PersonID` int(11)
,`LastName` varchar(64)
,`FirstName` varchar(64)
,`DisplayName` varchar(128)
,`OrganizationID` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `Versions`
--

CREATE TABLE `Versions` (
  `VersionID` int(11) NOT NULL,
  `Version` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vPeopleDropDown`
-- (See below for the actual view)
--
CREATE TABLE `vPeopleDropDown` (
`PersonID` int(11)
,`DisplayName` varchar(259)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vPublicationAuthors`
-- (See below for the actual view)
--
CREATE TABLE `vPublicationAuthors` (
`PublicationID` int(11)
,`PublicationAuthors` mediumtext
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vPublicationReviewers`
-- (See below for the actual view)
--
CREATE TABLE `vPublicationReviewers` (
`PublicationID` int(11)
,`PublicationReviewers` mediumtext
);

-- --------------------------------------------------------

--
-- Structure for view `vActivePeople`
--
DROP TABLE IF EXISTS `vActivePeople`;

CREATE ALGORITHM=UNDEFINED DEFINER=`mysql`@`localhost` SQL SECURITY DEFINER VIEW `vActivePeople`  AS SELECT `People`.`Created` AS `Created`, `People`.`Modified` AS `Modified`, `People`.`Version` AS `Version`, `People`.`CreatedBy` AS `CreatedBy`, `People`.`ModifiedBy` AS `ModifiedBy`, `People`.`deleted_at` AS `deleted_at`, `People`.`PersonID` AS `PersonID`, `People`.`LastName` AS `LastName`, `People`.`FirstName` AS `FirstName`, `People`.`DisplayName` AS `DisplayName`, `People`.`OrganizationID` AS `OrganizationID` FROM `People` WHERE `People`.`deleted_at` is null ;

-- --------------------------------------------------------

--
-- Structure for view `vPeopleDropDown`
--
DROP TABLE IF EXISTS `vPeopleDropDown`;

CREATE ALGORITHM=UNDEFINED DEFINER=`mysql`@`localhost` SQL SECURITY DEFINER VIEW `vPeopleDropDown`  AS SELECT `People`.`PersonID` AS `PersonID`, concat(`People`.`DisplayName`,' (',ifnull(`Organizations`.`Organization`,'No affiliation'),')') AS `DisplayName` FROM (`People` left join `Organizations` on(`People`.`OrganizationID` = `Organizations`.`OrganizationID`)) WHERE `People`.`deleted_at` is null ORDER BY concat(`People`.`DisplayName`,' (',ifnull(`Organizations`.`Organization`,'No affiliation'),')') ASC ;

-- --------------------------------------------------------

--
-- Structure for view `vPublicationAuthors`
--
DROP TABLE IF EXISTS `vPublicationAuthors`;

CREATE ALGORITHM=UNDEFINED DEFINER=`mysql`@`localhost` SQL SECURITY DEFINER VIEW `vPublicationAuthors`  AS SELECT `pa`.`PublicationID` AS `PublicationID`, group_concat(`pe`.`DisplayName` order by `pa`.`PrimaryAuthor` DESC,`pe`.`LastName` ASC,`pe`.`FirstName` ASC separator '; ') AS `PublicationAuthors` FROM (`PublicationsAuthors` `pa` left join `People` `pe` on(`pa`.`PersonID` = `pe`.`PersonID`)) WHERE `pa`.`deleted_at` is null GROUP BY `pa`.`PublicationID` ;

-- --------------------------------------------------------

--
-- Structure for view `vPublicationReviewers`
--
DROP TABLE IF EXISTS `vPublicationReviewers`;

CREATE ALGORITHM=UNDEFINED DEFINER=`mysql`@`localhost` SQL SECURITY DEFINER VIEW `vPublicationReviewers`  AS SELECT `pr`.`PublicationID` AS `PublicationID`, group_concat(`pe`.`DisplayName` order by `pr`.`LeadReviewer` DESC,`pe`.`DisplayName` ASC separator '; ') AS `PublicationReviewers` FROM (`PublicationsReviewers` `pr` left join `People` `pe` on(`pr`.`PersonID` = `pe`.`PersonID`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Clients`
--
ALTER TABLE `Clients`
  ADD PRIMARY KEY (`ClientID`),
  ADD UNIQUE KEY `idx_Clients_Client` (`Client`,`deleted_at`) USING BTREE,
  ADD KEY `fk_Clients_Users_CreatedBy` (`CreatedBy`),
  ADD KEY `fk_Clients_Users_ModifiedBy` (`ModifiedBy`),
  ADD KEY `fk_Clients_Users_DeletedBy` (`DeletedBy`);

--
-- Indexes for table `CostCentres`
--
ALTER TABLE `CostCentres`
  ADD PRIMARY KEY (`CostCentreID`),
  ADD UNIQUE KEY `idx_CostCentres_CostCentre` (`CostCentre`,`deleted_at`) USING BTREE,
  ADD KEY `fk_CostCentres_Users_CreatedBy` (`CreatedBy`),
  ADD KEY `fk_CostCentres_Users_ModifiedBy` (`ModifiedBy`),
  ADD KEY `fk_CostCentres_Users_DeletedBy` (`DeletedBy`);

--
-- Indexes for table `FiscalYears`
--
ALTER TABLE `FiscalYears`
  ADD PRIMARY KEY (`FiscalYearID`),
  ADD UNIQUE KEY `idx_FiscalYears_FiscalYear` (`FiscalYear`,`deleted_at`) USING BTREE,
  ADD KEY `fk_FiscalYears_Users_CreatedBy` (`CreatedBy`),
  ADD KEY `fk_FiscalYears_Users_ModifiedBy` (`ModifiedBy`),
  ADD KEY `fk_FiscalYears_Users_DeletedBy` (`DeletedBy`);

--
-- Indexes for table `Journals`
--
ALTER TABLE `Journals`
  ADD PRIMARY KEY (`JournalID`),
  ADD UNIQUE KEY `idx_Journals_Journal` (`Journal`,`deleted_at`) USING BTREE,
  ADD KEY `fk_Journals_Users_CreatedBy` (`CreatedBy`),
  ADD KEY `fk_Journals_Users_ModifiedBy` (`ModifiedBy`),
  ADD KEY `fk_Journals_Users_DeletedBy` (`DeletedBy`);

--
-- Indexes for table `Keywords`
--
ALTER TABLE `Keywords`
  ADD PRIMARY KEY (`KeywordID`),
  ADD KEY `idx_Keywords_KeywordEnglish` (`KeywordEnglish`),
  ADD KEY `idx_Keywords_KeywordFrench` (`KeywordFrench`),
  ADD KEY `fk_Keywords_Users_CreatedBy` (`CreatedBy`),
  ADD KEY `fk_Keywords_Users_ModifiedBy` (`ModifiedBy`),
  ADD KEY `fk_Keywords_Users_DeletedBy` (`DeletedBy`);

--
-- Indexes for table `LinkTypes`
--
ALTER TABLE `LinkTypes`
  ADD PRIMARY KEY (`LinkTypeID`),
  ADD UNIQUE KEY `idx_LinkTypes_LinkType` (`LinkType`,`deleted_at`) USING BTREE,
  ADD KEY `fk_LinkTypes_Users_CreatedBy` (`CreatedBy`),
  ADD KEY `fk_LinkTypes_Users_ModifiedBy` (`ModifiedBy`),
  ADD KEY `fk_LinkTypes_Users_DeletedBy` (`DeletedBy`);

--
-- Indexes for table `Organizations`
--
ALTER TABLE `Organizations`
  ADD PRIMARY KEY (`OrganizationID`),
  ADD UNIQUE KEY `idx_Organizations_Organization` (`Organization`,`deleted_at`) USING BTREE,
  ADD KEY `fk_Organizations_Users_CreatedBy` (`CreatedBy`),
  ADD KEY `fk_Organizations_Users_ModifiedBy` (`ModifiedBy`),
  ADD KEY `fk_Organizations_Users_DeletedBy` (`DeletedBy`);

--
-- Indexes for table `People`
--
ALTER TABLE `People`
  ADD PRIMARY KEY (`PersonID`),
  ADD KEY `idx_Person_Name` (`LastName`,`FirstName`),
  ADD KEY `fk_People_Organizations` (`OrganizationID`),
  ADD KEY `idx_Person_DisplayName` (`DisplayName`),
  ADD KEY `fk_People_Users_CreatedBy` (`CreatedBy`),
  ADD KEY `fk_People_Users_ModifiedBy` (`ModifiedBy`),
  ADD KEY `fk_People_Users_` (`DeletedBy`);

--
-- Indexes for table `Publications`
--
ALTER TABLE `Publications`
  ADD PRIMARY KEY (`PublicationID`),
  ADD KEY `fk_Publications_Clients` (`ClientID`),
  ADD KEY `fk_Publications_CostCentres` (`CostCentreID`),
  ADD KEY `fk_Publications_FiscalYears` (`FiscalYearID`),
  ADD KEY `fk_Publications_Journals` (`JournalID`),
  ADD KEY `fk_Publications_Organizations` (`OrganizationID`),
  ADD KEY `fk_Publications_ReportTypes` (`ReportTypeID`),
  ADD KEY `fk_Publications_Statuses` (`StatusID`),
  ADD KEY `fk_Publications_Users_StatusPersonID` (`StatusPersonID`),
  ADD KEY `idx_Publications_CreatedBy` (`CreatedBy`) USING BTREE,
  ADD KEY `fk_Publications_Users_ModifiedBy` (`ModifiedBy`),
  ADD KEY `fk_Publications_Users_DeletedBy` (`DeletedBy`);
ALTER TABLE `Publications` ADD FULLTEXT KEY `idx_Publications_PrimaryTitle` (`PrimaryTitle`);

--
-- Indexes for table `PublicationsAuthors`
--
ALTER TABLE `PublicationsAuthors`
  ADD PRIMARY KEY (`PublicationsAuthorsID`),
  ADD KEY `fk_PublicationsAuthors_Publications` (`PublicationID`),
  ADD KEY `fk_PublicationsAuthors_People` (`PersonID`),
  ADD KEY `fk_PublicationsAuthors_Users_CreatedBy` (`CreatedBy`),
  ADD KEY `fk_PublicationsAuthors_Users_ModifiedBy` (`ModifiedBy`),
  ADD KEY `fk_PublicationsAuthors_Users_DeletedBy` (`DeletedBy`);

--
-- Indexes for table `PublicationsComments`
--
ALTER TABLE `PublicationsComments`
  ADD PRIMARY KEY (`PublicationsCommentsID`),
  ADD KEY `fk_PublicationsComments_Publications` (`PublicationID`),
  ADD KEY `fk_PublicationsComments_Users_CreatedBy` (`CreatedBy`),
  ADD KEY `fk_PublicationsComments_Users_ModifiedBy` (`ModifiedBy`),
  ADD KEY `fk_PublicationsComments_Users_DeletedBy` (`DeletedBy`);

--
-- Indexes for table `PublicationsKeywords`
--
ALTER TABLE `PublicationsKeywords`
  ADD PRIMARY KEY (`PublicationsKeywordsID`),
  ADD KEY `fk_PublicationsKeywords_Publications` (`PublicationID`),
  ADD KEY `fk_PublicationsKeywords_Keywords` (`KeywordID`),
  ADD KEY `fk_PublicationsKeywords_Users_CreatedBy` (`CreatedBy`),
  ADD KEY `fk_PublicationsKeywords_Users_ModifiedBy` (`ModifiedBy`),
  ADD KEY `fk_PublicationsKeywords_Users_DeletedBy` (`DeletedBy`);

--
-- Indexes for table `PublicationsLinks`
--
ALTER TABLE `PublicationsLinks`
  ADD PRIMARY KEY (`PublicationsLinksID`),
  ADD KEY `fk_PublicationsLinks_Publications` (`PublicationID`),
  ADD KEY `fk_PublicationsLinks_LinkTypes` (`LinkTypeID`),
  ADD KEY `fk_PublicationsLinks_Users_CreatedBy` (`CreatedBy`),
  ADD KEY `fk_PublicationsLinks_Users_ModifiedBy` (`ModifiedBy`),
  ADD KEY `fk_PublicationsLinks_Users_DeletedBy` (`DeletedBy`);

--
-- Indexes for table `PublicationsReviewers`
--
ALTER TABLE `PublicationsReviewers`
  ADD PRIMARY KEY (`PublicationsReviewersID`),
  ADD KEY `fk_PublicationsReviewers_Publications` (`PublicationID`),
  ADD KEY `fk_PublicationsReviewers_People` (`PersonID`),
  ADD KEY `fk_PublicationsReviewers_Users_CreatedBy` (`CreatedBy`),
  ADD KEY `fk_PublicationsReviewers_Users_ModifiedBy` (`ModifiedBy`),
  ADD KEY `fk_PublicationsReviewers_Users_DeletedBy` (`DeletedBy`);

--
-- Indexes for table `PublicationsStatuses`
--
ALTER TABLE `PublicationsStatuses`
  ADD PRIMARY KEY (`PublicationsStatusesID`),
  ADD KEY `fk_PublicationsStatuses_Publications` (`PublicationID`),
  ADD KEY `fk_PublicationsStatuses_Statuses` (`StatusID`),
  ADD KEY `fk_PublicationsStatuses_Users_CreatedBy` (`CreatedBy`),
  ADD KEY `fk_PublicationsStatuses_Users_ModifiedBy` (`ModifiedBy`),
  ADD KEY `fk_PublicationsStatuses_Users_StatusPersonID` (`StatusPersonID`),
  ADD KEY `fk_PublicationsStatuses_Users_DeletedBy` (`DeletedBy`);

--
-- Indexes for table `ReportTypes`
--
ALTER TABLE `ReportTypes`
  ADD PRIMARY KEY (`ReportTypeID`),
  ADD UNIQUE KEY `idx_ReportTypes_ReportType` (`ReportType`,`deleted_at`) USING BTREE,
  ADD UNIQUE KEY `idx_ReportTypes_Abbreviation` (`Abbreviation`,`deleted_at`) USING BTREE,
  ADD KEY `fk_ReportTypes_Users_CreatedBy` (`CreatedBy`),
  ADD KEY `fk_ReportTypes_Users_ModifiedBy` (`ModifiedBy`),
  ADD KEY `fk_ReportTypes_Users_DeletedBy` (`DeletedBy`);

--
-- Indexes for table `Statuses`
--
ALTER TABLE `Statuses`
  ADD PRIMARY KEY (`StatusID`),
  ADD UNIQUE KEY `idx_Statuses_Status` (`Status`,`deleted_at`) USING BTREE,
  ADD KEY `fk_Statuses_Users_CreatedBy` (`CreatedBy`),
  ADD KEY `fk_Statuses_Users_ModifiedBy` (`ModifiedBy`),
  ADD KEY `fk_Statuses_Users_DeletedBy` (`DeletedBy`);

--
-- Indexes for table `Versions`
--
ALTER TABLE `Versions`
  ADD PRIMARY KEY (`VersionID`),
  ADD UNIQUE KEY `idx_Versions_Version` (`Version`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Clients`
--
ALTER TABLE `Clients`
  MODIFY `ClientID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `CostCentres`
--
ALTER TABLE `CostCentres`
  MODIFY `CostCentreID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `FiscalYears`
--
ALTER TABLE `FiscalYears`
  MODIFY `FiscalYearID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Journals`
--
ALTER TABLE `Journals`
  MODIFY `JournalID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Keywords`
--
ALTER TABLE `Keywords`
  MODIFY `KeywordID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `LinkTypes`
--
ALTER TABLE `LinkTypes`
  MODIFY `LinkTypeID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Organizations`
--
ALTER TABLE `Organizations`
  MODIFY `OrganizationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `People`
--
ALTER TABLE `People`
  MODIFY `PersonID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Publications`
--
ALTER TABLE `Publications`
  MODIFY `PublicationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PublicationsAuthors`
--
ALTER TABLE `PublicationsAuthors`
  MODIFY `PublicationsAuthorsID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PublicationsComments`
--
ALTER TABLE `PublicationsComments`
  MODIFY `PublicationsCommentsID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PublicationsKeywords`
--
ALTER TABLE `PublicationsKeywords`
  MODIFY `PublicationsKeywordsID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PublicationsLinks`
--
ALTER TABLE `PublicationsLinks`
  MODIFY `PublicationsLinksID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PublicationsReviewers`
--
ALTER TABLE `PublicationsReviewers`
  MODIFY `PublicationsReviewersID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PublicationsStatuses`
--
ALTER TABLE `PublicationsStatuses`
  MODIFY `PublicationsStatusesID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ReportTypes`
--
ALTER TABLE `ReportTypes`
  MODIFY `ReportTypeID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Statuses`
--
ALTER TABLE `Statuses`
  MODIFY `StatusID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Versions`
--
ALTER TABLE `Versions`
  MODIFY `VersionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Clients`
--
ALTER TABLE `Clients`
  ADD CONSTRAINT `fk_Clients_Users_CreatedBy` FOREIGN KEY (`CreatedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_Clients_Users_DeletedBy` FOREIGN KEY (`DeletedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_Clients_Users_ModifiedBy` FOREIGN KEY (`ModifiedBy`) REFERENCES `users`.`users` (`id`);

--
-- Constraints for table `CostCentres`
--
ALTER TABLE `CostCentres`
  ADD CONSTRAINT `fk_CostCentres_Users_CreatedBy` FOREIGN KEY (`CreatedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_CostCentres_Users_DeletedBy` FOREIGN KEY (`DeletedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_CostCentres_Users_ModifiedBy` FOREIGN KEY (`ModifiedBy`) REFERENCES `users`.`users` (`id`);

--
-- Constraints for table `FiscalYears`
--
ALTER TABLE `FiscalYears`
  ADD CONSTRAINT `fk_FiscalYears_Users_CreatedBy` FOREIGN KEY (`CreatedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_FiscalYears_Users_DeletedBy` FOREIGN KEY (`DeletedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_FiscalYears_Users_ModifiedBy` FOREIGN KEY (`ModifiedBy`) REFERENCES `users`.`users` (`id`);

--
-- Constraints for table `Journals`
--
ALTER TABLE `Journals`
  ADD CONSTRAINT `fk_Journals_Users_CreatedBy` FOREIGN KEY (`CreatedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_Journals_Users_DeletedBy` FOREIGN KEY (`DeletedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_Journals_Users_ModifiedBy` FOREIGN KEY (`ModifiedBy`) REFERENCES `users`.`users` (`id`);

--
-- Constraints for table `Keywords`
--
ALTER TABLE `Keywords`
  ADD CONSTRAINT `fk_Keywords_Users_CreatedBy` FOREIGN KEY (`CreatedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_Keywords_Users_DeletedBy` FOREIGN KEY (`DeletedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_Keywords_Users_ModifiedBy` FOREIGN KEY (`ModifiedBy`) REFERENCES `users`.`users` (`id`);

--
-- Constraints for table `LinkTypes`
--
ALTER TABLE `LinkTypes`
  ADD CONSTRAINT `fk_LinkTypes_Users_CreatedBy` FOREIGN KEY (`CreatedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_LinkTypes_Users_DeletedBy` FOREIGN KEY (`DeletedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_LinkTypes_Users_ModifiedBy` FOREIGN KEY (`ModifiedBy`) REFERENCES `users`.`users` (`id`);

--
-- Constraints for table `Organizations`
--
ALTER TABLE `Organizations`
  ADD CONSTRAINT `fk_Organizations_Users_CreatedBy` FOREIGN KEY (`CreatedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_Organizations_Users_DeletedBy` FOREIGN KEY (`DeletedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_Organizations_Users_ModifiedBy` FOREIGN KEY (`ModifiedBy`) REFERENCES `users`.`users` (`id`);

--
-- Constraints for table `People`
--
ALTER TABLE `People`
  ADD CONSTRAINT `fk_People_Organizations` FOREIGN KEY (`OrganizationID`) REFERENCES `Organizations` (`OrganizationID`),
  ADD CONSTRAINT `fk_People_Users_` FOREIGN KEY (`DeletedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_People_Users_CreatedBy` FOREIGN KEY (`CreatedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_People_Users_ModifiedBy` FOREIGN KEY (`ModifiedBy`) REFERENCES `users`.`users` (`id`);

--
-- Constraints for table `Publications`
--
ALTER TABLE `Publications`
  ADD CONSTRAINT `fk_Publications_Clients` FOREIGN KEY (`ClientID`) REFERENCES `Clients` (`ClientID`),
  ADD CONSTRAINT `fk_Publications_CostCentres` FOREIGN KEY (`CostCentreID`) REFERENCES `CostCentres` (`CostCentreID`),
  ADD CONSTRAINT `fk_Publications_FiscalYears` FOREIGN KEY (`FiscalYearID`) REFERENCES `FiscalYears` (`FiscalYearID`),
  ADD CONSTRAINT `fk_Publications_Journals` FOREIGN KEY (`JournalID`) REFERENCES `Journals` (`JournalID`),
  ADD CONSTRAINT `fk_Publications_Organizations` FOREIGN KEY (`OrganizationID`) REFERENCES `Organizations` (`OrganizationID`),
  ADD CONSTRAINT `fk_Publications_ReportTypes` FOREIGN KEY (`ReportTypeID`) REFERENCES `ReportTypes` (`ReportTypeID`),
  ADD CONSTRAINT `fk_Publications_Statuses` FOREIGN KEY (`StatusID`) REFERENCES `Statuses` (`StatusID`),
  ADD CONSTRAINT `fk_Publications_Users_CreatedBy` FOREIGN KEY (`CreatedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_Publications_Users_DeletedBy` FOREIGN KEY (`DeletedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_Publications_Users_ModifiedBy` FOREIGN KEY (`ModifiedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_Publications_Users_StatusPersonID` FOREIGN KEY (`StatusPersonID`) REFERENCES `users`.`users` (`id`);

--
-- Constraints for table `PublicationsAuthors`
--
ALTER TABLE `PublicationsAuthors`
  ADD CONSTRAINT `fk_PublicationsAuthors_People` FOREIGN KEY (`PersonID`) REFERENCES `People` (`PersonID`),
  ADD CONSTRAINT `fk_PublicationsAuthors_Publications` FOREIGN KEY (`PublicationID`) REFERENCES `Publications` (`PublicationID`),
  ADD CONSTRAINT `fk_PublicationsAuthors_Users_CreatedBy` FOREIGN KEY (`CreatedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_PublicationsAuthors_Users_DeletedBy` FOREIGN KEY (`DeletedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_PublicationsAuthors_Users_ModifiedBy` FOREIGN KEY (`ModifiedBy`) REFERENCES `users`.`users` (`id`);

--
-- Constraints for table `PublicationsComments`
--
ALTER TABLE `PublicationsComments`
  ADD CONSTRAINT `fk_PublicationsComments_Publications` FOREIGN KEY (`PublicationID`) REFERENCES `Publications` (`PublicationID`),
  ADD CONSTRAINT `fk_PublicationsComments_Users_CreatedBy` FOREIGN KEY (`CreatedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_PublicationsComments_Users_DeletedBy` FOREIGN KEY (`DeletedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_PublicationsComments_Users_ModifiedBy` FOREIGN KEY (`ModifiedBy`) REFERENCES `users`.`users` (`id`);

--
-- Constraints for table `PublicationsKeywords`
--
ALTER TABLE `PublicationsKeywords`
  ADD CONSTRAINT `fk_PublicationsKeywords_Keywords` FOREIGN KEY (`KeywordID`) REFERENCES `Keywords` (`KeywordID`),
  ADD CONSTRAINT `fk_PublicationsKeywords_Publications` FOREIGN KEY (`PublicationID`) REFERENCES `Publications` (`PublicationID`),
  ADD CONSTRAINT `fk_PublicationsKeywords_Users_CreatedBy` FOREIGN KEY (`CreatedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_PublicationsKeywords_Users_DeletedBy` FOREIGN KEY (`DeletedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_PublicationsKeywords_Users_ModifiedBy` FOREIGN KEY (`ModifiedBy`) REFERENCES `users`.`users` (`id`);

--
-- Constraints for table `PublicationsLinks`
--
ALTER TABLE `PublicationsLinks`
  ADD CONSTRAINT `fk_PublicationsLinks_LinkTypes` FOREIGN KEY (`LinkTypeID`) REFERENCES `LinkTypes` (`LinkTypeID`),
  ADD CONSTRAINT `fk_PublicationsLinks_Publications` FOREIGN KEY (`PublicationID`) REFERENCES `Publications` (`PublicationID`),
  ADD CONSTRAINT `fk_PublicationsLinks_Users_CreatedBy` FOREIGN KEY (`CreatedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_PublicationsLinks_Users_DeletedBy` FOREIGN KEY (`DeletedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_PublicationsLinks_Users_ModifiedBy` FOREIGN KEY (`ModifiedBy`) REFERENCES `users`.`users` (`id`);

--
-- Constraints for table `PublicationsReviewers`
--
ALTER TABLE `PublicationsReviewers`
  ADD CONSTRAINT `fk_PublicationsReviewers_People` FOREIGN KEY (`PersonID`) REFERENCES `People` (`PersonID`),
  ADD CONSTRAINT `fk_PublicationsReviewers_Publications` FOREIGN KEY (`PublicationID`) REFERENCES `Publications` (`PublicationID`),
  ADD CONSTRAINT `fk_PublicationsReviewers_Users_CreatedBy` FOREIGN KEY (`CreatedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_PublicationsReviewers_Users_DeletedBy` FOREIGN KEY (`DeletedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_PublicationsReviewers_Users_ModifiedBy` FOREIGN KEY (`ModifiedBy`) REFERENCES `users`.`users` (`id`);

--
-- Constraints for table `PublicationsStatuses`
--
ALTER TABLE `PublicationsStatuses`
  ADD CONSTRAINT `fk_PublicationsStatuses_Publications` FOREIGN KEY (`PublicationID`) REFERENCES `Publications` (`PublicationID`),
  ADD CONSTRAINT `fk_PublicationsStatuses_Statuses` FOREIGN KEY (`StatusID`) REFERENCES `Statuses` (`StatusID`),
  ADD CONSTRAINT `fk_PublicationsStatuses_Users_CreatedBy` FOREIGN KEY (`CreatedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_PublicationsStatuses_Users_DeletedBy` FOREIGN KEY (`DeletedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_PublicationsStatuses_Users_ModifiedBy` FOREIGN KEY (`ModifiedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_PublicationsStatuses_Users_StatusPersonID` FOREIGN KEY (`StatusPersonID`) REFERENCES `users`.`users` (`id`);

--
-- Constraints for table `ReportTypes`
--
ALTER TABLE `ReportTypes`
  ADD CONSTRAINT `fk_ReportTypes_Users_CreatedBy` FOREIGN KEY (`CreatedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_ReportTypes_Users_DeletedBy` FOREIGN KEY (`DeletedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_ReportTypes_Users_ModifiedBy` FOREIGN KEY (`ModifiedBy`) REFERENCES `users`.`users` (`id`);

--
-- Constraints for table `Statuses`
--
ALTER TABLE `Statuses`
  ADD CONSTRAINT `fk_Statuses_Users_CreatedBy` FOREIGN KEY (`CreatedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_Statuses_Users_DeletedBy` FOREIGN KEY (`DeletedBy`) REFERENCES `users`.`users` (`id`),
  ADD CONSTRAINT `fk_Statuses_Users_ModifiedBy` FOREIGN KEY (`ModifiedBy`) REFERENCES `users`.`users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
