Use Company;

LOAD DATA LOCAL INFILE './data/Client.dat'
INTO TABLE Client
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
Ignore 1 LINES
(ClientID, Name, LocationName);

LOAD DATA LOCAL INFILE './data/Employee.dat'
INTO TABLE Employee
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
Ignore 1 LINES
(EmployeeID, Name);

LOAD DATA LOCAL INFILE './data/Ticket.dat'
INTO TABLE Ticket
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(TicketNum, EmployeeID, SerialNum, ClientID, DeviceType, Status);

LOAD DATA LOCAL INFILE './data/Device.dat'
INTO TABLE Device
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(SerialNum, DeviceType, ClientID, LastWorkedOn, PurchasedDate, TicketNum);

LOAD DATA LOCAL INFILE './data/Users.dat'
INTO TABLE Users
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
Ignore 1 LINES
(UserID, Username, Password, UserType);
