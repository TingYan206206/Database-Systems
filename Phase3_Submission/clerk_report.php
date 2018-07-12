<?php include('lib/common.php');
?>


<?php include("lib/header.php"); ?>
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
<head>
		<title>Generate reports</title>

</head>
<body>
      <div id="main_container">
          <?php include('lib/menu_clerk.php');?>
          <div class="center_content">
            <div class="reservation_summary_section">
              <div class="subtitle">Clerk Report</div>
                <br>
                The list of clerks where their total pickups and dropoffs are shown for the past month.
                <br>
                <br>
                <table id="myTable" style="width:100%" >
                    <col width="60">
                    <col width="60">
                    <col width="60">
                    <col width="60">
                    <col width="80">
                    <col width="80">
                    <col width="80">
                    <col width="80">
                    <col width="80">
                    <col width="80">
                    <tr>
                        <td class="heading" style="cursor: pointer" onclick="sortTable(0)">Clerk ID</td>
                        <td class="heading" style="cursor: pointer" onclick="sortTable(1)">First Name</td>
                        <td class="heading" style="cursor: pointer" onclick="sortTable(2)">Middle Name</td>
                        <td class="heading" style="cursor: pointer" onclick="sortTable(3)">Last Name</td>
                        <td class="heading" style="cursor: pointer" onclick="sortTable(4)">Email</td>
                        <td class="heading" style="cursor: pointer" onclick="sortTable(5)">Hire Date</td>
                        <td class="heading" style="cursor: pointer" onclick="sortTable(6)">Number of Pickups</td>
                        <td class="heading" style="cursor: pointer" onclick="sortTable(7)">Number of Dropoffs</td>
                        <td class="heading" style="cursor: pointer" onclick="sortTable(8)">Combined Total</td>
                    </tr>
                    <?php

//
                    $query = "SELECT a.clerk_id, a.hire_date, a.num_of_dropoffs, a.num_of_pickups, a.combined_total, b.first_name, b.middle_name, b.last_name, b.email " .
                    "FROM Clerk a LEFT JOIN User b " .
                    "ON a.username = b.username " ;


	              $result = mysqli_query($db, $query);
	              include('lib/show_queries.php');

	              if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
	                  //false positive if no friends
	                  array_push($error_msg,  "Query ERROR: Failed to get clerk detail " . __FILE__ ." line:". __LINE__ );
	              }
	              while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

                      print '<tr>';
                      print '<td>' . $row['clerk_id'] . ' '. '</td>';
                      print '<td>' . $row['first_name'] . ' '. '</td>';
                      print '<td>' . $row['middle_name'] . ' '. '</td>';
                      print '<td>' . $row['last_name'] . ' '. '</td>';
                      print '<td>' . $row['email'] . ' '. '</td>';
                      print '<td>' . $row['hire_date'] . '   '. '</td>';
                      // pick up number
                      $query = "SELECT COUNT(pick_up_clerk_username) as myCount FROM `Reservation`
									WHERE Reservation.pick_up_clerk_username='{$row['email']}'
									and MONTH(start_date) = MONTH(Now());";
                      $pick_up_result = mysqli_query($db, $query);
                      $pick_up_count = mysqli_fetch_array($pick_up_result, MYSQLI_ASSOC);
                      print "<td style=\"text-align: center\">" . $pick_up_count['myCount'] . "</td>";

                      //drop-offs
                      $query = "SELECT COUNT(drop_off_clerk_username) as myCount FROM `Reservation`
									WHERE Reservation.drop_off_clerk_username='{$row['email']}'
									and MONTH(end_date) = MONTH(Now());";

                      $drop_off_result = mysqli_query($db, $query);
                      $drop_off_count = mysqli_fetch_array($drop_off_result, MYSQLI_ASSOC);
                      print "<td style=\"text-align: center\">" . $drop_off_count['myCount'] . "</td>";
                      $total = $drop_off_count['myCount']+ $pick_up_count['myCount'];
                      print "<td style=\"text-align: center\">" . $total . "</td>";

                      print '</tr>';

                  }

	              ?>

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
              <div class="clear"></div>


              <?php include("lib/footer.php"); ?>

              </div>
              </div>
              </div>
</body>
</html>
