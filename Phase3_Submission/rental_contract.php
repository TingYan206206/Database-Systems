<?php
include('lib/common.php');
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit();
}

$query = "SELECT username, first_name, middle_name, last_name " .
     "FROM User " .
     "WHERE User.username = '{$_SESSION['username']}'";

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

<?php

if( $_SERVER['REQUEST_METHOD'] == 'POST'){

    $id = $_POST[reservation_id];
    $customer_name = $_POST[customer_name];
    $customer_username = $_POST[customer_username];
    $total_deposit = $_POST[total_deposit_price];
    $total_rental = $_POST[total_rental_price];

    $query = "UPDATE Reservation SET pick_up_clerk_username = '$username' ".
        "WHERE reservation_id = '$id'";
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');

    $query = "UPDATE Clerk SET num_of_pickups = num_of_pickups + 1, combined_total = combined_total +1 " .
        "WHERE username = '$username'";
    $result = mysqli_query($db, $query);

    include('lib/show_queries.php');
    $query = "UPDATE Tool SET current_status ='Rented', number_of_rent_time = number_of_rent_time+1 ".
        "WHERE tool_number in ".
        "(SELECT tool_number from reservationdetail where reservation_id = $id)";
    $result = mysqli_query($db, $query);

    include('lib/show_queries.php');
    if (mysqli_affected_rows($db) == -1) {
        array_push($error_msg,  "UPDATE ERROR: unable to add pickup clerk name... <br>".  __FILE__ ." line:". __LINE__ );
    }

    if($_POST['is_new_card'] == "true"){

        $card_name = $_POST[card_name];
        $card_number = $_POST[card_number];
        $cvc = $_POST[cvc];
        $expiration_month = $_POST[exp_month];
        $expiration_year = $_POST[exp_year];


        $query = "UPDATE Customer SET card_number ='$card_number' " .
            "WHERE username='$customer_username' ";

        $result = mysqli_query($db, $query);

        include('lib/show_queries.php');

        if (mysqli_affected_rows($db) == -1) {
            array_push($error_msg,  "UPDATE ERROR: Regular User... <br>".  __FILE__ ." line:". __LINE__ );
        }



        if (mysqli_affected_rows($db) == -1) {
            array_push($error_msg,  "UPDATE ERROR: Regular User... <br>".  __FILE__ ." line:". __LINE__ );
        }

        $query = "UPDATE CreditCard SET name = '$card_name', cvc ='$cvc', exp_year ='$expiration_year', exp_month='$expiration_month' " .
                  "WHERE card_number = '$card_number'";
        $result = mysqli_query($db, $query);

        include('lib/show_queries.php');

        if (mysqli_affected_rows($db) == -1) {
            array_push($error_msg,  "UPDATE ERROR: cannot add new creditcard info... <br>".  __FILE__ ." line:". __LINE__ );
        }

    }


}

?>



<?php include("lib/header.php"); ?>
<?php include("lib/menu.php"); ?>

<title>Rental contract</title>
</head>
  <title>Print Test</title>
</head>
<body>
      <div id="main_container">
        <?php include('lib/menu_clerk.php');?>
          <div class="center_content">
            <div class="reservation_summary_section">
              <div class="subtitle">Pickup Reservation</div>
              <div class="subtitle">Rental Contract</div>



              <?php
              $query ="SELECT  a.start_date, a.end_date, b.card_number ".
                  "FROM Reservation a LEFT JOIN Customer b ON a.customer_username = b.username WHERE a.reservation_id = '$id'";
              $result = mysqli_query($db, $query);
              include('lib/show_queries.php');

              if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                  //false positive if no friends
                  array_push($error_msg,  "Query ERROR: Failed to get tool detail " . __FILE__ ." line:". __LINE__ );
              }

              $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                print 'Pick-up Clerk: ' . $user_name;
                print '<br>';
              print 'Customer Name: ' . $customer_name;
              print '<br>';
              print 'Credit Card #: ' . $row['card_number']. '</td>';
              print '<br>';
              print 'Start Date: ' . $row['start_date']. '</td>';
              print '<br>';
              print 'End Date: ' . $row['end_date']. '</td>';
              print '<br>';
              print '<br>';
              print '<br>';


              ?>
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
              }

              $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
              $count = mysqli_num_rows($result);

              if ($row) {
                print '<table>';
                print '<tr>';
                print '<td class="heading">Tool ID</td>';
                print '<td class="heading">Tool Name</td>';
                print '<td class="heading">Deposit Price</td>';
                print '<td class="heading">Rental Price</td>';
                print '</tr>';


                  while ($row){

                      print '<tr>';
                      print '<td>' . $row['tool_number'] . ' '. '</td>';
                      print '<td>' . $row['power_source'] . ' '. $row['sub_option'] . ' ' . $row['sub_type'] .'</td>';
                      print '<td>' . $row['purchase_price']*0.4 . '</td>';
                      print '<td>' . $row['purchase_price']*0.15 . '</td>';

                      print '</tr>';
                      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                  }

                  print '<td>' . Totals . '</td>';
                  print '<td>' . '' . '</td>';
                  print '<td>' . $total_deposit . '</td>';
                  print '<td>' . $total_rental . '</td>';
                  print '</table>';
              }
              else {
                      print "<br/>None!";
                  }
              ?>

              <div class="subtitle">Signatures</div>
              <div>

                <span style="text-decoration: underline;"  >x____________________________ </span>
                <span style="padding-right:20px"></span>
                <span style="text-decoration: underline;">Date:___________________________ </span>
                <br>
                <?php print '<td>' . 'Pickup Clerk - '. $user_name . '</td>';?>
                <br>
                <br>
                <span style="text-decoration: underline;"  >x____________________________ </span>
                <span style="padding-right:20px"></span>
                <span style="text-decoration: underline;">Date:___________________________ </span>
                <br>
                <?php

                  print '<td>' . 'Cusotmer - '. $customer_name . '</td>';

                ?>
                <br>
                <br>

              </div>

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
              "value='Print Contract'/>");
              </script>

     <?php include("lib/error.php"); ?>
         <?php include("lib/footer.php"); ?>

</div>
</body>
</html>
