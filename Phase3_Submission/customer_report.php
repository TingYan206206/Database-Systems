<?php include('lib/common.php');
?>

<?php include("lib/menu.php"); ?>

<?php
	include("lib/header.php");

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
<html>
		<title>Generate reports</title>
</head>
<body>
      <div id="main_container">
          <?php include('lib/menu_clerk.php');?>
          <div class="center_content">
            <div class="reservation_summary_section">
              <div class="subtitle">Customer Report</div>
                <?php

//								$query = "SELECT a.customer_id, a.total_reservations, a.total_tool_rented, b.first_name, b.middle_name, b.last_name, b.email, c.area_code,c.exchange, c.number, c.extension ".
//                                "FROM Reservation r LEFT JOIN Customer a ON r.customer_username = a.username ".
//                                "LEFT JOIN User b ON r.customer_username = b.username ".
//                                "LEFT JOIN Phone c ON r.customer_username = c.username WHERE c.is_primary = 1 AND ".
//                                    "MONTH(r.start_date)=MONTH(NOW()) ORDER BY YEAR(r.start_date) DESC, MONTH(r.start_date) DESC, DAY(r.start_date) DESC ";
//
                    $query = "SELECT DISTINCT a.customer_id, a.total_reservations, a.total_tool_rented, b.first_name, b.middle_name, b.last_name, b.email, c.area_code, c.exchange, c.number, c.extension ".
                        "FROM Reservation r LEFT JOIN Customer a ON r.customer_username = a.username ".
                        "LEFT JOIN User b ON r.customer_username = b.username ".
                        "LEFT JOIN Phone c ON r.customer_username = c.username WHERE c.is_primary = 1 AND ".
                        "MONTH(r.start_date)=MONTH(NOW()) Order by a.total_tool_rented DESC,b.last_name ";


                    $result = mysqli_query($db, $query);
                    include('lib/show_queries.php');

                    if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
									//false positive if no friends
                        array_push($error_msg,  "Query ERROR: Failed to get customer detail " . __FILE__ ." line:". __LINE__ );
                    }?>
                <table id="myTable" style="width:100%" >
                    <tr>
                        <td class="heading" style="cursor: pointer" onclick="sortTable(0)">Customer ID</td>
                        <td class="heading" >View Profile?</td>
                        <td class="heading" style="cursor: pointer" onclick="sortTable(2)">First Name</td>
                        <td class="heading" style="cursor: pointer" onclick="sortTable(3)">Middle Name</td>
                        <td class="heading" style="cursor: pointer" onclick="sortTable(4)">Last Name</td>
                        <td class="heading" style="cursor: pointer" onclick="sortTable(5)">Email</td>
                        <td class="heading" style="cursor: pointer" onclick="sortTable(6)">Phone</td>
                        <td class="heading" style="cursor: pointer" onclick="sortTable(7)">Total # Reservations</td>
                        <td class="heading" style="cursor: pointer" onclick="sortTable(8)">Total # Tools Rented</td>

                    </tr>

                    <?php while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){ ?>

                        <tr>
                            <td><?php echo $row['customer_id']; ?></td>
                            <td><a href="<?php echo 'view_customer_profile.php?email='  . $row['email']; ?>"><?php echo 'View Profile' ?></a></td>
                            <td><?php echo $row['first_name']; ?></td>
                            <td><?php echo $row['middle_name']; ?></td>
                            <td><?php echo $row['last_name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['area_code'].$row['exchange'] .$row['number'].' '; ?></td>
                            <?php
                                $query = "SELECT count(*) as ct FROM Reservation WHERE customer_username = '{$row['email']}' and month(start_date) = month(now());";
                                $result_resv = mysqli_query($db, $query);
                                include('lib/show_queries.php');
                                $row_resv = mysqli_fetch_array($result_resv, MYSQLI_ASSOC);
                                $total_reservations = $row_resv['ct'];

                                $query = "SELECT count(*) as ct FROM (SELECT a.reservation_id, b.tool_number ".
                                         "from Reservation a left join ReservationDetail b on a.reservation_id = b.reservation_id ".
                                         "WHERE a.customer_username = '{$row['email']}' ".
                                         "and month(start_date) = month(now())) c;";
                                $result_tool = mysqli_query($db, $query);
                                include('lib/show_queries.php');
                                $row_tool = mysqli_fetch_array($result_tool, MYSQLI_ASSOC);
                                $total_tool_rented = $row_tool['ct'];
                            ?>
                            <?php echo '<td style="text-align: center">' . $total_reservations. '</td>'; ?>
                            <?php echo '<td style="text-align: center">' . $total_tool_rented. '</td>'; ?>

                        </tr>
                    <?php } ?>
                </table>

                <div class="back to generate report menu">

                    <div class="center_content">
                        <td><a href="generate_reports.php">Back to Report Menu</a></td>

                        <form action="" enctype="multipart/form-data">

                            <input type="submit" value="Reload Result">
                        </form>



                        <?php include("lib/error.php"); ?>

                        <div class="clear"></div>
                    </div>

                </div>

              <?php include("lib/footer.php"); ?>

              </div>
              </div>
              </div>
</body>
</html>
