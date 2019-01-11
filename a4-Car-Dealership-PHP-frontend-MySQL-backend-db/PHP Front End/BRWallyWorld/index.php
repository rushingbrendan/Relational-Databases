<!DOCTYPE html>

<!--
  FILE          : index.php
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
<!-- END OF HEADER NAVIGATION BAR -->

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


<!--  ADD CUSTOMER BOX-->
<div class='vehicleDiv'>
<h1>Add Customer</h1>
<form method="post">
<TABLE border="0" width="80%">
            <TR>
                <!-- First Name text input -->
                <TD width="40%" align="right">First Name</TD>
                <TD width="2%">&nbsp;</TD>
                <TD width="38%" align="left"><INPUT type="text" required pattern ="^[a-zA-Z ]+$" size="40" value="" name="firstName" /></TD>
            </TR>
            <TR>
                <!-- Last Name text input -->
                <TD width="40%" align="right">Last Name</TD>
                <TD width="2%">&nbsp;</TD>
                <TD width="38%" align="left"><INPUT type="text" required pattern ="^[a-zA-Z ]+$" size="40" value="" name="lastName" /></TD>
            </TR>
            <TR>
                <!-- Phone Number text input -->
                <TD width="40%" align="right">Phone Number</TD>
                <TD width="2%">&nbsp;</TD>
                <TD width="38%" align="left"><INPUT type="text" required pattern ="^d{3}-\d{3}-\d{4}+$" size="40" value="" name="phoneNumber" /></TD>
            </TR>
            <TR>
                <TD></TD>
                <TD></TD>
                <!-- Submit button -->
                <TD align="left">
                    <INPUT type="submit" name="newCustomer" value="Submit" />&nbsp;&nbsp;
                <!-- Reset button -->
                    <INPUT type="reset" name="newCustomer" value="Reset" />
            </TR>
        </TABLE>
</form>
<!--  END OF ADD CUSTOMER BOX-->

<?php
    // if add new customer is pressed
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        //create variables from post data
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $phoneNumber = $_POST['phoneNumber'];

        if (!empty($phoneNumber))
        {
            //run sql insert command to add customer to db
            $sqlCmd = "CALL spAddCustomer('$firstName', '$lastName', '$phoneNumber');";
                    
            if ($conn->query($sqlCmd) === TRUE) 
            {
                echo "Customer has been added.";
            } 
            else 
            {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }            
        }
    }
?>

</div>



<?php
    // if add new customer is pressed
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        //create variables from post data
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $phoneNumber = $_POST['phoneNumber'];

        if (!empty($phoneNumber))
        {

            //run sql insert command to add customer to db
            $sqlCmd = "CALL spAddCustomer('$firstName', '$lastName', '$phoneNumber');";
                    
            if ($conn->query($sqlCmd) === TRUE) 
            {
                //echo "Customer has been added.";
            } 
            else 
            {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            //$conn->close();
        }

    }
?>
</div>
<BR>
<body>
<?php
    //get dealership selection from post
    $SelectedDealership = $_POST['PK_DealershipName'];

    //SQL QUERIES
    //  1. Get vehicles for sale
    //  2. Get list of dealerships
    $query  = "CALL spGetVehiclesForSale();";
    $query .= "CALL spGetDealerships();";
    
    //execute query
    $result = mysqli_multi_query($conn, $query);
    //pass $result to the loop_multi function
    $output = loop_multi($result);    

    //div for dealership selection
    // - selection menu
    echo @"<div class='vehicleDiv'>";
    echo @"<h1>Select Dealership</h1>";
    echo @"<form method='post'>";
    echo @"<SELECT name='PK_DealershipName'>";
    echo @"<OPTION value=''>ALL DEALERSHIPS</OPTION>";
        
    if(isset($output['error'][2]) && $output['error'][2] !== "")
    {
        echo $output['error'][2];
    }
    else
    {
        //GET DEALERSHIP NAMES FROM QUERY
        while($row = $output['result'][2]->fetch_assoc())        
        {        
            //ADD DEALERSHIP NAMES TO OPTION MENU
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
    // END OF DEALERSHIP SELECTION DIV
        
        
    
    if(isset($output['error'][2]) && $output['error'][2] !== "")
    {
        echo $output['error'][2];
    }
    else
    {   //get all vehicles from vehicle list
        while($row = $output['result'][0]->fetch_assoc())
        {            
            if ((empty($SelectedDealership)) || ($row["PK_DealershipName"] == $SelectedDealership))
            {
                
            $vehiclePrice = $row["wPrice"];             //get vehicle price
            $vehiclePrice = $vehiclePrice * 1.4;        //add markup to warehouse price for vehicles

            $VIN_NUMBER = $row["PK_VIN_Number"];        // get vin number
            $vehicleImage = '/wallysworldtest/BRWallyWorld/images/';    //get vehicle image from vin number
            $defaultImage = '/wallysworldtest/BRWallyWorld/images/default.png';
            $vehicleImage .= $row["PK_VIN_Number"];
            $vehicleImage .= '.png';

            $imageLocation = "MacOS";
            $imageLocation .= getcwd();
            $imageLocation .= $vehicleImage;

        
            //start of vehicle div
            echo @"<div class='vehicleDiv'>";    

            //start of table
            echo @"<table border='0' width='100%'>";                     
            
            //start of table row
            echo @"<tr>";                       

            //start of first table column          
            echo @"<th>";         
        
            //add image
            //show default image if not available
            echo @"<img src='$vehicleImage' onerror=\"this.src='$defaultImage';\" alt=\"Missing Image\" height='150' width='275'/>";

            //end of first table column
            echo @"</th>";     

            //start of second table column 
            echo @"<th>";                                          
            echo @"<p class='vehicleTitle' >". $row["Year"]." ".$row["PK_MakeName"]. " ".$row["PK_ModelName"]. "</p>";    // year + make + model            
            echo @"<p class='vehicleDetails' >VIN: ".$row["PK_VIN_Number"]. "</p>";                           // VIN: VIN NUMBER
            echo @"<p class='vehicleDetails' >".$row["PK_ColorName"]. "</p>";                                        // Color
            echo @"<p class='vehicleDetails' >".$row["Km"]. " KMs"."</p>";                                     // KMs
            echo@"<br>";         
            echo @"</th>";                      //end of second table column

            echo @"<th>";                       //start of third table column
            echo @"<p class='vehiclePrice' >". "$" .$vehiclePrice. "</p>";                    // Price
            
            //buy now Button
            echo @"<form method='POST' action='checkout.php'>";
            echo @"<input type='hidden' name='selectedVehicle' value='$VIN_NUMBER' />";
            echo @"<input type='submit' class='buyNowButton' text='$VIN_NUMBER' value='BUY NOW' />";            // Submit Button
            echo @"</form>";
            echo @"<p class='vehicleDetails' >". "Available at:". "</p>";
            echo @"<p class='vehicleDelear' >".$row["PK_DealershipName"]. "</p>";                    // Dealership
            
            echo @"<p> </p> ";
            echo @"</th>";                      //end of third table column
                
            echo @"</tr>";      //end of table row
            echo @"</table>";   //end of table
            echo @"</div>";     //end of div

            //page break
            echo @"<br>";
            }
            $conn->close();
    }
}

?>
<br/> <br />     
</body>
</html>