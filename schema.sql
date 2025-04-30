CREATE DATABASE IF NOT EXISTS Company;
USE Company;

CREATE TABLE IF NOT EXISTS Client (
    ClientID INT UNIQUE NOT NULL,
    Name VARCHAR(15) NOT NULL,
    Location VARCHAR(30) NOT NULL,
    CONSTRAINT ClientIDPK PRIMARY KEY (ClientID)
);

CREATE TABLE IF NOT EXISTS Employee (
    EmployeeID INT UNIQUE NOT NULL,
    Name VARCHAR(30) NOT NULL,
    CONSTRAINT EmpIDPK PRIMARY KEY (EmployeeID)
);

CREATE TABLE IF NOT EXISTS Ticket (
    TicketNum INT NOT NULL,
    EmployeeID INT,
    SerialNum INT NOT NULL,
    ClientID INT NOT NULL,
    DeviceType ENUM('Computer', 'Printer', 'Server') NOT NULL,
    Status ENUM('Open', 'In Progress', 'Closed') NOT NULL,
    CONSTRAINT TicketNumPK PRIMARY KEY (TicketNum),
    CONSTRAINT TicketEmpFK FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID) ON DELETE SET NULL,
    CONSTRAINT TicketClientFK FOREIGN KEY (ClientID) REFERENCES Client(ClientID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Device (
    SerialNum INT NOT NULL,
    DeviceType ENUM('Computer', 'Printer', 'Server') NOT NULL,
    ClientID INT NOT NULL,
    LastWorkedOn DATE,
    PurchasedDate DATE,
    TicketNum INT,
    CONSTRAINT DevicePK PRIMARY KEY (SerialNum),
    CONSTRAINT DeviceClientFK FOREIGN KEY (ClientID) REFERENCES Client(ClientID) ON DELETE CASCADE,
    CONSTRAINT DeviceTicketFK FOREIGN KEY (TicketNum) REFERENCES Ticket(TicketNum) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS Users (
    Username VARCHAR(50) UNIQUE NOT NULL PRIMARY KEY,
    Password VARCHAR(255) NOT NULL,
    UserID INT NOT NULL,
    UserType ENUM('Client', 'Employee', 'Admin') NOT NULL
);

DELIMITER $$

CREATE TRIGGER CheckUserIDBeforeInsert
BEFORE INSERT ON Users
FOR EACH ROW
BEGIN
    IF NEW.UserType = 'Client' THEN
        IF NOT EXISTS (SELECT 1 FROM Client WHERE ClientID = NEW.UserID) THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Invalid ClientID for UserType Client';
        END IF;
    ELSEIF NEW.UserType = 'Employee' THEN
        IF NOT EXISTS (SELECT 1 FROM Employee WHERE EmployeeID = NEW.UserID) THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Invalid EmployeeID for UserType Employee';
        END IF;
    END IF;
END$$

CREATE TRIGGER CheckUserIDBeforeUpdate
BEFORE UPDATE ON Users
FOR EACH ROW
BEGIN
    IF NEW.UserType = 'Client' THEN
        IF NOT EXISTS (SELECT 1 FROM Client WHERE ClientID = NEW.UserID) THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Invalid ClientID for UserType Client';
        END IF;
    ELSEIF NEW.UserType = 'Employee' THEN
        IF NOT EXISTS (SELECT 1 FROM Employee WHERE EmployeeID = NEW.UserID) THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Invalid EmployeeID for UserType Employee';
        END IF;
    END IF;
END$$

DELIMITER ;
