-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 24, 2024 at 11:33 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ehr`
--

-- --------------------------------------------------------

--
-- Table structure for table `Appointments`
--

CREATE TABLE `Appointments` (
  `AppointmentID` int(11) NOT NULL,
  `PatientID` int(11) DEFAULT NULL,
  `DoctorID` int(11) DEFAULT NULL,
  `ClinicID` int(11) DEFAULT NULL,
  `TimeSlot` time DEFAULT NULL,
  `AppointmentDate` date DEFAULT NULL,
  `ConsultationType` varchar(255) DEFAULT NULL,
  `Speciality` varchar(255) DEFAULT NULL,
  `Status` varchar(20) DEFAULT 'Scheduled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Appointments`
--

INSERT INTO `Appointments` (`AppointmentID`, `PatientID`, `DoctorID`, `ClinicID`, `TimeSlot`, `AppointmentDate`, `ConsultationType`, `Speciality`, `Status`) VALUES
(7, 4, 1, 3, '09:00:00', '2024-01-18', 'Regular Checkup', 'Cardiology', 'Scheduled'),
(8, 3, 1, 3, '10:30:00', '2024-01-19', 'Follow-up', 'Orthopedics', 'Scheduled'),
(9, 3, 1, 3, '14:00:00', '2024-01-20', 'Initial Consultation', 'Dermatology', 'Scheduled'),
(10, 5, 1, 3, '11:15:00', '2024-01-21', 'Regular Checkup', 'Neurology', 'Scheduled'),
(11, 4, 1, 3, '15:45:00', '2024-01-22', 'Follow-up', 'Gastroenterology', 'Scheduled'),
(12, 6, 1, 3, '08:30:00', '2024-01-23', 'Regular Checkup', 'Ophthalmology', 'Scheduled');

-- --------------------------------------------------------

--
-- Table structure for table `clinics`
--

CREATE TABLE `clinics` (
  `ClinicID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clinics`
--

INSERT INTO `clinics` (`ClinicID`, `Name`, `Location`) VALUES
(1, 'Passau Clinic', 'Innstraße 76, 94032 Passau'),
(2, 'Rottal-In Clinic', 'Am Griesberg 1, 84347 Pfarrkirchen'),
(3, 'Eggenfelden Clinic', 'Simonsöder Allee 20, 84307 Eggenfelden'),
(4, 'Munich Clinic', 'Bavariaring 46, 80336 München'),
(5, 'Mühldorf Clinic', 'Krankenhausstraße 1, 84453 Mühldorf am Inn'),
(6, 'Burghausen Clinic', 'Krankenhausstraße 3a, 84489 Burghausen'),
(7, 'Pocking Clinic', 'Berger Str. 1, 94060 Pocking'),
(8, 'Augsburg Clinic', 'Annastraße 2, 86150 Augsburg'),
(9, 'Bayreuth Clinic', 'Kurpromenade 2, 95448 Bayreuth');

-- --------------------------------------------------------

--
-- Table structure for table `clinicspecialities`
--

CREATE TABLE `clinicspecialities` (
  `ClinicID` int(11) NOT NULL,
  `SpecialityID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clinicspecialities`
--

INSERT INTO `clinicspecialities` (`ClinicID`, `SpecialityID`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(3, 7),
(4, 8),
(5, 3),
(5, 4),
(5, 5),
(5, 8),
(6, 4),
(6, 5),
(6, 8),
(7, 6),
(8, 9);

-- --------------------------------------------------------

--
-- Table structure for table `doctorclinic`
--

CREATE TABLE `doctorclinic` (
  `DoctorID` int(11) NOT NULL,
  `ClinicID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctorclinic`
--

INSERT INTO `doctorclinic` (`DoctorID`, `ClinicID`) VALUES
(1, 1),
(2, 2),
(3, 1),
(4, 2),
(5, 4),
(6, 4),
(7, 6),
(8, 5),
(9, 1),
(10, 5),
(11, 5),
(12, 6),
(13, 6),
(14, 5),
(15, 7),
(16, 7),
(17, 3),
(18, 3),
(19, 8),
(20, 8),
(31, 5),
(34, 3);

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `DoctorID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `FirstName` varchar(255) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `Speciality` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `birthdate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`DoctorID`, `UserID`, `FirstName`, `LastName`, `Speciality`, `gender`, `birthdate`) VALUES
(1, 3, 'Emily', 'Turner', 'Cardiology', NULL, NULL),
(2, 4, 'Benjamin', 'Hayes', 'Cardiology', NULL, NULL),
(3, 5, 'Sarah', 'Mitchell', 'Endocrinology', NULL, NULL),
(4, 6, 'Kevin', 'Rodriguez', 'Endocrinology', NULL, NULL),
(5, 7, 'Amanda', 'Foster', 'Rheumatology', NULL, NULL),
(6, 8, 'Robert', 'Hughes', 'Rheumatology', NULL, NULL),
(7, 15, 'Melissa', 'Thompson', 'Rheumatology', NULL, NULL),
(8, 16, 'Christopher', 'Harris', 'Rheumatology', NULL, NULL),
(9, 9, 'Jessica', 'Wong', 'Nephrology', NULL, NULL),
(10, 10, 'Michael', 'Patel', 'Nephrology', NULL, NULL),
(11, 11, 'Laura', 'Reynolds', 'Gastroenterology', NULL, NULL),
(12, 12, 'Brian', 'Lewis', 'Gastroenterology', NULL, NULL),
(13, 13, 'Rachel', 'Carter', 'Dermatology', NULL, NULL),
(14, 14, 'Jonathan', 'Kim', 'Dermatology', NULL, NULL),
(15, 17, 'Kimberly', 'Davis', 'Dentistry', NULL, NULL),
(16, 18, 'Jordan', 'Carter', 'Dentistry', NULL, NULL),
(17, 19, 'Alexandra', 'Taylor', 'Gynecology and Obstetrics', NULL, NULL),
(18, 20, 'Samuel', 'Rodriguez', 'Gynecology and Obstetrics', NULL, NULL),
(19, 21, 'Jennifer', 'White', 'Family Doctor', NULL, NULL),
(20, 22, 'David', 'Johnson', 'Family Doctor', NULL, NULL),
(31, 47, 'kelly', 'khalil', 'Gastroenterology', 'Female', '1999-01-04'),
(34, 50, 'Rodrigo', 'Azevedo', 'Cardiology', 'Male', '1999-12-04');

-- --------------------------------------------------------

--
-- Table structure for table `HealthRecords`
--

CREATE TABLE `HealthRecords` (
  `RecordID` int(11) NOT NULL,
  `PatientID` int(11) DEFAULT NULL,
  `DoctorID` int(11) DEFAULT NULL,
  `DateRecorded` date DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `medications` text DEFAULT NULL,
  `procedures` text DEFAULT NULL,
  `comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `HealthRecords`
--

INSERT INTO `HealthRecords` (`RecordID`, `PatientID`, `DoctorID`, `DateRecorded`, `diagnosis`, `medications`, `procedures`, `comments`) VALUES
(1, 4, 1, '2022-01-01', 'Pain in head', 'Medication 1', 'Procedure 1', 'Comments 1'),
(2, 4, 1, '2022-02-05', 'Pain in arm', 'Medication B', 'Procedure 2', 'Comments 2'),
(3, 4, 1, '2022-03-10', 'Condition 3', 'Medication C', 'Procedure 3', 'Comments 3'),
(4, 4, 1, '2022-04-15', 'Condition 4', 'Medication D', 'Procedure 4', 'Comments 4');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `MessageID` int(11) NOT NULL,
  `SenderID` int(11) DEFAULT NULL,
  `ReceiverID` int(11) DEFAULT NULL,
  `Content` text DEFAULT NULL,
  `Timestamp` datetime DEFAULT current_timestamp(),
  `IsRead` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`MessageID`, `SenderID`, `ReceiverID`, `Content`, `Timestamp`, `IsRead`) VALUES
(3, 3, 4, 'Hello Benjamin thank you for your message', '2024-01-18 10:48:41', 0),
(4, 3, 4, 'Could you please do a check up on patient ID 2', '2024-01-18 10:56:16', 0),
(5, 4, 3, 'Ok emily sure ill do the check up on patient ID 2', '2024-01-18 11:01:46', 0),
(6, 3, 4, 'Benjamin could you help me with the diagnosis of a patient?', '2024-01-23 13:04:56', 0),
(7, 3, 5, 'Hello Sarah, have you gotten the test results of patient 3', '2024-01-23 16:13:13', 0),
(8, 5, 3, 'Hello Emily, i still didnt get the lab results back', '2024-01-23 16:14:58', 0),
(9, 47, 3, 'Hello Emily this is kelly', '2024-01-24 09:57:03', 0),
(10, 50, 3, 'hello emily this is rodrigo', '2024-01-24 10:40:23', 0);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `created_at`) VALUES
(1, 'rodrigobivarazevedo1@gmail.com', '762468c072333e7077071e3999904e39f46f45cd9baf3f736570bda6f1488581', 1705940916),
(2, 'rodrigobivarazevedo@gmail.com', 'a5454aae3639d9bb8c77d6717b482ac3c598370935401763b8822f551244b3f3', 1705941281),
(3, 'rodrigobivarazevedo@gmail.com', '8068277df523adf188fcc043d4db9d0ddd9eae1935da0595ff09bf0d6089d8f4', 1705942207),
(4, 'rodrigobivarazevedo@gmail.com', '3a7a5d043d1f2871bfa9e1caac5cc74f93aaef3657eed1417d173fba0efdbdab', 1706006387);

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `PatientID` int(11) NOT NULL,
  `FirstName` varchar(255) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `ContactNumber` varchar(20) DEFAULT NULL,
  `Email` varchar(255) NOT NULL,
  `Birthdate` date NOT NULL,
  `Gender` enum('Male','Female','Other') NOT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `DoctorID` int(11) NOT NULL,
  `Smoker` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`PatientID`, `FirstName`, `LastName`, `ContactNumber`, `Email`, `Birthdate`, `Gender`, `Address`, `DoctorID`, `Smoker`) VALUES
(4, 'Rodrigo', 'Azevedo', '964929299', 'rodrigo.azevedo@gmail.com', '1999-11-04', 'Male', '123 Main St', 1, 'no'),
(5, 'Eduardo', 'Azevedo', '964829298', 'eduardoazevedo@gmail.com', '1999-11-23', 'Male', '123 Main St', 1, 'Yes'),
(18, 'Francisca', 'Silva', '987676545', 'francisca.sliva@gmail.com', '2024-01-09', 'Female', 'pfarrkirchen', 1, 'Yes'),
(19, 'Antonio', 'Silvestre', '908765654', 'antonio.silvestre@gmail.com', '2024-01-18', 'Male', 'pfarrkirchen', 1, 'No'),
(20, 'Carlos', 'Cabrito', '987654321', 'carloscabrito@gmail.com', '2024-01-11', 'Male', 'pfarrkirchen', 1, 'Yes'),
(21, 'Pedro ', 'Aparicio', '986376365', 'pedroaparicio@gmail.com', '2024-01-09', 'Male', 'lisbon', 1, 'No'),
(22, 'Mariana', 'Castro', '908675462', 'marianacastro@gmail.com', '2024-01-16', 'Female', 'Lisbon', 1, 'Yes'),
(23, 'Matilde', 'Oliveira', '983526172', 'matildeoliveira@gmail.com', '2024-01-26', 'Female', 'lisbon', 1, 'Yes'),
(25, 'patricia', 'castro', '909354128', 'patriciacastro@gmail.com', '1998-06-16', 'Female', 'Lisbon', 1, 'No'),
(26, 'Alexandre', 'Costa', '908767654', 'alexandrecosta@gmail.com', '1976-12-04', 'Male', 'lisbon', 1, 'No'),
(28, 'Kelly', 'Khalil', '909098741', 'kelly@gmail.com', '1999-12-04', 'Female', 'pfarrkirchen', 1, 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `specialities`
--

CREATE TABLE `specialities` (
  `SpecialityID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `specialities`
--

INSERT INTO `specialities` (`SpecialityID`, `Name`) VALUES
(1, 'Cardiology'),
(2, 'Endocrinology'),
(3, 'Nephrology'),
(4, 'Gastroenterology'),
(5, 'Dermatology'),
(6, 'Dentistry'),
(7, 'Gynecology and Obstetrics'),
(8, 'Rheumatology'),
(9, 'Family Doctor');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `ContactNumber` varchar(20) NOT NULL,
  `Role` varchar(50) NOT NULL DEFAULT 'doctor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `ContactNumber`, `Role`) VALUES
(3, 'Emily Turner', '$2y$10$HGUQUL15VVzyKcLt4e684uws6RKO.8V3YOrjUw0qYpWRbj4HUYuzm', 'emilyturner123@gmail.com', '582726414', 'doctor'),
(4, 'Benjamin Hayes', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'benjamin.hayes@gmail.com', '570024859', 'doctor'),
(5, 'Sarah Mitchell', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'sarah.mitchell@gmail.com', '560213219', 'doctor'),
(6, 'Kevin Rodriguez', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'kevin.rodriguez@gmail.com', '599986832', 'doctor'),
(7, 'Amanda Foster', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'amanda.foster@gmail.com', '577957680', 'doctor'),
(8, 'Robert Hughes', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'robert.hughes@gmail.com', '564307415', 'doctor'),
(9, 'Jessica Wong', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'jessica.wong@gmail.com', '566511093', 'doctor'),
(10, 'Michael Patel', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'michael.patel@gmail.com', '519086303', 'doctor'),
(11, 'Laura Reynolds', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'laura.reynolds@gmail.com', '573668483', 'doctor'),
(12, 'Brian Lewis', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'brian.lewis@gmail.com', '526462103', 'doctor'),
(13, 'Rachel Carter', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'rachel.carter@gmail.com', '528064094', 'doctor'),
(14, 'Jonathan Kim', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'jonathan.kim@gmail.com', '527805060', 'doctor'),
(15, 'Melissa Thompson', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'melissa.thompson@gmail.com', '548060688', 'doctor'),
(16, 'Christopher Harris', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'christopher.harris@gmail.com', '510705975', 'doctor'),
(17, 'Kimberly Davis', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'kimberly.davis@gmail.com', '538606662', 'doctor'),
(18, 'Jordan Carter', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'jordan.carter@gmail.com', '581064857', 'doctor'),
(19, 'Alexandra Taylor', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'alexandra.taylor@gmail.com', '588277439', 'doctor'),
(20, 'Samuel Rodriguez', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'samuel.rodriguez@gmail.com', '516043234', 'doctor'),
(21, 'Jennifer White', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'jennifer.white@gmail.com', '533527570', 'doctor'),
(22, 'David Johnson', '$2y$10$xruSs.zKflmdS9t9qdgcx.iwLggvi4vBewJF7OQ/Pr59U6akHMxPS', 'david.johnson@gmail.com', '550292128', 'doctor'),
(47, 'Kelly', '$2y$10$j0I36.kb9He0H9PfXE2vPe8PrBdYhiYk9jBvA1LTcNcYySP.5BR0G', 'kellykhalil048@gmail.com', '909898987', 'doctor'),
(50, 'Rodrigo', '$2y$10$OPNKC0emGwSkU2sMbcKvnedMlSKxEwZMdHIsde6Y1lz17suRx6E6.', 'rodrigobivarazevedo@gmail.com', '909898765', 'doctor');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Appointments`
--
ALTER TABLE `Appointments`
  ADD PRIMARY KEY (`AppointmentID`),
  ADD KEY `PatientID` (`PatientID`),
  ADD KEY `DoctorID` (`DoctorID`),
  ADD KEY `ClinicID` (`ClinicID`);

--
-- Indexes for table `clinics`
--
ALTER TABLE `clinics`
  ADD PRIMARY KEY (`ClinicID`);

--
-- Indexes for table `clinicspecialities`
--
ALTER TABLE `clinicspecialities`
  ADD PRIMARY KEY (`ClinicID`,`SpecialityID`),
  ADD KEY `SpecialityID` (`SpecialityID`);

--
-- Indexes for table `doctorclinic`
--
ALTER TABLE `doctorclinic`
  ADD PRIMARY KEY (`DoctorID`,`ClinicID`),
  ADD KEY `ClinicID` (`ClinicID`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`DoctorID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `HealthRecords`
--
ALTER TABLE `HealthRecords`
  ADD PRIMARY KEY (`RecordID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`MessageID`),
  ADD KEY `SenderID` (`SenderID`),
  ADD KEY `ReceiverID` (`ReceiverID`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email_token` (`email`,`token`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`PatientID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `DoctorID` (`DoctorID`);

--
-- Indexes for table `specialities`
--
ALTER TABLE `specialities`
  ADD PRIMARY KEY (`SpecialityID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `ContactNumber` (`ContactNumber`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Appointments`
--
ALTER TABLE `Appointments`
  MODIFY `AppointmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `DoctorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `HealthRecords`
--
ALTER TABLE `HealthRecords`
  MODIFY `RecordID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `MessageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `PatientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `specialities`
--
ALTER TABLE `specialities`
  MODIFY `SpecialityID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Appointments`
--
ALTER TABLE `Appointments`
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`DoctorID`) REFERENCES `Doctors` (`DoctorID`),
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`ClinicID`) REFERENCES `Clinics` (`ClinicID`);

--
-- Constraints for table `clinicspecialities`
--
ALTER TABLE `clinicspecialities`
  ADD CONSTRAINT `clinicspecialities_ibfk_1` FOREIGN KEY (`ClinicID`) REFERENCES `clinics` (`ClinicID`),
  ADD CONSTRAINT `clinicspecialities_ibfk_2` FOREIGN KEY (`SpecialityID`) REFERENCES `specialities` (`SpecialityID`);

--
-- Constraints for table `doctorclinic`
--
ALTER TABLE `doctorclinic`
  ADD CONSTRAINT `doctorclinic_ibfk_1` FOREIGN KEY (`DoctorID`) REFERENCES `doctors` (`DoctorID`),
  ADD CONSTRAINT `doctorclinic_ibfk_2` FOREIGN KEY (`ClinicID`) REFERENCES `clinics` (`ClinicID`);

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`SenderID`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`ReceiverID`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_2` FOREIGN KEY (`DoctorID`) REFERENCES `doctors` (`DoctorID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
