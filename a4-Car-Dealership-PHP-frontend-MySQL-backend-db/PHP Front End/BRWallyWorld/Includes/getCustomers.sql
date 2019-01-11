CREATE PROCEDURE `getCustomers` ()
BEGIN
SELECT CustomerID, CONCAT (FirstName, " ", LastName), PhoneNumber
FROM products;
END
