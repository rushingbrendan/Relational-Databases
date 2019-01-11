<!DOCTYPE html>
    <!--
  FILE          : orderConfirmation.php
  PROJECT       : PROG2110 - Relational Databases: Assignment #4 - Wally World
  PROGRAMMER    : Brendan Rushing
  FIRST VERSION : 2018-12-05
  DESCRIPTION   :   This project is a web application for a used car dealership called Wally's World

  - This web app uses PHP for serverside communication
  - The web page is built with HTML and Javascript

  - Wally's World is connected to a MySQL database
  
  - Customers can buy, sell and trade in cars
  - Customers can also be added to the database

  - Order details can be searched and updated.

-->
<html>

<?php
// include file for MYSQL connection settings
include_once("includes/inc.php");
?>

<!-- HEADER NAVIGATION BAR -->
<head>
    <title>Wally's World Vehicles For Sale</title>
    <!-- CSS FOR HTML -->
    <link rel="STYLESHEET" type="text/css" href="css/BRWallyWorld.css">
</head>
<div id='test2' class='headerTopBar'>
<div 
<div style="text-align: center">
<img src="wallysHeader_1.0.PNG" alt="Paris" class="center" >
<br>
<div align="center">
<a href="index.php">Home</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="index.php">Vehicles For Sale</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="sellVehicle.php">Buy Vehicle</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="orderDetails.php">Order Details</a>
<br>
<hr size="1">
</div>
</div>
</div>
<!-- END OF HEADER NAVIGATION BAR -->
<body>

<?php
/*
     Function: loop_multi
     Purpose: loop through multiple SQL queries
     Parameters: $result
     Return: none
     Source: https://stackoverflow.com/questions/10924127/two-mysqli-queries
*/
function loop_multi($result){
    //use the global variable $conn in this function
    global $conn;
    //an array to store results and return at the end
    $returned = array("result"=>array(),"error"=>array());
    //if first query doesn't return errors
      if ($result){
        //store results of first query in the $returned array
        $returned["result"][0] = mysqli_store_result($conn);
        //set a variable to loop and assign following results to the $returned array properly
        $count = 0;
        // start doing and keep trying until the while condition below is not met
        do {
            //increase the loop count by one
            $count++;
            //go to the next result
            mysqli_next_result($conn);
            //get mysqli stored result for this query
            $result = mysqli_store_result($conn);
            //if this query in the loop doesn't return errors
            if($result){
              //store results of this query in the $returned array
              $returned["result"][$count] = $result;
            //if this query in the loop returns errors
            }else{
              //store errors of this query in the $returned array
              $returned["error"][$count] = mysqli_error($conn);
            }
        }
        // stop if this is false
        while (mysqli_more_results($conn));
      }else{
        //if first query returns errors
        $returned["error"][0] = mysqli_error($conn);
      }
    //return the $returned array
    return $returned;
  }
?>
<br>
<?php

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        //create variables from post data
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $phoneNumber = $_POST['phoneNumber'];
        $dealershipName = $_POST['DealershipName'];
        $date= $_POST['currentDate'];
        $tradeInOption = $_POST['tradeinOption'];
        $tradeInVIN_Number = $_POST['tradeInVIN_Number'];
        $tradeInYear = $_POST['tradeInYear'];
        $tradeInMake = $_POST['tradeInMake'];
        $tradeInModel = $_POST['tradeInModel'];
        $tradeInColor = $_POST['tradeInColor'];
        $tradeInKms = $_POST['tradeInKms'];
        $tradeInPrice = $_POST['tradeInPrice'];
        $tradeInPaymentType = $_POST['paymentType'];
        $vehicleColor = $_POST['vehicleColor'];
        $vehicleMake = $_POST['vehicleMake'];
        $vehicleModel = $_POST['vehicleModel'];
        $vehicleKMs = $_POST['vehicleKMs'];
        $vehicleYear = $_POST['vehicleYear'];
        $vehicleVIN_Number = $_POST['VIN_Number'];
        $vehiclePrice = $_POST['vehiclePrice'];
        $status = $_POST['paymentType'];
        $saleTotal = 0;
        $saleTax = 0;
        $customerID = 0;
        $tradeInOrderID = 0;
        $purchasedOrderID = 0;

        //set tradeinoption to yes if valid vin number
        if (!empty($tradeInVIN_Number))
        {
            $tradeInOption = 'Yes';
        }

        //set date
        $date = date("Y-m-d");

        // ****************************************************************************************************************************************
        //  1. ADD CUSTOMER TO DATABASE
        //      - check if they are in it
        //      - if not, add them
        //      - then get the ID
        // ****************************************************************************************************************************************
        
        //MYSQL QUERY
        //  - get customer ID from first name, last name, phone number
        $query = @"CALL spGetCustomerID('$firstName', '$lastName', '$phoneNumber');";
        
        //execute query
        $result = mysqli_multi_query($conn, $query);
        //pass $result to the loop_multi function
        $output = loop_multi($result);

        //loop through and get customer ID
        if(isset($output['error'][2]) && $output['error'][2] !== "")
        {
        }
        else
        {
            while($row = $output['result'][0]->fetch_assoc())
            {   //set customer id if found
                $customerID = $row["PK_CustomerID"];                
                break;
            }
        }

        //IF CUSTOMER ID WAS NOT FOUND THEN ADD THE CUSTOMER
        if ($customerID == 0)
        {
            //MYSQL COMMAND
            //  - add customer to database with first name, last name, phone number
            $sqlCmd = "CALL spAddCustomer('$firstName', '$lastName', '$phoneNumber');";
                    
            if ($conn->query($sqlCmd) === TRUE) 
            {
                
            } 
            else 
            {
            }

            // MYSQL QUERY    
            //  - get CustomerID from first name, last name, phone number
            $query = @"CALL spGetCustomerID('$firstName', '$lastName', '$phoneNumber');";
            
            //execute query
            $result = mysqli_multi_query($conn, $query);
            //pass $result to the loop_multi function
            $output = loop_multi($result);

            //loop through and get customer ID
            if(isset($output['error'][2]) && $output['error'][2] !== "")
            {
                //echo $output['error'][2];
            }
            else
            {
                while($row = $output['result'][0]->fetch_assoc())
                {   //set customer ID
                    $customerID = $row["PK_CustomerID"];                    
                    break;
                }
            }
        }
        //****************************************************************************************************************************************

        //IF THERE IS VEHICLE TO PUCHASE
        if (!empty($vehicleVIN_Number))
        {

        // ****************************************************************************************************************************************
        //  2. ADD PURCHASED VEHICLE ORDER TO DATABASE
        //      - add order
        //      - then get the ID
        // ****************************************************************************************************************************************
        
        //MYSQL COMMAND
        // - add order to database with: date, vehicleprice, tradeinpaymenttype, customerid, vehicleVIN_Number
       $sqlCmd = @"CALL spAddOrder('$date','$vehiclePrice', '$tradeInPaymentType', '$customerID', '$vehicleVIN_Number');";
                        
       if ($conn->query($sqlCmd) === TRUE) 
       {     
       } 
       else 
       {
       }
       //MYSQL QUERY
       //   - Get OrderID with date,vehicleprice, tradeinpaymenttype, customerid
       $query = @"CALL spGetOrderID('$date','$vehiclePrice', '$tradeInPaymentType', '$customerID');";
              
       //execute query
       $result = mysqli_multi_query($conn, $query);
       //pass $result to the loop_multi function
       $output = loop_multi($result);
       //loop through and get customer ID
       if(isset($output['error'][2]) && $output['error'][2] !== "")
       {           
       }
       else
       {
           while($row = $output['result'][0]->fetch_assoc())
           {   //get orderid
               $purchasedOrderID = $row["PK_OrderID"];               
               break;
           }
       }
       //****************************************************************************************************************************************

       // ****************************************************************************************************************************************
       //  3. SET PURCHASED VEHICLE TO NOT IN STOCK OR ON HOLD
       //      
       // ****************************************************************************************************************************************

       //if payment type is HOLD
       if ($_POST['paymentType'] == 'HOLD')
       {
            //MYSQL COMMAND
            // - Set vehicle to hold with vin number
           $sqlCmd = @"CALL spSetVehicleToHold('$vehicleVIN_Number');";
               
           if ($conn->query($sqlCmd) === TRUE) 
           {               
           } 
           else 
           {               
           }
       }
       else
       {
            //MYSQL COMMAND
            //   - Set vehicle to not in stock
           $sqlCmd = @"CALL spSetVehicleToNotInStock('$vehicleVIN_Number');";
               
           if ($conn->query($sqlCmd) === TRUE) 
           {               
           } 
           else 
           {
           }
       }

       // ****************************************************************************************************************************************

        }

        // ****************************************************************************************************************************************
        //  3. ADD TRADE IN VEHICLE TO DATABASE AND THEN ADD ORDERLINE
        //      - add order
        //      - then get the ID
        // ****************************************************************************************************************************************

        //set tradein price to negative as per requirements
        $newTradeInPrice = $tradeInPrice * -1;

        //make sure that trade in vehicle is valid
        if (($tradeInOption == "Yes") &&($tradeInVIN_Number != "DEFAULTVIN1"))
        {            
            //MYSQL COMMAND     
            //  ADD VEHICLE TO VEHICLE TABLE
            $sqlCmd = @"CALL spAddVehicle('$tradeInVIN_Number','$tradeInYear', '$tradeInMake', '$tradeInModel',
                                        '$tradeInColor', '$tradeInKms', '$tradeInPrice', '$dealershipName');";
            
            if ($conn->query($sqlCmd) === TRUE) 
            {                
            } 
            else 
            {                
            }

            //MYSQL COMMAND
            //ADD ORDER TO ORDERLINE            
            $sqlCmd = @"CALL spAddOrder('$date','$newTradeInPrice', '$tradeInPaymentType', '$customerID', '$tradeInVIN_Number');";

            if ($conn->query($sqlCmd) === TRUE) 
            {                
            } 
            else 
            {                
            }
            //MYSQL COMMAND
            //GET ORDER ID            
            $query = @"CALL spGetOrderID('$date','$newTradeInPrice', '$tradeInPaymentType', '$customerID');";
                        
            //execute query
            $result = mysqli_multi_query($conn, $query);
            //pass $result to the loop_multi function
            $output = loop_multi($result);

            //loop through and get customer ID
            if(isset($output['error'][2]) && $output['error'][2] !== "")
            {                
            }
            else
            {
                while($row = $output['result'][0]->fetch_assoc())
                {        
                    //get order ID
                    $tradeInOrderID = $row["PK_OrderID"];                    
                    break;
                }
            }
        }
        
        //****************************************************************************************************************************************

        // HTML CODE for order confirmation
        echo @"<div class='orderConfirmationDiv' >";    
        echo "<br>";
        echo "<h1>Order Completed</h1>";
        echo "<br>";        
        echo "Thank you for choosing Wally's World of Wheels at $DealershipName for your quality used vehicle";
        echo "<br>";
        echo "<br>";
        echo "Date: $date <br>";
        echo "Customer: $firstName $lastName <br>";
        echo "Phone: $phoneNumber <br><br>";

        //add vehicle purchased details if there is one
        if (!empty($vehicleVIN_Number))
        {
            echo "VEHICLE PURCHASED<br>";
            echo "Order ID: $purchasedOrderID - $tradeInPaymentType<br><br>";
            echo "$vehicleYear $vehicleMake $vehicleModel, $vehicleColor<br>";
            echo "VIN: $vehicleVIN_Number <br>";
            echo "KMs: $vehicleKMs <br><br>";    
            echo "Purchase Price: $$vehiclePrice<br><br>";
        }
        //add trade in vehicle if there is one
        if (($tradeInOption == "Yes") &&($tradeInVIN_Number != "DEFAULTVIN1"))
        {
            echo "VEHICLE TRADED-IN<br>";
            echo "Order ID: $tradeInOrderID - $tradeInPaymentType<br><br>";
            echo "$tradeInYear $tradeInMake $tradeInModel, $tradeInColor<br>";
            echo "VIN: $tradeInVIN_Number <br>";
            echo "KMs: $tradeInKms <br><br>";
            echo "Trade In Price: $$tradeInPrice<br><br>";    
        }
        else
        {
            $tradeInPrice = 0;
        }

        //calculate sub total
        $subTotal = $vehiclePrice - $tradeInPrice;
        echo "Subtotal = $$subTotal<br>";

        //calculate sales tax
        $saleTax = $vehiclePrice * 0.13;

        //calcaulate sale total
        $saleTotal = $subTotal + $saleTax;

        echo "HST (13%): $$saleTax<br>";
        echo "Sale Total = $$saleTotal<br>";
        echo "<br>";
        echo @"</div>";
    }
?>
</body>
</html>