-- Relational Databases Assignment #4
-- Wally's World Used Car Database
-- Brendan Rushing
-- Dec 5 2018

-- 
-- 
-- Create schema BRWally
--
DROP DATABASE IF EXISTS BRWally;

CREATE DATABASE IF NOT EXISTS BRWally;

USE BRWally;

--
-- Definition of table `customers'`
-- Stores customer information

DROP TABLE IF EXISTS `Customer`;
CREATE TABLE `Customer` (
  `PK_CustomerID` int(11) NOT NULL PRIMARY KEY auto_increment,
  `FirstName` mediumtext NOT NULL,
  `LastName` mediumtext NOT NULL,
  `PhoneNumber` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insert test data into customers table

/*!40000 ALTER TABLE `Customer` DISABLE KEYS */;
INSERT INTO `Customer` (`PK_CustomerID`, `FirstName`, `LastName`, `PhoneNumber`)
VALUES  (1, 'Wallys', 'World of Wheels Inc.,', '519-555-0000'),
				(2, 'Ringo', 'Star', '416-555-1111'),
				(3, 'Mick', 'Jagger', '519-555-2222'),
				(4, 'Eric', 'Clapton', '519-555-3333');
/*!40000 ALTER TABLE `Customer` DISABLE KEYS */;



--
-- Definition of table `dealership`
-- Stores name of used car dealership

DROP TABLE IF EXISTS `Dealership`;
CREATE TABLE `Dealership` (
  `PK_DealershipName` varchar(100)  NOT NULL PRIMARY KEY
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Definition of table `Make`
-- Stores name of car make

DROP TABLE IF EXISTS `Make`;
CREATE TABLE `Make` (
  `PK_MakeName` varchar(100)  NOT NULL PRIMARY KEY
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- Insert test data into make table

/*!40000 ALTER TABLE `Make` DISABLE KEYS */;
INSERT INTO Make
VALUES  ('Ford'),
				('Honda'),
				('Volkswagen'),
                ('Dodge');
/*!40000 ALTER TABLE `Make` DISABLE KEYS */;



--
-- Definition of table `Model`
-- Stores name of car model

DROP TABLE IF EXISTS `Model`;
CREATE TABLE `Model` (
  `PK_ModelName` varchar(100)  NOT NULL PRIMARY KEY
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insert test data into model table

/*!40000 ALTER TABLE `Model` DISABLE KEYS */;
INSERT INTO Model
VALUES  ('Focus'),
				('Civic'),
				('Jetta'),
                ('GMC'),
                ('Ram');
/*!40000 ALTER TABLE `Model` DISABLE KEYS */;

--
-- Definition of table `Color`
-- Stores name of color of cars

DROP TABLE IF EXISTS `Color`;
CREATE TABLE `Color` (
  `PK_ColorName` varchar(100)  NOT NULL PRIMARY KEY
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insert test data into color table

/*!40000 ALTER TABLE `Color` DISABLE KEYS */;
INSERT INTO Color
VALUES  ('Blue'),
				('Black'),
				('Silver'),
                ('Red'),
                ('Yellow'),
                ('Brown'),
                ('Orange'),
                ('White');
/*!40000 ALTER TABLE `Color` DISABLE KEYS */;




-- Insert test data into dealership table

/*!40000 ALTER TABLE `Dealership` DISABLE KEYS */;
INSERT INTO Dealership
VALUES  ('Sports World'),
				('Guelph Auto Mall'),
				('Waterloo');
/*!40000 ALTER TABLE `Dealership` DISABLE KEYS */;




--
-- Definition of table `vehicle`
-- Stores information about vehicle
-- Model, Make, Year, VIN Number
-- KM, Warehouse Price
-- In stock status

DROP TABLE IF EXISTS `Vehicle`;
CREATE TABLE `Vehicle` (
  `PK_VIN_Number` varchar(11) NOT NULL UNIQUE PRIMARY KEY,
  `Year` int(4) NOT NULL,
  `FK_MakeName` varchar(100)  NOT NULL,
  `FK_ModelName`varchar(100)  NOT NULL,
  `FK_ColorName` varchar(100)  NOT NULL,
  `Km` int(6) NOT NULL,
  `wPrice` float(6) NOT NULL,
  `FK_DealershipName` varchar(100)  NOT NULL,
  `InStock` mediumtext NOT NULL,
  CONSTRAINT FOREIGN KEY (`FK_DealershipName`) REFERENCES `Dealership`(`PK_DealershipName`),
  CONSTRAINT FOREIGN KEY (`FK_MakeName`) REFERENCES `Make`(`PK_MakeName`),
  CONSTRAINT FOREIGN KEY (`FK_ModelName`) REFERENCES `Model`(`PK_ModelName`),
  CONSTRAINT FOREIGN KEY (`FK_ColorName`) REFERENCES `Color`(`PK_ColorName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insert test data into vehicle table

/*!40000 ALTER TABLE `Vehicle` DISABLE KEYS */;
INSERT IGNORE INTO Vehicle
VALUES  ('58847722BRB', 2010, 'Honda', 'Civic', 'Blue', 120332, 6500, 'Sports World','Yes'),
				('26663747GTG', 2009, 'Ford', 'Focus', 'Black', 89221, 8950, 'Sports World','Yes'),
				('99277544LOL', 2012, 'Volkswagen', 'Jetta', 'Silver', 156233, 13450, 'Sports World','Yes'),
                ('27764534RTB', 2013, 'Dodge', 'Ram', 'Red', 211023, 10900, 'Waterloo','Yes'),
				('26653747GTG', 2009, 'Ford', 'Focus', 'Black', 89221, 8950, 'Sports World','Yes'),
				('99487544JUD', 2018, 'Dodge', 'Ram', 'Red', 166233, 23450, 'Waterloo','Yes'),
                ('99487541JUD', 2018, 'Dodge', 'Ram', 'Red', 176233, 33450, 'Waterloo','Yes'),
                ('99487543JUD', 2018, 'Dodge', 'Ram', 'Red', 126233, 24450, 'Waterloo','Yes'),
                ('99487523JUD', 2018, 'Dodge', 'Ram', 'White', 1233, 18420, 'Waterloo','Yes'),
                ('99487555JUD', 2018, 'Dodge', 'Ram', 'Black', 26233, 13450, 'Waterloo','Yes'),
				('53347223WTF', 2011, 'Buick', 'Regal', 'Mint', 134538, 7950, 'Waterloo','Yes');
/*!40000 ALTER TABLE `Vehicle` DISABLE KEYS */;


--
-- Definition of table `Status`
-- Stores name of status
-- 4 options PAID, CNCL, HOLD, RFND

DROP TABLE IF EXISTS `Status`;
CREATE TABLE `Status` (
  `PK_StatusName` varchar(4) NOT NULL PRIMARY KEY
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insert test data into color table

/*!40000 ALTER TABLE `Status` DISABLE KEYS */;
INSERT INTO Status
VALUES  ('PAID'),
				('CNCL'),
				('RFND'),
                ('HOLD');                
/*!40000 ALTER TABLE `Status` DISABLE KEYS */;



--
-- Definition of table `order`
-- Contains order information:
-- order id, sale price, status name, customer id

DROP TABLE IF EXISTS `Orders`;
CREATE TABLE `Orders` (
 `PK_OrderID` int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
 `OrderDate` varchar(40) NOT NULL,
   `sPrice` double NOT NULL,
	`FK_StatusName` varchar(4)  NOT NULL,
    `FK_CustomerID` int(11),
    FOREIGN KEY (`FK_CustomerID`) REFERENCES `Customer`(`PK_CustomerID`),
	FOREIGN KEY (`FK_StatusName`) REFERENCES `Status`(`PK_StatusName`)    
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- insert into orders

 /*!40000 ALTER TABLE `Orders` DISABLE KEYS */;
 INSERT INTO Orders
 VALUES (1, '2017-09-20', 17243, 'PAID', 4),				 
                 (2, '2017-10-06', 10283, 'HOLD', 3),
                 (3, '2017-10-20', 10283, 'CNCL', 3);
 /*!40000 ALTER TABLE `Orders` DISABLE KEYS */;

--
-- Definition of table `orderline`
-- Intermediary between orders and customers
-- Contains orderline ID, OrderID, Quanitty, VIN Number

DROP TABLE IF EXISTS `Orderline`;
CREATE TABLE `Orderline` (
 `PK_OrderlineID` int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
`FK_OrderID` int(11) NOT NULL,
FOREIGN KEY (`FK_OrderID`) REFERENCES `Orders` (`PK_OrderID`),
`Quantity`int (11) NOT NULL,
`FK_VIN_Number` varchar(11) NOT NULL,
FOREIGN KEY (`FK_VIN_Number`) REFERENCES `Vehicle` (`PK_VIN_Number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- insert into orderline

 /*!40000 ALTER TABLE `Orderline` DISABLE KEYS */;
 INSERT  INTO Orderline
 VALUES (1, 1, 1, '27764534RTB'),
	 			(2, 2, 1, '58847722BRB');

 /*!40000 ALTER TABLE `Orderline` DISABLE KEYS */;


-- STORED PROCEDURES ---------------------------------------------------------------------

-- Procedure Name: spGetVehiclesForSale
-- Purpose: get all vehicles that are in stock in all dealerships

-- Parameters: None
-- Returns: VIN Number, Year, Make Name, Model Name, Color Name, KM, Dealership Name, wPrice

   DROP procedure IF EXISTS `spGetVehiclesForSale`;
        DELIMITER $$
        USE `BRWally`$$
        CREATE PROCEDURE `spGetVehiclesForSale` ()
	BEGIN
	SELECT PK_VIN_Number, Year, PK_MakeName, PK_ModelName, PK_ColorName, Km, PK_DealershipName,wPrice
	FROM vehicle
    INNER JOIN Dealership ON Dealership.PK_DealershipName = Vehicle.FK_DealershipName
    INNER JOIN Make ON Make.PK_MakeName = Vehicle.FK_MakeName
    INNER JOIN Model ON Model.PK_ModelName = Vehicle.FK_ModelName
	INNER JOIN Color ON Color.PK_ColorName = Vehicle.FK_ColorName
	WHERE inStock = 'Yes';
	END
        $$
        DELIMITER ;
        
        
-- Procedure Name: spGetCustomers
-- Purpose: get all customers in database

-- Parameters: None
-- Returns: customerID, firstname, lastname, phone number

DROP procedure IF EXISTS `spGetCustomers`;
DELIMITER $$
USE `BRWally`$$
CREATE PROCEDURE `spGetCustomers` ()
BEGIN
SELECT PK_CustomerID, FirstName, LastName, PhoneNumber
FROM Customer;
END
$$
DELIMITER ;
        
        
-- Procedure Name: spAddCustomer
-- Purpose: Adds a customer to the database

-- Parameters: firstName, lastName, phoneNumber
-- Returns: fail or pass

DROP procedure IF EXISTS `spAddCustomer`;
DELIMITER $$
USE `BRWally`$$
CREATE PROCEDURE `spAddCustomer` (IN inputFirstName mediumtext, IN inputLastName mediumtext, IN inputPhoneNumber varchar(12))
BEGIN
INSERT INTO Customer(FirstName, LastName, PhoneNumber)
VALUES (inputFirstName, inputLastName, inputPhoneNumber);
END
$$
 DELIMITER ;

     
	

-- Procedure Name: spGetDealerships
-- Purpose: get all dealership names in database

-- Parameters: None
-- Returns: DealershipName

DROP procedure IF EXISTS `spGetDealerships`;
DELIMITER $$
USE `BRWally`$$
CREATE PROCEDURE `spGetDealerships` ()
BEGIN
SELECT PK_DealershipName
FROM Dealership;
END
$$
DELIMITER ;
        
        
-- Procedure Name: spGetVehicles
-- Purpose: get all vehicles that are in stock at specified dealership

-- Parameters: Dealerhip name
-- Returns: VIN Number, Year, Make Name, Model Name, Color Name, KM, Dealership Name, wPrice

DROP procedure IF EXISTS `spGetVehicles`;
DELIMITER $$
USE `BRWally`$$
CREATE PROCEDURE `spGetVehicles` (IN inputDealershipName varchar(100))
BEGIN
IF inputDealerShipName = 'ALL' THEN
	CALL spGetVehiclesForSale();
ELSE
	SELECT PK_VIN_Number, Year, PK_MakeName, PK_ModelName, PK_ColorName, Km, PK_DealershipName,wPrice
	FROM vehicle
    INNER JOIN Dealership ON Dealership.PK_DealershipName = Vehicle.FK_DealershipName
    INNER JOIN Make ON Make.PK_MakeName = Vehicle.FK_MakeName
    INNER JOIN Model ON Model.PK_ModelName = Vehicle.FK_ModelName
	INNER JOIN Color ON Color.PK_ColorName = Vehicle.FK_ColorName
	WHERE inStock = 'Yes' AND Vehicle.FK_DealershipName = inputDealershipName;
    END IF;
END
$$
DELIMITER ;
        
        
-- Procedure Name: spGetVehicle
-- Purpose: returns information about vehicle with specified VIN Number

-- Parameters: VIN Number
-- Returns: VIN Number, Year, Make Name, Model Name, Color Name, KM, Dealership Name, wPrice

DROP procedure IF EXISTS `spGetVehicle`;
DELIMITER $$
USE `BRWally`$$
CREATE PROCEDURE `spGetVehicle` (IN inputVIN_Number varchar (11))
BEGIN
SELECT PK_VIN_Number, Year, PK_MakeName, PK_ModelName, PK_ColorName, Km, PK_DealershipName,wPrice
FROM vehicle
INNER JOIN Dealership ON Dealership.PK_DealershipName = Vehicle.FK_DealershipName
INNER JOIN Make ON Make.PK_MakeName = Vehicle.FK_MakeName
INNER JOIN Model ON Model.PK_ModelName = Vehicle.FK_ModelName
INNER JOIN Color ON Color.PK_ColorName = Vehicle.FK_ColorName
WHERE inputVIN_Number = PK_VIN_Number;
END
$$
DELIMITER ;
        
        
-- Procedure Name: spAddOrder
-- Purpose: Adds an order into the database

-- Parameters: Order Date, sPrice, Status, CustomerID, VIN_Number
-- Returns: pass or fail

DROP procedure IF EXISTS `spAddOrder`;
DELIMITER $$
USE `BRWally`$$
CREATE PROCEDURE `spAddOrder` (IN inputOrderDate varchar(40), IN inputSPrice double, IN inputStatusName varchar(4), 
IN inputCustomerID int (11), IN inputVIN_Number varchar (11))
BEGIN
	
DECLARE new_OrderID varchar(11) DEFAULT 0;


-- insert into order
INSERT INTO Orders(OrderDate, sPrice, FK_StatusName, FK_CustomerID)
VALUES(inputOrderDate, inputSPrice, inputStatusName, inputCustomerID);
     
-- insert into order line
     
SELECT PK_OrderID into new_OrderID
FROM Orders
WHERE Orders.OrderDate = inputOrderDate
     AND 		Orders.sPrice = inputSPrice
     AND		Orders.FK_StatusName = inputStatusName
     AND 		Orders.FK_CustomerID = inputCustomerID;     

INSERT INTO Orderline (FK_OrderID, Quantity, FK_VIN_Number)
VALUES (new_OrderID, 1, inputVIN_Number);
     
END
$$
DELIMITER ;
        
        
-- Procedure Name: spGetOrderID
-- Purpose: get OrderID from date, sPrice, status name

-- Parameters: date, sPrice, status name
-- Returns: OrderID

DROP procedure IF EXISTS `spGetOrderID`;
DELIMITER $$
USE `BRWally`$$
CREATE PROCEDURE `spGetOrderID` (IN inputOrderDate varchar(40), IN inputSPrice double, IN inputStatusName varchar(4), 
IN inputCustomerID int (11))
BEGIN
     SELECT PK_OrderID
     FROM Orders
     WHERE Orders.OrderDate = inputOrderDate
     AND 		Orders.sPrice = inputSPrice
     AND		Orders.FK_StatusName = inputStatusName
     AND 		Orders.FK_CustomerID = inputCustomerID;     
END
$$
DELIMITER ;
        
        
-- Procedure Name: spGetCustomerID
-- Purpose: get CustomerId from firstName, lastname and phone number

-- Parameters: firstName, lastname and phone number
-- Returns: CustomerID
DROP procedure IF EXISTS `spGetCustomerID`;
DELIMITER $$
USE `BRWally`$$
CREATE PROCEDURE `spGetCustomerID` (IN inputFirstName mediumtext, 
							IN inputLastName mediumtext, IN inputPhoneNumber varchar(12))
BEGIN

SELECT PK_CustomerID 
					FROM Customer 
					WHERE Customer.FirstName = inputFirstName 
                    AND	  Customer.LastName = inputLastName
                    AND 	  Customer.PhoneNumber = inputPhoneNumber;			
END
$$
DELIMITER ;
        


-- Procedure Name: spAdd Vehicle
-- Purpose: Add vehicle to database

-- Parameters: VIN Number, Year, Make Name, Model Name, Color Name, KM, Dealership Name, wPrice
-- Returns: Pass or Fail
DROP procedure IF EXISTS `spAddVehicle`;
        DELIMITER $$
        USE `BRWally`$$
        CREATE PROCEDURE `spAddVehicle` (IN inputVIN_Number varchar(11), IN inputYear int(4), IN inputMakeName varchar(100), IN inputModelName varchar(100),
									IN inputColorName varchar(100), IN inputKm int(6), IN inputwPrice int(6), 
                                    IN inputFK_DealershipName varchar(100))
															
BEGIN

	 INSERT IGNORE INTO Make(PK_MakeName)     
     VALUES (inputMakeName);
     
     
	 INSERT IGNORE INTO Model(PK_ModelName)
     VALUES (inputModelName);
     
	 INSERT IGNORE INTO Color(PK_ColorName)
     VALUES (inputColorName);
     
     
	 INSERT INTO Vehicle(PK_VIN_Number, Year, FK_MakeName, FK_ModelName, FK_ColorName, Km, 
     wPrice, FK_DealershipName, InStock)
     VALUES (inputVIN_Number, inputYear, inputMakeName, inputModelName, inputColorName, inputKm, 
     inputwPrice, inputFK_DealershipName, 'Yes');
     
END
$$
DELIMITER ;
        
        
-- Procedure Name: spSetVehicleToNotInStock
-- Purpose: Sets a vehicle to not in stock

-- Parameters: VIN Number
-- Returns: Pass or Fail
DROP procedure IF EXISTS `spSetVehicleToNotInStock`;
DELIMITER $$
USE `BRWally`$$		
CREATE PROCEDURE `spSetVehicleToNotInStock` (IN inputVIN_Number varchar(11))
BEGIN

	 UPDATE Vehicle
     SET  Vehicle.InStock = 'No'
     WHERE Vehicle.PK_VIN_Number = inputVIN_Number
     LIMIT 99999;
	
END
$$
DELIMITER ;
        
-- Procedure Name: spSetVehicleToHold
-- Purpose: Sets a vehicle to hold status

-- Parameters: VIN Number
-- Returns: Pass or Fail  
DROP procedure IF EXISTS `spSetVehicleToHold`;
DELIMITER $$
USE `BRWally`$$		
CREATE PROCEDURE `spSetVehicleToHold` (IN inputVIN_Number varchar(11))
BEGIN
	
	 UPDATE Vehicle
     SET  Vehicle.InStock = 'Hold'
     WHERE Vehicle.PK_VIN_Number = inputVIN_Number
     LIMIT 9999;
     

END
$$ 
DELIMITER ;
        
-- Procedure Name: spGetOrderDetails
-- Purpose: Returns all order details

-- Parameters: none
-- Returns: OrderID, OrderDate, sPrice, Status, CustomerID, VIN, Quantity,
-- 				Firstname, lastname, phone number, model, make, km, year, dealership name				
DROP procedure IF EXISTS `spGetOrderDetails`;
DELIMITER $$
USE `BRWally`$$
CREATE PROCEDURE `spGetOrderDetails` ()
	BEGIN
	SELECT Orders.PK_OrderID, Orders.OrderDate, Orders.sPrice, Orders.FK_StatusName, Orders.FK_CustomerID,
					Orderline.FK_VIN_Number, Orderline.Quantity,
                    Customer.FirstName, Customer.LastName, Customer.PhoneNumber,
                    Vehicle.Year, Vehicle.FK_MakeName, Vehicle.FK_ModelName, Vehicle.FK_ColorName, Vehicle.Km, Vehicle.FK_DealershipName
	FROM Orders
    INNER JOIN Orderline ON Orderline.FK_OrderID = Orders.PK_OrderID
    INNER JOIN Vehicle ON Orderline.FK_VIN_Number = Vehicle.PK_VIN_Number
    INNER JOIN Make ON Make.PK_MakeName = Vehicle.FK_MakeName
    INNER JOIN Model ON Model.PK_ModelName = Vehicle.FK_ModelName
	INNER JOIN Color ON Color.PK_ColorName = Vehicle.FK_ColorName
    INNER JOIN Customer ON Orders.FK_CustomerID = Customer.PK_CustomerID;

END
$$
DELIMITER ;
        
        
-- Procedure Name: spChangeOrderStatus
-- Purpose:  Change order status to specified value

-- Parameters:OrderStatus, OrderID, VIN_Number
-- Returns: Pass or Fail
DROP procedure IF EXISTS `spChangeOrderStatus`;
DELIMITER $$
USE `BRWally`$$
CREATE PROCEDURE `spChangeOrderStatus` (IN inputOrderStatus varchar(4), IN inputOrderID int(11), IN inputVIN_Number varchar(11))
BEGIN
	
IF inputOrderStatus = 'CNCL' OR inputOrderStatus = 'RFND' THEN     
    
	 UPDATE Orders
     SET  Orders.FK_StatusName = inputOrderStatus
     WHERE Orders.PK_OrderID = inputOrderID
     LIMIT 99999;
     
     UPDATE Vehicle
     SET  Vehicle.InStock = 'Yes'
	 WHERE Vehicle.PK_VIN_Number = inputVIN_Number
     LIMIT 99999;
     
	UPDATE Orders
     SET  Orders.sPrice = 0
	 WHERE Orders.PK_OrderID = inputOrderID
     LIMIT 99999;
END IF;
     
IF inputOrderStatus = 'PAID' THEN     
    
	 UPDATE Orders
     SET  Orders.FK_StatusName = inputOrderStatus
     WHERE Orders.PK_OrderID = inputOrderID
     LIMIT 9999;
     
     UPDATE Vehicle
     SET  Vehicle.InStock = 'No'
	 WHERE Vehicle.PK_VIN_Number = inputVIN_Number
     LIMIT 9999;
          
END IF;
     
	
END
$$
DELIMITER ;
        
        
-- Requried default values added

        CALL spChangeOrderStatus('CNCL', 4, '58847722BRB');
        
        CALL spAddVehicle('53347223WTF', 2011,'Buick', 'Regal', 'Mint', 134538, 7950, 'Waterloo');
        CALL spAddOrder('2017-09-22', -7950, 'PAID',1, '53347223WTF' );
        
		CALL spAddVehicle('99146514OMG', 2008,'Volkswagen', 'Jetta', 'White', 199012, 2500, 'Waterloo');
        CALL spAddOrder('2017-11-02', -2500, 'PAID',2, '99146514OMG');
        
        CALL spAddOrder('2017-10-06', -7950, 'HOLD',3, '58847722BRB' );
        CALL spAddOrder('2017-10-20', -7950, 'CNCL',3, '58847722BRB' );
        
        CALL spAddOrder('2017-11-02', 21278, 'PAID',2, '99277544LOL' );
