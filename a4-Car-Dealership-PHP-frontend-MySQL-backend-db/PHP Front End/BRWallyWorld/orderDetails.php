<!DOCTYPE html>

<!--
  FILE          : orderDetails.php
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
</head>
<h1>Order Details</h1>
<br>
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

<?php
    //get variables from POST
    //  - dealership selection if selected
    //  - order id if searched
    //  - order change state if pressed
    //  - selected VIN number
    $selectedDealership = $_POST['PK_DealershipName'];
    $orderIDSearch = $_POST['orderIDSearch'];
    $selectedOrderID = $_POST['orderSelection'];
    $orderChangeStatus = $_POST['changeOrderState'];
    $selectedVIN_Number = $_POST['selectedVIN_Number'];

    //SHOW ALL ORDERS IF DEALERSHIP OR ORDERID IS NOT SELECTED
    if ((!empty($orderChangeStatus)) &&(!empty($selectedOrderID)))
    {
        //IF ORDER CHANGE STATUS IS
        // RFND, CNCL, PAID, HOLD
        if (($orderChangeStatus == 'RFND') || ($orderChangeStatus == 'CNCL') || ($orderChangeStatus == 'PAID')|| ($orderChangeStatus == 'HOLD'))
        {
        
        //div to show response from order details update
        echo "<div class='vehicleDiv'>";            
        
        //run sql insert command to change order status      
        $sqlCmd = "CALL spChangeOrderStatus('$orderChangeStatus', '$selectedOrderID', '$selectedVIN_Number');";
                
        if ($conn->query($sqlCmd) === TRUE) 
        {
            //if order added to db then show response for user
            echo"<br><p class='vehicleTitle'> Order Successfully Updated </p>";
            echo "<br>OrderID: $selectedOrderID has been set to $orderChangeStatus<br><br>  ";
        } 
        else 
        {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        echo "</div>";
        }     
    }

    //MYSQL QUERIES
    // 1. get order details
    // 2. get dealership list
    $query  = "CALL spGetOrderDetails();";        
    $query .= "CALL spGetDealerships();";
    
    //execute query
    $result = mysqli_multi_query($conn, $query);
    //pass $result to the loop_multi function
    $output = loop_multi($result);    

    //div to show dealership selection menu
    echo @"<div class='vehicleDiv'>";
    echo @"<h1 class='vehicleTitle'>Search Orders</h1>";
    echo @"<form method='post'>";
    echo @"Order ID: <INPUT type='text' pattern ='^[0123456789]+$' size='40' value='' name='orderIDSearch' /></TD>";
    echo @"<SELECT name='PK_DealershipName'>";
    echo @"D<OPTION value=''>ALL DEALERSHIPS</OPTION>";
        
    if(isset($output['error'][2]) && $output['error'][2] !== "")
    {
        echo $output['error'][2];
    }
    else
    {
        while($row = $output['result'][2]->fetch_assoc())        
        {        
            $currentDealership = $row["PK_DealershipName"];
            echo @"<OPTION value='$currentDealership'>$currentDealership</OPTION>";
        }
    }

    echo @"</SELECT>";
    echo @"<INPUT type='submit' name='dealershipSearch' value='Submit' />&nbsp;&nbsp;";
    echo @"</form>";
    echo @"<br>";
    echo @"</div>";
    echo @"<br>";
    // end of dealership menu

    //MYSQL COMMAND
    // get order details
    $sqlCmd = @"CALL spGetOrderDetails()";

    $result = $conn->query($sqlCmd);
    
    //show table of order details
    if ($result->num_rows > 0) {
        // output data of each row
        echo(" <fieldset><legend>Order Details</legend>");
        echo @"<form method='post'>";
        echo "<table border='1' width='100%'>";
        echo @"<tr>
                    <th>Select</th>
                    <th>Order Date</th>
                    <th>Order ID</th>
                    <th>Order Status</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone Number</th>
                    <th>Sale Price</th>
                    <th>Quantity</th>
                    <th>VIN Number</th>
                    <th>Year</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Color</th>
                    <th>KMs</th>
                    <th>Dealership Name</th>
                    </tr>";

        while($row = $result->fetch_assoc()) {

            $selectedOrderID = $row["PK_OrderID"];   

            //FULL TABLE OF ALL ORDER DETAILS FROM ALL DEALERSHIPS
            if ((empty($selectedDealership)) && (empty($orderIDSearch)))
            {
            echo @"<tr>
                        <th>" ."<INPUT type='radio' name='orderSelection' required value='$selectedOrderID' />". "</th>
                        <th>". $row["OrderDate"]. "</th>
                        <th>". $row["PK_OrderID"]. "</th>
                        <th>". $row["FK_StatusName"]. "</th>
                        <th>". $row["FirstName"]. "</th>
                        <th>". $row["LastName"]. "</th>
                        <th>". $row["PhoneNumber"]. "</th>
                        <th>". $row["sPrice"]. "</th>
                        <th>". $row["Quantity"]. "</th>
                        <th>". $row["FK_VIN_Number"]. "</th>
                        <th>". $row["Year"]. "</th>
                        <th>". $row["FK_MakeName"]. "</th>
                        <th>". $row["FK_ModelName"]. "</th>
                        <th>". $row["FK_ColorName"]. "</th>
                        <th>". $row["Km"]. "</th>
                        <th>". $row["FK_DealershipName"]. "</th>
                        </tr>";
                        $selectedVIN_Number = $row["FK_VIN_Number"];
                        echo @"<INPUT type='hidden' name='selectedVIN_Number' value='$selectedVIN_Number' />";
            }
            // IF ORDER ID & DEALERSHIP ARE SEARCHED THEN ONLY DISPLAY RESULTS
            else if ((!empty($selectedDealership)) && (!empty($orderIDSearch)) 
            && ($row["PK_OrderID"] == $orderIDSearch) && ($row["FK_DealershipName"] == $selectedDealership))
            {
            echo @"<tr>
                        <th>" ."<INPUT type='radio' name='orderSelection' required value='$selectedOrderID' />". "</th>
                        <th>". $row["OrderDate"]. "</th>
                        <th>". $row["PK_OrderID"]. "</th>
                        <th>". $row["FK_StatusName"]. "</th>
                        <th>". $row["FirstName"]. "</th>
                        <th>". $row["LastName"]. "</th>
                        <th>". $row["PhoneNumber"]. "</th>
                        <th>". $row["sPrice"]. "</th>
                        <th>". $row["Quantity"]. "</th>
                        <th>". $row["FK_VIN_Number"]. "</th>
                        <th>". $row["Year"]. "</th>
                        <th>". $row["FK_MakeName"]. "</th>
                        <th>". $row["FK_ModelName"]. "</th>
                        <th>". $row["FK_ColorName"]. "</th>
                        <th>". $row["Km"]. "</th>
                        <th>". $row["FK_DealershipName"]. "</th>
                        </tr>";
                        $selectedVIN_Number = $row["FK_VIN_Number"];
                        echo @"<INPUT type='hidden' name='selectedVIN_Number' value='$selectedVIN_Number' />";
            }
            // IF ORDER ID IS SEARCHED THEN ONLY DISPLAY RESULTS
            else if ((empty($selectedDealership)) && (!empty($orderIDSearch))
            && ($row["PK_OrderID"] == $orderIDSearch))
            {
            echo @"<tr>
                        <th>" ."<INPUT type='radio' name='orderSelection' required value='$selectedOrderID' />". "</th>
                        <th>". $row["OrderDate"]. "</th>
                        <th>". $row["PK_OrderID"]. "</th>
                        <th>". $row["FK_StatusName"]. "</th>
                        <th>". $row["FirstName"]. "</th>
                        <th>". $row["LastName"]. "</th>
                        <th>". $row["PhoneNumber"]. "</th>
                        <th>". $row["sPrice"]. "</th>
                        <th>". $row["Quantity"]. "</th>
                        <th>". $row["FK_VIN_Number"]. "</th>
                        <th>". $row["Year"]. "</th>
                        <th>". $row["FK_MakeName"]. "</th>
                        <th>". $row["FK_ModelName"]. "</th>
                        <th>". $row["FK_ColorName"]. "</th>
                        <th>". $row["Km"]. "</th>
                        <th>". $row["FK_DealershipName"]. "</th>
                        </tr>";
                        $selectedVIN_Number = $row["FK_VIN_Number"];
                        echo @"<INPUT type='hidden' name='selectedVIN_Number' value='$selectedVIN_Number' />";
            }
            // IF DEALERSHIP IS SEARCHED THEN ONLY DISPLAY RESULTS
            else if ((!empty($selectedDealership)) && (empty($orderIDSearch) && ($row["FK_DealershipName"] == $selectedDealership)))
            {
            echo @"<tr>
                        <th>" ."<INPUT type='radio' name='orderSelection' required value='$selectedOrderID' />". "</th>
                        <th>". $row["OrderDate"]. "</th>
                        <th>". $row["PK_OrderID"]. "</th>
                        <th>". $row["FK_StatusName"]. "</th>
                        <th>". $row["FirstName"]. "</th>
                        <th>". $row["LastName"]. "</th>
                        <th>". $row["PhoneNumber"]. "</th>
                        <th>". $row["sPrice"]. "</th>
                        <th>". $row["Quantity"]. "</th>
                        <th>". $row["FK_VIN_Number"]. "</th>
                        <th>". $row["Year"]. "</th>
                        <th>". $row["FK_MakeName"]. "</th>
                        <th>". $row["FK_ModelName"]. "</th>
                        <th>". $row["FK_ColorName"]. "</th>
                        <th>". $row["Km"]. "</th>
                        <th>". $row["FK_DealershipName"]. "</th>
                        </tr>";
                        $selectedVIN_Number = $row["FK_VIN_Number"];
                        echo @"<INPUT type='hidden' name='selectedVIN_Number' value='$selectedVIN_Number' />";
            }
        }
        echo "</table>";
        echo "<br>";
        echo @"Change Order State: &nbsp;&nbsp;<SELECT name='changeOrderState'>";            
        echo @"<OPTION value='PAID'>PAID</OPTION>";
        echo @"<OPTION value='HOLD'>HOLD</OPTION>";
        echo @"<OPTION value='RFND'>RFND</OPTION>";
        echo @"<OPTION value='CNCL'>CNCL</OPTION>";
        echo @"</SELECT>&nbsp;&nbsp;&nbsp;&nbsp;";
        echo @"<INPUT type='submit' name='orderIDSearchButton' value='Submit' />&nbsp;&nbsp;";
        echo "</form>";
        echo "</fieldset>";
    } else {
        echo "0 results";
    }

    $conn->close();
?>
</div>
<BR>
<body class="orderDetailsBody">
<br/> <br />     
</body>
</html>