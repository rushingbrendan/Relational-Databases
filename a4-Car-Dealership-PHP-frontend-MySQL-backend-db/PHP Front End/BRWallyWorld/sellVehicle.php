<!DOCTYPE html>
<html>

    <!--
  FILE          : sellVehicle.php
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
<BR>
<body>




<!-- FORM FOR CAR SALE -->
<form method='POST' action='orderConfirmation.php'>
<div class='vehicleDiv'>
<pre>
<?php 
//SELECT DEALERSHIP MENU
echo @"Select Dealership&nbsp;&nbsp;<SELECT name='DealershipName'>";

//MYSQL COMMAND
//  - get all dealerships
$sqlCmd = @"CALL spGetDealerships()";

$result = $conn->query($sqlCmd);
if ($result->num_rows > 0) {
    // output data of each row    
    while($row = $result->fetch_assoc()) {

        $currentDealership = $row["PK_DealershipName"];
        echo @"<OPTION value='$currentDealership'>$currentDealership</OPTION>";
    }
} else {
    echo "0 results";
}
echo @"</SELECT>";
//END OF SELECT DEALERSHIP MENU
?>
</pre>

<!--  ADD CUSTOMER BOX-->
<br>
<h1>Customer Information</h1>
<TABLE border="0" width="80%">
            <TR>
                <!-- First Name text input -->
                <TD width="20%" align="right">First Name</TD>
                <TD width="2%">&nbsp;</TD>
                <TD width="58%" align="left"><INPUT type="text" required pattern ="^[a-zA-Z ]+$" size="40" value="" name="firstName" /></TD>
            </TR>
            <TR>
                <!-- Last Name text input -->
                <TD width="20%" align="right">Last Name</TD>
                <TD width="2%">&nbsp;</TD>
                <TD width="58%" align="left"><INPUT type="text" required pattern ="^[a-zA-Z ]+$" size="40" value="" name="lastName" /></TD>
            </TR>
            <TR>
                <!-- Phone Number text input -->
                <TD width="20%" align="right">Phone Number</TD>
                <TD width="2%">&nbsp;</TD>
                <TD width="58%" align="left"><INPUT type="text" required pattern ="^d{3}-\d{3}-\d{4}+$" size="40" value="" name="phoneNumber" /></TD>
            </TR>
        </TABLE>
<!--  END OF ADD CUSTOMER BOX-->
<br>

<?php
    //GET VIN NUMBER FROM POST
    $VIN_Number = $_POST['selectedVehicle'];

    //MYSQL QUERY
    //  - get vehicle details from vin number
    $sqlCmd = @"CALL spGetVehicle('$VIN_Number');";

    $result = $conn->query($sqlCmd);
    if ($result->num_rows > 0) 
    {
        while($row = $result->fetch_assoc()) 
        {
            //set vehicle price markup from warehosue price
            $vehiclePrice = $row["wPrice"];
            $vehiclePrice = $vehiclePrice * 1.4;

            //get car details from SQL query
            $dealershipName = $row["PK_DealershipName"];
            $date = date("Y-m-d");
            $vehicleKMs = $row["Km"];
            $vehicleMake = $row["PK_MakeName"];
            $vehicleModel = $row["PK_ModelName"];
            $vehicleYear = $row["Year"];
            $vehicleColor = $row["PK_ColorName"];

            //start of div
            echo @"<div class='vehicleDiv'>";                
            echo@"<h1>Vehicle Information</h1>";

            //start of table
            echo @"<table border='0' width='100%'>";                     
            
            //start of table row
            echo @"<tr>";                       
            echo @"<th>";      
            //start of first table column                
            // image of car
            echo @"<img class='carImages' src='/wallysworld/BRWallyWorld/images/"
            . $row["PK_VIN_Number"] . ".png' height='150' width='275'>";
            echo @"</th>";                      
            //end of first table column

            //start of second table column  
            echo @"<th>";                                         
            echo @"<p class='vehicleTitle' >". $row["Year"]." ".$row["PK_MakeName"]. " ".$row["PK_ModelName"]. "</p>";    // year + make + model
            echo @"<p class='vehicleDetails' >VIN: ".$row["PK_VIN_Number"]. "</p>";      // VIN: VIN NUMBER
            echo @"<p class='vehicleDetails' >".$row["PK_ColorName"]. "</p>";            // Color
            echo @"<p class='vehicleDetails' >".$row["Km"]. " KMs"."</p>";               // KMs
            echo@"<br>";

            //end of second table column
            echo @"</th>";                      

            //start of third table column
            echo @"<th>";                       
            echo @"<p class='vehiclePrice' >". "$" .$vehiclePrice. "</p>";   // Price        
            echo @"<p class='vehicleDetails' >". "Available at:". "</p>";
            echo @"<p class='vehicleDelear' >".$row["PK_DealershipName"]. "</p>";  // Dealership            
            echo @"<p> </p> ";
            echo @"</th>";                      
            //end of third table column
            echo @"</tr>";      //end of table row
            echo @"</table>";   //end of table
            echo @"</div>";     //end of div

            
            //HIDDEN POST INFORMATION FOR NEXT PAGE
            echo @"<INPUT type='hidden' name='VIN_Number' value='$VIN_Number' />";
            echo @"<INPUT type='hidden' name='DealershipName' value='$dealershipName' />";
            echo @"<INPUT type='hidden' name='currentDate' value='$date' />";
            echo @"<INPUT type='hidden' name='vehiclePrice' value='$vehiclePrice' />";
            echo @"<INPUT type='hidden' name='vehicleKMs' value='$vehicleKMs' />";
            echo @"<INPUT type='hidden' name='vehicleMake' value='$vehicleMake' />";
            echo @"<INPUT type='hidden' name='vehicleModel' value='$vehicleModel' />";
            echo @"<INPUT type='hidden' name='vehicleYear' value='$vehicleYear' />";
            echo @"<INPUT type='hidden' name='vehicleColor' value='$vehicleColor' />";
            echo @"<INPUT type='hidden' name='dealershipName' value='$dealershipName' />";
            echo @"<INPUT type='hidden' name='paymentType' value='PAID' />";

            //page break
            echo @"<br>";        
        }
    }
?>

<!--  TRADE-IN VEHICLE BOX-->
<h1>Trade-In Vehicle</h1>
<TABLE border="0" width="80%">
<TR>
        <!-- VIN_Number text input -->
        <TD width="20%" align="right">VIN Number</TD>
        <TD width="2%">&nbsp;</TD>
        <TD width="58%" align="left"><INPUT type="text" required pattern ="^[a-zA-Z 0123456789]+$" maxLength="11" value="" name="tradeInVIN_Number" id="tradeInVIN_NumberID"/></TD>
    </TR>
    <TR>
        <!-- Year text input -->
        <TD width="20%" align="right">Year</TD>
        <TD width="2%">&nbsp;</TD>
        <TD width="58%" align="left"><INPUT type="text" required pattern ="^[0123456789]+$" size="40" value="" name="tradeInYear" id="tradeInYearID"/></TD>
    </TR>
    <TR>
        <!-- Make text input -->
        <TD width="20%" align="right">Make</TD>
        <TD width="2%">&nbsp;</TD>
        <TD width="58%" align="left"><INPUT type="text" required pattern ="^[a-zA-Z ]+$" size="40" value="" name="tradeInMake" id="tradeInMakeID"/></TD>
    </TR>
    <TR>
        <!-- Model text input -->
        <TD width="20%" align="right">Model</TD>
        <TD width="2%">&nbsp;</TD>
        <TD width="58%" align="left"><INPUT type="text" required pattern ="^[a-zA-Z 0123456789]+$" size="40" value="" name="tradeInModel" id="tradeInModelID"/></TD>
    </TR>
    <TR>
        <!-- Color text input -->
        <TD width="20%" align="right">Color</TD>
        <TD width="2%">&nbsp;</TD>
        <TD width="58%" align="left"><INPUT type="text" required pattern ="^[a-zA-Z ]+$" size="40" value="" name="tradeInColor" id="tradeInColorID"/></TD>
    </TR>
    <TR>
        <!-- KMs text input -->
        <TD width="20%" align="right">KMs</TD>
        <TD width="2%">&nbsp;</TD>
        <TD width="58%" align="left"><INPUT type="text" required pattern = "^[0123456789]+$" size="40" value="" name="tradeInKms" id="tradeInKmsID"/></TD>
    </TR>
        <!-- Cost text input -->
        <TD width="20%" align="right">Price</TD>
        <TD width="2%">&nbsp;</TD>
        <TD width="58%" align="left"><INPUT type="text" required pattern ="^[0123456789]+$" size="40" value="" name="tradeInPrice" id="tradeInPriceID"/></TD>
    </TR>
    <TR>
    <INPUT type='hidden' name='paymentType' value='PAID' />
    <TR>
        <TD colspan="3" style="color:red;" align="center"><div id="userFeedback"></div>
    </TR>
        </TABLE>
<br>
<br>
<TR>
<TD></TD>
<TD></TD>
<!-- Submit button -->
&nbsp;&nbsp;
<TD align="left">
    <INPUT type="submit" name="newCustomer" value="Submit" />&nbsp;&nbsp;
<!-- Reset button -->
    <INPUT type="reset" name="newCustomer" value="Reset" />
</TR>
</div>
<br/> <br />     
</form>
</body>
</html>