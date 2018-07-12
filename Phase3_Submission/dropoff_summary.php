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


<?php include("lib/header.php"); ?>
<?php include("lib/menu.php"); ?>
<?php
  $id = $_POST[reservation_id];
  $total_deposit_price = 0;
  $total_rental_price = 0;
  $customer_username;
  $customer_name;
  $total_due;

if (!empty($_GET['tool_number'])) {
    $cookie_name = "tool_number";
    $cookie_value = $_GET['tool_number'];
    setcookie($cookie_name, "", time() - 3600);
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
    header('Refresh: 0.01; ' . 'url=tool_details.php');
}
?>

<title>dropoff Summary</title>
</head>
<body>
    <div id="main_container">
      <?php include('lib/menu_clerk.php');?>
        <div class="center_content">
          <div class="reservation_summary_section">
            <div class="subtitle">Drop Off Reservation</div>
              <div class="subtitle">Reservation Details</div>

              <?php

              $query = "SELECT a.reservation_id, a.customer_username, f.total_deposit_price, f.total_rental_price ".
                  "FROM Reservation a " .
                  "LEFT JOIN (SELECT reservation_id, sum(purchase_price)*0.4 as total_deposit_price, sum(purchase_price)*0.15 ".
                  "as total_rental_price FROM ReservationDetail c LEFT JOIN Tool d ON c.tool_number = d.tool_number GROUP BY 1) f ".
                  "ON a.reservation_id = f.reservation_id WHERE a.reservation_id = '$id'";


              $result = mysqli_query($db, $query);
              include('lib/show_queries.php');

              if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
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
                  $total_due = $total_rental_price - $total_deposit_price;
                  print '<tr>';
                  print 'Reservation ID#: ' . $id .'</td>';
                  print '<br>';
                  print 'Customer Name: ' . $customer_name .'</td>';
                  print '<br>';
                  print 'Total Deposit: $' . $total_deposit_price ;
                  print '<br>';
                  print 'Total Rental Price: $' . $total_rental_price;
                  print '<br>';
                  print 'Total Due: $' . $total_due;
                  print '<br>';
                  print '<br>';
                  print '<br>';

                  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);


              }
              else {
                  print "<br/>None!";
              }
              ?>
          </div>
          <div class="dropoff_tool_info">
              <?php

              $query = "SELECT  a.tool_number, a.power_source, a.sub_option, a.sub_type, a.purchase_price " .
                  "FROM Tool a " .
                  "INNER JOIN ReservationDetail ON ReservationDetail.tool_number=a.tool_number " .
                  "WHERE ReservationDetail.reservation_id=$id " ;


              $result = mysqli_query($db, $query);
              include('lib/show_queries.php');

              if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                  //false positive if no friends
                  array_push($error_msg,  "Query ERROR: Failed to get tool detail " . __FILE__ ." line:". __LINE__ );
              }?>

              <table style="width:100%">
                  <tr>
                      <td class="heading">Tool ID</td>
                      <td class="heading">Tool Name</td>
                      <td class="heading">Deposit Price</td>
                      <td class="heading">Rental Price</td>

                  </tr>

                  <?php while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){ ?>

                      <tr>
                          <td><?php echo $row['tool_number'] ; ?></td>
                          <td><?php print "<a href=". "\"check_tool_availability.php?tool_number=" .
                          urlencode($row['tool_number']) . "\"". "target=\"_blank\"". ">".$row['power_source'] . ' '. $row['sub_option'] . ' ' . $row['sub_type']."</a>"; ?></td>

                          <td><?php echo '$'.$row['purchase_price']*0.4 ; ?></td>
                          <td><?php echo '$'.$row['purchase_price']*0.15; ?></td>
                      </tr>
                  <?php }

                  print '<td>' . Totals . '</td>';
                  print '<td>' . '' . '</td>';
                  print '<td>' . '$'.$total_deposit_price . '</td>';
                  print '<td>' . '$'.$total_rental_price . '</td>';

                  ?>

              </table>


          </div>

            <?php
            if (isset($_POST['update'])){

                $query = "UPDATE Reservation SET drop_off_clerk_username = '$username' ".
                    "WHERE reservation_id = '$id'";
                $result = mysqli_query($db, $query);

                include('lib/show_queries.php');
                $query = "UPDATE Clerk SET num_of_dropoffs = num_of_dropoffs + 1 , combined_total = combined_total+1 " .
                    "WHERE username = '$username'";
                $result = mysqli_query($db, $query);

                include('lib/show_queries.php');
                $query = "UPDATE Tool SET current_status ='for rent' ".
                    "WHERE tool_number in ".
                    "(SELECT tool_number from reservationdetail where reservation_id = $id)";
                $result = mysqli_query($db, $query);

                include('lib/show_queries.php');

                if (mysqli_affected_rows($db) == -1) {
                    array_push($error_msg,  "UPDATE ERROR: Regular User... <br>".  __FILE__ ." line:". __LINE__ );
                    //array_push($error_msg,  'Error# '. mysqli_errno($db) . ": " . mysqli_error($db));
                }

            }
            ?>


          <br>
          <br>

        <form action="" method="post" onsubmit="alert('Drop off confirmed!')">
            <input type="text" name="update" hidden="" value= "true">
            <input type="text" name="reservation_id" hidden="" value=<?php echo $id;?>>
            <input type="text" name="clerk_username" hidden="" value=<?php echo $username;?>>
            <input type="image" src="img/button_drop-off.gif" class="confirm"/>
            <!-- <input type="image" src="img/button_print-receipt.gif" class="confirm"/> -->
        </form>



            <style type="text/css" media="print">
                .printbutton {
                    visibility: hidden;
                    display: none;
                }
            </style>

            <script>
                document.write("<input type='button' " +
                    "onClick='window.print()' " +
                    "class='printbutton' " +
                    "value='Print Receipt'/>");
            </script>

                <?php include("lib/error.php"); ?>

                <div class="clear"></div>
        </div>

					<?php include("lib/footer.php"); ?>

        </div>
    </body>
</html>
