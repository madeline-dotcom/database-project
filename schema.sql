CREATE DATABASE IF NOT EXISTS Company;
USE Company;

CREATE TABLE IF NOT EXISTS Client (
    ClientID INT NOT NULL,
    Name VARCHAR(15) NOT NULL,
    Location VARCHAR(30) NOT NULL,
    CONSTRAINT ClientIDPK PRIMARY KEY (ClientID)
);

CREATE TABLE IF NOT EXISTS Employee (
    EmployeeID INT NOT NULL,
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
    CONSTRAINT TicketEmpFK FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID),
    CONSTRAINT TicketClientFK FOREIGN KEY (ClientID) REFERENCES Client(ClientID)
);

CREATE TABLE IF NOT EXISTS Device (
    SerialNum INT NOT NULL,
    DeviceType ENUM('Computer', 'Printer', 'Server') NOT NULL,
    ClientID INT NOT NULL,
    LastWorkedOn DATE,
    PurchasedDate DATE,
    TicketNum INT,
    CONSTRAINT DevicePK PRIMARY KEY (SerialNum),
    CONSTRAINT DeviceClientFK FOREIGN KEY (ClientID) REFERENCES Client(ClientID),
    CONSTRAINT DeviceTicketFK FOREIGN KEY (TicketNum) REFERENCES Ticket(TicketNum) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    UserType ENUM('Client', 'Employee', 'Admin') NOT NULL
);