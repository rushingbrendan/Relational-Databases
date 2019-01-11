CREATE DEFINER=`root`@`localhost` PROCEDURE `spGetCustomers`()
BEGIN
SELECT CustomerID, FirstName, LastName, PhoneNumber
FROM customer;
END