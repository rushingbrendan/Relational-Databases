<?php
include_once("includes/inc.php");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Wally's World Vehicles For Sale</title>
    <link rel="STYLESHEET" type="text/css" href="css/BRWallyWorld.css">
</head>

<body>



</div>
    
    <form method="POST">
        <div class='vehicleDiv'>
        <h1>Vehicles available for sale</h1>
        <br>
        <?php
                
                    //echo "</table>";
                    //echo "</fieldset>";

                    $sqlCmd = "";

                    if (!empty($inputDealershipName))
                    {
                        echo @"TEST - EMPTY";
                        $sqlCmd = @"CALL spGetVehicles('$inputDealershipName');";
                    }
                    else
                    {
                        echo @"TEST -  NOTEMPTY";
                        $sqlCmd = @"CALL spGetVehiclesForSale();";
                    }

                    $result = $conn->query($sqlCmd);

                    //echo "Error: " . $sql . "<br>" . $conn->error;
                    echo "QUERY: ".$sqlCmd;
                    echo "RESULT: ". $result;

                    if ($result->num_rows > 0) {
    
                        while($row = $result->fetch_assoc()) {
    
                            $vehiclePrice = $row["wPrice"];
                            $vehiclePrice = $vehiclePrice * 1.4;
    
    
                            //start of div
                            echo @"<div class='vehicleDiv'>";    
    
                            //start of table
                            echo @"<table border='0' width='100%'>";                     
                            
                            //start of table row
                            echo @"<tr>";                       
    
                            echo @"<th>";                       //start of first table column
                            echo @"<img class='carImages' src='/wallysworld/BRWallyWorld/images/"   // image of car
                            . $row["PK_VIN_Number"] . ".png' height='150' width='275'>";
                            echo @"</th>";                      //end of first table column
    
                            echo @"<th>";                       //start of second table column                    
                            echo @"<p class='vehicleTitle' >". $row["Year"]." ".$row["MakeName"]. " ".$row["ModelName"]. "</p>";    // year + make + model
                            //echo@"<br>";
                            echo @"<p class='vehicleDetails' >VIN: ".$row["PK_VIN_Number"]. "</p>";                           // VIN: VIN NUMBER
                            echo @"<p class='vehicleDetails' >".$row["ColorName"]. "</p>";                                        // Color
                            echo @"<p class='vehicleDetails' >".$row["Km"]. " KMs"."</p>";                                     // KMs
                            echo@"<br>";
                            
                            echo @"</th>";                      //end of second table column
    
    
                            echo @"<th>";                       //start of third table column
                            echo @"<p class='vehiclePrice' >". "$" .$vehiclePrice. "</p>";                    // Price
                            //echo @"<p class='vehiclePrice' >". "$" .$row["wPrice"]. "</p>";                 // Price
    
                            //buy now Button
                            echo @"<input type='submit' class='buyNowButton' text='VIN' value='BUY NOW' />";            // Submit Button
    
                            echo @"<p class='vehicleDetails' >". "Available at:". "</p>";
                            echo @"<p class='vehicleDelear' >".$row["DealerName"]. "</p>";                    // Dealership
                            
                            echo @"<p> </p> ";
                            echo @"</th>";                      //end of third table column
                                
                            echo @"</tr>";      //end of table row
                            echo @"</table>";   //end of table
                            echo @"</div>";     //end of div
    
                            //page break
                            echo @"<br>";
                        }
                        //echo "</table>";
                        //echo "</fieldset>";
    
    
                    }


                }


                $conn->close();
                ?>

        <hr />
        <br/> <br />     
        
</form>
</body>
</html>
