<script>
    function yourfunction(radioid)
    {
        if(radioid == 1) {
            document.getElementById('existing').style.display = '';
            document.getElementById('newCard').style.display = 'none';

        }
        else{
            document.getElementById('existing').style.display = 'none';
            document.getElementById('newCard').style.display = '';
        }
    }
</script>

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


<?php
    $id = $_POST[reservation_id];
    $total_deposit_price = 0;
  	$total_rental_price = 0;
  	$customer_username;
  	$customer_name;
?>
<title>Pickup Summary</title>
</head>

<body>
    <div id="main_container">
        <?php include("lib/menu_clerk.php"); ?>
        <div class="center_content">
          <div class="reservation_summary_section">
            <div class="subtitle">Pickup Reservation</div>

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


          <div>

              <form name="form" action="" method="post">
                  <div class="subtitle">Credit Card:</div>

                  <table style="width:50%">
                      <tr>
                          <td>TYPE:</td>
                          <td><input type="radio" name="cardType" value="rbExisting" checked onClick="javascript:return yourfunction(1)"> Existing</td>
                          <td><input type="radio" name="cardType" value="rbNew" onclick="javascript:return yourfunction(2)"> New</td>
                      </tr>
                  </table><br>
              </form>

            <form action="rental_contract.php" method="post">
                  <div id = "existing" style = "display:">
                    <input type="text" hidden="" name="reservation_id" value= <?php echo $id ?>>
                    <input type="text" name="customer_username" hidden="" value="<?php echo $customer_username ?>">
                    <input type="text" name="customer_name" hidden="" value="<?php echo $customer_name ?>">
                    <input type="text" name="total_deposit_price" hidden="" value="<?php echo $total_deposit_price ?>">
                    <input type="text" name="total_rental_price" hidden="" value="<?php echo $total_rental_price ?>">
                    <br>
                      <input type="submit" value="Confirm Pick Up">
                      <input type="text" name="update" hidden="" value= "true">
                  </div>
                </form>


            <form action="rental_contract.php" method="post">
                  <div id = "newCard" style = "display:none">
                    <div class="subtitle">Enter Updated Credit Card Information</div>
                      <p><b> **THIS WILL OVERWRITE THE PRIOR CUSTOMERS CREDIT CARD INFORMATION**</b></p>
                    <br/>
                    <br/>
                    <input type="text" name="card_name" placeholder="Name on Credit Card" required/>
                    <input type="text" name="card_number" placeholder="Credit Card #" required/>
                    <br/>
                    <br/>
                    <input type="text" name="cvc" placeholder="cvc" required/>

                  <select name="exp_month" required>
                    <option value="" disabled selected>Expiration Month</option>
                    <option value="1">Jan</option>
                    <option value="2">Feb</option>
                    <option value="3">Mar</option>
                    <option value="4">Apr</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">Aug</option>
                    <option value="9">Sep</option>
                    <option value="10">Oct</option>
                    <option value="11">Nov</option>
                    <option value="12">Dec</option>
                  </select>

                  <select name="exp_year" required>
                    <option value="" disabled selected>Expiration Year</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                  </select>
                  <br/>
                  <br/>
                  <input type="text" name="reservation_id" hidden="" value=<?php echo $id ?>>
                  <input type="text" name="is_new_card" hidden="" value= "true">
                  <input type="text" name="customer_username" hidden="" value="<?php echo $customer_username ?>">
                  <input type="text" name="customer_name" hidden="" value="<?php echo $customer_name ?>">
                  <input type="text" name="total_deposit_price" hidden="" value="<?php echo $total_deposit_price ?>">
                  <input type="text" name="total_rental_price" hidden="" value="<?php echo $total_rental_price ?>">
                  <br>
                      <input type="submit" value="Confirm Pick Up">
                      <input type="text" name="update" hidden="" value= "true">
            </form>
            <?php include("lib/error.php"); ?>
            <?php include("lib/footer.php"); ?>
          </div>
        </div>
    </div>


    </body>

</html>
