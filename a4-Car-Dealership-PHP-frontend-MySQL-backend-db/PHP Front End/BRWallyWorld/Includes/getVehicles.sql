CREATE DEFINER=`root`@`localhost` PROCEDURE `spGetVehicles`()
BEGIN
SELECT VIN_Number, Year, Make, Model, Color, Km, wPrice, inStock
FROM vehicle;
END



