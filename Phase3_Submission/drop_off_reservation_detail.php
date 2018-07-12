<?php

include('lib/common.php');
// written by GTusername4


?>

<?php include("lib/header.php"); ?>
<?php
    $id = $_GET[reservation_id];
    $total_deposit_price = 0;
    $total_rental_price = 0;
    $customer_username;
    $customer_name;
if (!empty($_GET['tool_number'])) {
    $cookie_name = "tool_number";
    $cookie_value = $_GET['tool_number'];
    setcookie($cookie_name, "", time() - 3600);
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
    header('Refresh: 0.01; ' . 'url=tool_details.php');
}
?>

		<title>Drop off Reservation details</title>
	</head>

	<body>
		<div id="main_container">
    <?php include("lib/menu.php"); ?>

			<div class="center_content">
				<div class="center_left">
					<div class="title_name"><?php print "Reservation ID: # ". $id; ?></div>
					<div class="features">
						<div class="profile_section">
							<div class="subtitle">Drop Off Reservation</div>

							<?php
                            $query = "SELECT a.reservation_id, a.customer_username, f.total_deposit_price, f.total_rental_price ".
                                "FROM Reservation a " .
                                "LEFT JOIN (SELECT reservation_id, sum(purchase_price)*0.4 as total_deposit_price, sum(purchase_price)*0.15 ".
                                "as total_rental_price FROM ReservationDetail c LEFT JOIN Tool d ON c.tool_number = d.tool_number GROUP BY 1) f ".
                                "ON a.reservation_id = f.reservation_id WHERE a.reservation_id = '$id'";

                            $result = mysqli_query($db, $query);
                                include('lib/show_queries.php');

                                if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                      //false positive if no friends
                                     array_push($error_msg,  "Query ERROR: Failed to get pickup reservation <br>" . __FILE__ ." line:". __LINE__ );
                                }

                                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                $count = mysqli_num_rows($result);
                                $total_deposit_price = $row['total_deposit_price'];
                                $total_rental_price = $row['total_rental_price'];
                                $customer_username = $row['customer_username'];

							?>

                            <?php
                            $query = "SELECT User.first_name, User.middle_name, User.last_name ".
                                "FROM User " .
                                "WHERE username = '$customer_username' ";

                            $result = mysqli_query($db, $query);
                            include('lib/show_queries.php');

                            if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                //false positive if no friends
                                array_push($error_msg,  "Query ERROR: Failed to get customer info <br>" . __FILE__ ." line:". __LINE__ );
                            }
                            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                            $count = mysqli_num_rows($result);
                            if ($row) {

                                $customer_name= $row['first_name'] .' ' .$row['middle_name'] .' ' .$row['last_name'];
                                print '<tr>';
                                print 'Customer Name: ' . $customer_name .'</td>';
                                print '<br>';
                                print 'Total Deposit: $' . $total_deposit_price ;
                                print '<br>';
                                print 'Total Rental Price: $' . $total_rental_price;
                                print '<br>';

                                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);


                            }
                            else {
                                print "<br/>None!";
                            }
                            ?>

                        </div>
                        <div class="profile_section">
                            <div class="subtitle">Tool Name</div>

                            <?php
                            $query = "SELECT Tool.tool_number, power_source, sub_option, sub_type " .
                                "FROM Tool " .
                                "INNER JOIN ReservationDetail ON ReservationDetail.tool_number=Tool.tool_number " .
                                "WHERE ReservationDetail.reservation_id='$id' " ;


                            $result = mysqli_query($db, $query);
                            include('lib/show_queries.php');

                            if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                //false positive if no friends
                                array_push($error_msg,  "Query ERROR: Failed to get tool detail " . __FILE__ ." line:". __LINE__ );
                            }

                            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                            $count = mysqli_num_rows($result);

                            if ($row) {

                                while ($row){

                                    $des =  $row['power_source'] . ' '.$row['sub_option'] . ' '.$row['sub_type'];
                                    print '<tr>';
                                    print "<a href=". "\"check_tool_availability.php?tool_number=" .
                                        urlencode($row['tool_number']) . "\"". "target=\"_blank\"". ">".$des."</a>";
                                    print '<br>';
                                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                                }

                            }
                            else {
                                print "<br/>None!";
                            }
                            ?>
						</div>

									 <!-- <td><a href="pick_up.php">Back</a></td> -->

					 </div>

					 <br>
					 <br>
					 <div class="back to pickup menu">

						 <div class="center_content">

										 <form action="drop_off.php" method="post" enctype="multipart/form-data">

														 <input type="image" src="img/button_back.gif" />

										 <?php include("lib/error.php"); ?>

										 <div class="clear"></div>
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
