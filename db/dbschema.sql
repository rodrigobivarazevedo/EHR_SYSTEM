CREATE TABLE HealthRecords (
    record_id INT PRIMARY KEY,
    patient_id INT,
    doctor_id INT,
    date_recorded DATE NOT NULL,
    diagnosis TEXT,
    medications TEXT, -- Reference to the PrescriptionID from MedicationPrescriptions table
    procedures TEXT,
    comments TEXT,
    
);

-- Patients table
CREATE TABLE patients (
    PatientID INT PRIMARY KEY AUTO_INCREMENT,
    DoctorID INT,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    Email VARCHAR(255) NOT NULL UNIQUE,
    Birthdate DATE NOT NULL,
    Gender ENUM('Male', 'Female', 'Other') NOT NULL,
    Address VARCHAR(255),
    ContactNumber VARCHAR(20),
    PRIMARY KEY (PatientID, DoctorID),
    FOREIGN KEY (DoctorID) REFERENCES Doctors(DoctorID)
    -- Add other relevant patient information
);


CREATE TABLE Users (
    UserID INT PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(255) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL UNIQUE,
    ContactNumber VARCHAR(20) NOT NULL UNIQUE,
    Role VARCHAR(50) NOT NULL DEFAULT 'user' -- Default role is set to 'user'
);


-- Doctors table
CREATE TABLE Doctors (
    DoctorID INT PRIMARY KEY AUTO_INCREMENT,
    UserID INT,
    FirstName VARCHAR(255) NOT NULL,
    LastName VARCHAR(255) NOT NULL,
    Speciality VARCHAR(255) NOT NULL,
    ContactNumber VARCHAR(20),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);


-- Clinics table
CREATE TABLE Clinics (
    ClinicID INT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Location VARCHAR(255) NOT NULL
);

CREATE TABLE ClinicSpecialities (
    ClinicID INT,
    SpecialityID INT,
    PRIMARY KEY (ClinicID, SpecialityID),
    FOREIGN KEY (ClinicID) REFERENCES Clinics(ClinicID),
    FOREIGN KEY (SpecialityID) REFERENCES Specialities(SpecialityID)
);

CREATE TABLE Specialities (
    SpecialityID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(255) NOT NULL
);


-- Create a new table for the many-to-many relationship between doctors and clinics
CREATE TABLE DoctorClinic (
    DoctorID INT,
    ClinicID INT,
    PRIMARY KEY (DoctorID, ClinicID),
    FOREIGN KEY (DoctorID) REFERENCES Doctors(DoctorID),
    FOREIGN KEY (ClinicID) REFERENCES Clinics(ClinicID)
);


CREATE TABLE Messages (
    MessageID INT AUTO_INCREMENT PRIMARY KEY,
    SenderID INT,
    ReceiverID INT,
    Content TEXT,
    Timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    IsRead BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (SenderID) REFERENCES Users(UserID),
    FOREIGN KEY (ReceiverID) REFERENCES Users(UserID)
);