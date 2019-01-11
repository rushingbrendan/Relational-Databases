CREATE DEFINER=`root`@`localhost` PROCEDURE `spGetVehiclesForSale`()
BEGIN
SELECT VIN_Number, Year, Make, Model, Color, Km, wPrice
FROM vehicle
WHERE inStock = 'Yes';
END