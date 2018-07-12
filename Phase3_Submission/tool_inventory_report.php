<?php include('lib/common.php');
?>


<?php include("lib/header.php"); ?>
<?php include("lib/menu.php"); ?>
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
if (!empty($_GET['tool_number'])) {
    $cookie_name = "tool_number";
    $cookie_value = $_GET['tool_number'];
    setcookie($cookie_name, "", time() - 3600);
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
    header('Refresh: 0.01; ' . 'url=tool_details.php');
}
?>
<html>
		<title>Generate reports</title>
</head>
<body>
      <div id="main_container">
          <?php include('lib/menu_clerk.php');?>
          <div class="center_content">
            <div class="tool_type">
              <div class="subtitle">Tool Inventory Report</div>
							The list of tools where their total profit and cost are shown for all time.

							<div class="tool_type">
	              <div class="subtitle">Type</div>
								<form method="post" enctype="multipart/form-data">
	                <label class="login_label"></label>
	                                <input type="radio" name="type" value="All" checked> All Tools
                                    <input type="radio" name="type" value="Hand Tool" > Hand Tools
									<input type="radio" name="type" value="Garden Tool" > Garden Tools
									<input type="radio" name="type" value="Ladder" > Ladder Tools
	                                <input type="radio" name="type" value="Power Tool"> Power Tool <br>
	                                <label class="login_label"></label>

									<div class="subtitle">Custom Search</div>
									<form method="post" enctype="multipart/form-data">

											<div class="login_form_row">

													<input type="text" name="keyword" placeholder="Enter Keyword"/>
													<input type="image" src="img/button_search.gif" />
                                            </div>
                                    </form>

											</div>
											</div>
              <br>
              <table id="myTable" style="width:100%" >
                  <thead>
                  <tr>
                      <td class="heading" style="cursor: pointer" onclick="sortTable(0)">Tool ID</td>
                      <td class="heading" style="cursor: pointer" onclick="sortTable(1)">Current Status</td>
                      <td class="heading" style="cursor: pointer" onclick="sortTable(2)">Date</td>
                      <td class="heading" style="cursor: pointer" onclick="sortTable(4)">Description</td>
                      <td class="heading" style="cursor: pointer" onclick="sortTable(5)">Rental Profit</td>
                      <td class="heading" style="cursor: pointer" onclick="sortTable(6)">Total Cost</td>
                      <td class="heading" style="cursor: pointer" onclick="sortTable(7)">Total Profit</td>
                  </tr>

											<?php
                                                    $keyword = $_POST[keyword];
													$type = $_POST[type];

											if ($type !='All') {
                                                $query = "SELECT a.tool_number as tool_id, a.current_status, a.sub_type, a.power_source, " .
                                                    "a.sub_option, (CASE WHEN a.current_status = 'Sold' THEN b.sale_date WHEN a.current_status = 'Rented' THEN e.start_date WHEN a.current_status = 'For-Sale' THEN b.for_sale_date ELSE NULL END) AS " .
                                                    "Date, (a.purchase_price* 0.15 * a.number_of_rent_time) as rental_profit, a.purchase_price as total_cost, (a.purchase_price*0.15 * a.number_of_rent_time  - a.purchase_price) AS " .
                                                    "total_profit FROM Tool a LEFT JOIN SaleStatus b ON a.tool_number = b.tool_number " .
                                                    "LEFT JOIN ReservationDetail d " .
                                                    "ON a.tool_number = d.tool_number LEFT JOIN Reservation e ON d.reservation_id = e.reservation_id " .
                                                    "WHERE a.type = '$type' AND (a.type LIKE '%$keyword%' OR a.sub_type LIKE '%$keyword%' OR a.sub_option LIKE '%$keyword%')";

                                            }
                                            else {
                                                $query = "SELECT a.tool_number as tool_id, a.current_status, a.sub_type, a.power_source, " .
                                                    "a.sub_option, (CASE WHEN a.current_status = 'Sold' THEN b.sale_date WHEN a.current_status = 'Rented' THEN e.start_date WHEN a.current_status = 'For-Sale' THEN b.for_sale_date ELSE NULL END) AS " .
                                                    "Date, (a.purchase_price* 0.15 * a.number_of_rent_time) as rental_profit, a.purchase_price as total_cost, (a.purchase_price*0.15 * a.number_of_rent_time  - a.purchase_price) AS " .
                                                    "total_profit FROM Tool a LEFT JOIN SaleStatus b ON a.tool_number = b.tool_number " .
                                                    "LEFT JOIN ReservationDetail d " .
                                                    "ON a.tool_number = d.tool_number LEFT JOIN Reservation e ON d.reservation_id = e.reservation_id " .
                                                    "WHERE (a.type LIKE '%$keyword%' OR a.sub_type LIKE '%$keyword%' OR a.sub_option LIKE '%$keyword%') ";
                                            }
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

															print '<tr>';
															print '<td>' . $row['tool_id'] . ' '. '</td>';
                                                            if($row['current_status']=='for rent') // [val1] can be 'approved'
                                                                echo "<td style='background-color: #008000;'>".$row['current_status']. "</td>";
                                                            else if($row['current_status']=='Rented')// [val2]can be 'rejected'
                                                                echo "<td style='background-color: #ffab0a;'>".$row['current_status']. "</td>";
                                                            print '<td>' . $row['Date'] . ' '. '</td>';
//
															$des = $row['power_source'] . ' '. $row['sub_option'] . ' ' . $row['sub_type'];
                                                            print '<td>' ."<a href=". "\"check_tool_availability.php?tool_number=" .
                                                            urlencode($row['tool_id']) . "\"". "target=\"_blank\"". ">".$des."</a>". '</td>';
                                                            print '<td hidden>' .$des. '</td>';
															print '<td>' . $row['rental_profit'] . '</td>';
															print '<td>' . $row['total_cost'] . '</td>';
															print '<td>' . $row['total_profit'] . '</td>';
															print '</tr>';

															$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

													}
													print '</table>';
											}

											?>
              </table>
          </div>

              <div class="back to generate report menu">

                  <div class="center_content">
                      <td><a href="generate_reports.php">Back to Report Menu</a></td>
                      <br>

                      <form action="" enctype="multipart/form-data">

                          <input type="submit" value="Reload Result">
                      </form>



                          <?php include("lib/error.php"); ?>

                          <div class="clear"></div>
                  </div>

              </div>


              <?php include("lib/error.php"); ?>

              <div class="clear"></div>
              <?php include("lib/footer.php"); ?>

              </div>


</body>
</html>
