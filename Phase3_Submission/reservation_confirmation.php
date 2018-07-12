<?php
include('lib/common.php');
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit();
}
$cookie_name = "start_date";
if(!isset($_COOKIE[$cookie_name])) {
    array_push($error_msg, "Start date is not found! Please go to make reservation first");
} else {
    $start_date = $_COOKIE[$cookie_name];
}
$cookie_name = "end_date";
if(!isset($_COOKIE[$cookie_name])) {
    array_push($error_msg, "End date is not found! Please go to make reservation first");
} else {
    $end_date = $_COOKIE[$cookie_name];
}
$cookie_name = "selected_tool_number";
if(!isset($_COOKIE[$cookie_name])) {
    array_push($error_msg, "Selected tool number is not found! Please go to make reservation first");
} else {
    $selected_tool_number = $_COOKIE[$cookie_name];
}
$query = "SELECT * FROM tool WHERE tool_number in ($selected_tool_number);";
$result = mysqli_query($db, $query);
include('lib/show_queries.php');
$total_purchase_price = 0;
if (isset($result)) {
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$total_purchase_price = $total_purchase_price+$row['purchase_price'];
	}
}
mysqli_data_seek($result, 0); // move the pointer in query result to the beginning
$total_deposit_price = $total_purchase_price*0.4;
$total_rental_price = $total_purchase_price*0.15;
$datediff = strtotime($end_date)-strtotime($start_date);
$number_days_rented = floor($datediff/(60*60*24))+1;

if (!empty($_GET['tool_number'])) {
  $cookie_name = "tool_number";
  $cookie_value = $_GET['tool_number'];
  setcookie($cookie_name, "", time() - 3600);
  setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
  header('Refresh: 0.01; ' . 'url=tool_details.php');
}
?>

<?php include("lib/header.php"); ?>
		<title>Tool4Rent Reservation Summary</title>
	</head>
	<style type="text/css">
	tr td {
	  white-space: nowrap;
	}
	input[type=text], input[type=number],select {
	  width: 190px;
	}
	table {
	  empty-cells: inherit;
	}
	</style>
	<body>
    	<div id="main_container">
            <?php include("lib/menu_customer.php"); ?>
			
			<div class="center_content">
				<div class="center_left">
					<div class="title_name"><?php print "Reservation Confirmation"; ?></div>          			
					<div class="features">
						<div class="profile_section">						
								<table>				
									<tr>
										<td>Reservation Datas: <?php print $start_date." - ". $end_date; ?></td>
									</tr>
									<tr>
										<td>Number of Days Rented: <?php print strval($number_days_rented)." Days"; ?></td>
									</tr> 
									<td>Total Deposit Price: <?php print "$".strval($total_deposit_price); ?></td> 
									<tr>
										<td>Total Rental Price: <?php print "$".strval($total_rental_price); ?></td>
									</tr>
								</table>

						</div>  
						
						<div class="profile_section">
						<div class="title_name"><?php print "Tools"; ?></div>         	
								<table>								
							            <tr>
							                <th>Tool ID</th>
							                <th>Description</th>
							                <th>Rental Price</th>
							                <th>Deposit Price</th>
							            </tr>
					                          <?php
					                            if (isset($result)) {
					                                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
					                                if ($row['power_source'] == 'Manual') {
					                                  $desc = $row['sub_option']." ".$row['sub_type'];
					                                }
					                                else {
					                                  $desc = $row['power_source']." ".$row['sub_option']." ".$row['sub_type'];
					                                }
					                                $rental_price = $row['purchase_price']*0.15;
					                                $deposit_price = $row['purchase_price']*0.4;                                
					                                print "<tr>";
					                                print "<td>{$row['tool_number']}</td>";
					                                print "<td>";
					                                print "<a href=". "\"make_reservation.php?tool_number=" . 
					                                      urlencode($row['tool_number']) . "\"". "target=\"_blank\"". ">".$desc."</a>";
					                                print "</td>";
					                                print "<td>{$rental_price}</td>";
					                                print "<td>{$deposit_price}</td>" ;             
					                                print "</tr>";
					                              }
					                            } ?> 
					                    <tr>
							                <td>Total</td>
							                <td></td>
							                <td><?php print "$".strval($total_rental_price); ?></td>
							                <th><?php print "$".strval($total_deposit_price); ?></th>
							            </tr>
								</table>																
						</div>
					 </div> 
				</div> 
                
                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 
			</div>    
            
               <?php include("lib/footer.php"); ?>
		 
		</div>
	</body>
</html>