/*

	Student Name: Brendan Rushing
    Student Number: 6020895
    Course: Relational Databases - Assignment #3
    Description:    SQL QUERY script using the premade northwind database.
                    Answers to each question are written below.
                    Tested in MySQL.

*/

-- QUERY SCRIPT is using northwind database

    USE Northwind;

-- 1. Displaythe CustomerID, ContactName, Country and City (in that order) intheCustomers table
	
    SELECT CustomerID, ContactName, Country, City
    FROM Customers;
    
-- 2. Display the countries that are in the Customers table in alphabetical order. Display each country name only once

    SELECT DISTINCT Country
    FROM Customers
    ORDER BY Country ASC;


-- 3. What are the CompanyName and City of all customers in Germany?

    SELECT CompanyName, City
    FROM Customers
    WHERE Country = 'Germany';

-- 4. Display the CustomerID and ContactName for each customer that does not have a Fax number.

    SELECT CustomerID, ContactName
    FROM Customers
    WHERE Fax IS NULL
        OR Fax = ' ';


-- 5. How many products are in the Products tables?

    SELECT COUNT(ProductID)
    FROM Products;


-- 6. Display the ProductID, ProductName and UnitPrice for each product in the Products table.

    SELECT ProductID, ProductName, UnitPrice
    FROM Products;

-- 7. Display the ProductName, UnitsInStock and UnitPrice (in that order) for all the products that
-- 		cost most than $20 (assuming the unit price is in dollars). Make sure that the list is in UnitPrice
-- 		descending order (most expensive at the top of the list).

    SELECT ProductName, UnitsInStock, UnitPrice
    FROM Products
    WHERE (Products.UnitPrice > 20)
    ORDER BY UnitPrice DESC;

-- 8. How many products are discontinued? (Discontinued products are indicated by a value of -1).

    SELECT COUNT(ProductID)
    FROM Products
    WHERE Discontinued = -1;

-- 9. Display the CategoryName and ProductName (in that order) for each product.

    SELECT Categories.CategoryName, Products.ProductName
    FROM Products
    INNER JOIN Categories ON Products.CategoryID = Categories.CategoryID;

-- 10. From the Employees table, combine the Title, FirstName and LastName to display a column called Salutation.

    SELECT CONCAT(Title, " ", FirstName, " ", LastName) AS Salutation
    FROM Employees;

-- 11. Display a list of TerritoryDescriptions with their corresponding RegionDescriptions.

    SELECT Territories.TerritoryDescription, Region.RegionDescription
    FROM  Territories
    INNER JOIN Region ON Territories.RegionID = Region.RegionID;
    

-- 12. For each order detail line, display the OrderID, CustomerID, ProductID and Quantity.

    SELECT Orderdetails.OrderID, Orders.CustomerID, Orderdetails.ProductID, Orderdetails.Quantity
    FROM OrderDetails
    INNER JOIN Orders ON Orders.OrderID = Orderdetails.Orderid;
    

-- 13. For each order detail line, display the OrderID, CustomerID, ProductID and Extended Price. 
    -- (The extended price is the product of UnitPrice and Quantity. Make sure the column is called
	-- “Extended Price”.)

    SELECT Orderdetails.OrderID, Orders.CustomerID, Orderdetails.ProductID, (Products.UnitPrice * Orderdetails.Quantity) AS 'Extended Price' 
    FROM OrderDetails
    INNER JOIN Products ON Products.ProductID = Orderdetails.ProductID
    INNER JOIN Orders ON Orders.OrderID = Orderdetails.OrderID;

-- 14. For each order, display the OrderID, OrderDate, CompanyName (Customers) and Employee Name 
    -- (combination of first name and last name from Employees).

    SELECT Orders.OrderID, Orders.OrderDate, Customers.CompanyName, CONCAT (Employees.FirstName, " ", Employees.LastName) AS 'Employee Name'
    FROM Orders
    INNER JOIN Customers ON Customers.CustomerID = Orders.CustomerID
    INNER JOIN Employees ON Orders.EmployeeID = employees.EmployeeID;

-- 15. Display the CustomerID and CustomerName of all Customers that have ever had an order.

	SELECT DISTINCT Orders.CustomerID, Customers.ContactName
    FROM Customers
    INNER JOIN Orders ON Orders.CustomerID = Customers.CustomerID;
    

-- 16.Display the CustomerID and CustomerName of all Customers that have never had an order.

	SELECT Customers.CustomerID, Customers.ContactName
    FROM Customers
    LEFT JOIN Orders ON Orders.CustomerID = Customers.CustomerID
    WHERE Orders.CustomerID is NULL;

-- 17.Add a new region, called ‘Europe’, to the Region table.

	INSERT INTO Region (Region.RegionID, Region.RegionDescription)
	VALUES ( 5,'Europe');


-- 18.Remove the region called ‘Europe’from the Region table.

    DELETE FROM Region
	WHERE RegionDescription='Europe' and RegionID = 5;

-- 19.For the company called ‘Ernst Handel’, change the name of the contact person to Hans Schmidt.

    UPDATE Customers
    SET ContactName = 'Hans Schmidt'
    WHERE CompanyName = 'Ernst Handel' AND CustomerID = 'ERNSH';


-- 20.Increase each UnitPrice in the Products table by $1 (assuming the unit price is in $’s).

    UPDATE Products
    SET UnitPrice = UnitPrice + 1
    WHERE ProductID > 0;


-- 21.Create a new category of products called “Discontinued”.

	INSERT INTO Categories(CategoryID, CategoryName, Description, Picture)
	VALUES (9, 'Discontinued', 'These items are discontinued', NULL);
    
-- 22.For each discontinued product, change its category to “Discontinued”.

	UPDATE Products
    SET CategoryID = 9
    WHERE Discontinued = -1 AND productID > 0;
