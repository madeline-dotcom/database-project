Use Company;

LOAD DATA LOCAL INFILE './data/Client.dat'
INTO TABLE Client
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
(ClientID, LocationName);

LOAD DATA LOCAL INFILE './data/Employee.dat'
INTO TABLE Employee
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
(EmployeeID, Name);

LOAD DATA LOCAL INFILE './data/Ticket.dat'
INTO TABLE Ticket
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
(TicketNum, EmployeeID, SerialNum, ClientID, DeviceType, Status);

LOAD DATA LOCAL INFILE './data/Device.dat'
INTO TABLE Device
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
(SerialNum, ClientID, LastWorkedOn, PurchasedDate, TicketNum);