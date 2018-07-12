<?php

include('lib/common.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$query = "SELECT username, first_name, middle_name, last_name " .
    "FROM User " .
    "WHERE User.email = '{$_SESSION['username']}'";

$result = mysqli_query($db, $query);
include('lib/show_queries.php');

if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);
    $user_name = $row['first_name'] . " " .$row['middle_name'] . " " . $row['last_name'];
    $username =  $row['username'];
} else {
    array_push($error_msg,  "SELECT ERROR: User profile <br>" . __FILE__ ." line:". __LINE__ );
}

?>
<script>
    function myOperator(a, b, sign){
        if(sign == ">"){
            return a>b;
        }else if(sign == "<"){
            return a<b;
        }
    }

    function myCompare(x, y, sign){
        if(parseFloat(x) == x){
            return myOperator(parseFloat(x), parseFloat(y), sign);
        }else{
            if(x[0] == "$"){
                return myCompare(x.substring(1), y.substring(1), sign);
            }else{
                return myOperator(x.toLowerCase(), y.toLowerCase(), sign);
            }
        }
    }

    function sortTable(n) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("myTable");
        switching = true;
        dir = "desc";
        while (switching) {
            switching = false;
            rows = table.getElementsByTagName("TR");
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                if (dir == "asc") {
                    if (myCompare(x.innerHTML, y.innerHTML, ">")) {
                        shouldSwitch= true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (myCompare(x.innerHTML, y.innerHTML, "<")) {
                        shouldSwitch= true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount ++;
            } else {
                if (switchcount == 0 && dir == "desc") {
                    dir = "asc";
                    switching = true;
                }
            }
        }
    }

</script>
<?php
	include("lib/header.php");
?>
<html>
		<title>Dropoff Reservation</title>
	</head>

	<body>
		<div id="main_container">
    <?php include('lib/menu_clerk.php');?>

			<div class="center_content">
				<div class="center_left">
					<div class="features">
						<div class="profile_section">
							<div class="subtitle">Drop off Reservation</div>

							<?php
                                $query = "SELECT a.reservation_id, a.start_date, a.end_date, b.username, b.customer_id " .
                                         "FROM Reservation a ".
                                         "LEFT JOIN Customer b ON a.customer_username = b.username ".
                            "Where a.end_date >= DATE(Now())";

                                $result = mysqli_query($db, $query);
								include('lib/show_queries.php');

                                if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                      //false positive if no friends
                                     array_push($error_msg,  "Query ERROR: Failed to get pickup reservation <br>" . __FILE__ ." line:". __LINE__ );
                                }?>
                                    <table id="myTable" style="width:200%">
                                        <tr>
                                            <td class="heading" style="cursor: pointer" onclick="sortTable(1)">Reservation ID</td>
                                            <td class="heading" style="cursor: pointer" onclick="sortTable(2)">Customer</td>
                                            <td class="heading" style="cursor: pointer" onclick="sortTable(3)">Customer ID</td>
                                            <td class="heading" style="cursor: pointer" onclick="sortTable(4)">Start Date</td>
                                            <td class="heading" style="cursor: pointer" onclick="sortTable(5)">End Date</td>
                                        </tr>

                                        <?php while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){ ?>

                                            <tr style="font-size:12px">
                                                <td><a href="<?php echo 'drop_off_reservation_detail.php?reservation_id=' . $row['reservation_id']; ?>"><?php echo $row['reservation_id'] ?></a></td>
                                                <td hidden><?php echo $row['reservation_id'] ?></td>
                                                <td><?php echo $row['username']; ?></td>
                                                <td><?php echo $row['customer_id']; ?></td>
                                                <td><?php echo $row['start_date']; ?></td>
                                                <td><?php echo $row['end_date']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>

						</div>
						<div class="choose_reservation_to_pickup">
                            <form action="dropoff_summary.php" method="post" enctype="multipart/form-data">

                                <div class="login_form_row">

                                    <input type="text" name="reservation_id" value="Enter Reservation ID"
                                           onclick="if(this.value=='Enter Reservation ID'){this.value=''}"
                                           onblur="if(this.value==''){this.value='Enter Reservation ID'}"/>

                                    <input type="image" src="img/button_drop-off.gif" class="pickup"/>

                                </div>
                            </form>

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
