<?php

include('lib/common.php');
// written by GTusername4

if (!isset($_SESSION['username'])) {
	header('Location: login.php');
	exit();
}
    
    $query = "SELECT email, Full_Name, Address, max(Home_Phone) as home_phone, max(Cell_Phone) as cell_phone, max(Work_Phone) as work_phone ".
             "FROM (SELECT a.email,CONCAT(a.first_name, ' ', a.middle_name, ' ',a.last_name) as Full_Name, ".
             "CONCAT(b.street_address, ', ',b.city, ', ',b.state, ', ',b.zip_code) as Address, ".
             "(case when (c.phone_type = 'Home Phone' and c.extension is not null) ".
             "then CONCAT('(', c.area_code, ')-', c.exchange, '-', c.number, ' x ',c.extension) ".
             "when (c.phone_type = 'Home Phone' and c.extension is null) ".
             "then CONCAT('(', c.area_code, ')-', c.exchange, '-', c.number) end) as Home_Phone, ".
             "(case when (c.phone_type = 'Cell Phone' and c.extension is not null) ".
             "then CONCAT('(', c.area_code, ')-', c.exchange, '-', c.number, ' x ',c.extension) ".
             "when (c.phone_type = 'Cell Phone' and c.extension is null) ".
             "then CONCAT('(', c.area_code, ')-', c.exchange, '-', c.number) end) as Cell_Phone, ".
             "(case when (c.phone_type = 'Work Phone' and c.extension is not null) ".
             "then CONCAT('(', c.area_code, ')-', c.exchange, '-', c.number, ' x ',c.extension) ".
             "when (c.phone_type = 'Work Phone' and c.extension is null) ".
             "then CONCAT('(', c.area_code, ')-', c.exchange, '-', c.number) end) as Work_Phone ".
             "from User a left join Customer b on a.username = b.username ".
             "inner join Phone c on a.username = c.username ".
             "where a.username = '{$_SESSION['username']}') tmp group by 1,2,3;";

    $result_cust_info = mysqli_query($db, $query);
    include('lib/show_queries.php');
 
    if ( !is_bool($result_cust_info) && (mysqli_num_rows($result_cust_info) > 0) ) {
        $row_cust_info = mysqli_fetch_array($result_cust_info, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to get User profile...<br>" . __FILE__ ." line:". __LINE__ );
    }

    $query ="select reservation_id, start_date, end_date, pick_up_clerk_username, drop_off_clerk_username, Number_of_Days, ".
            "group_concat(Single_tool separator ',') as Tools,sum(Deposit_price) as Total_deposit_price,sum(Rental_price) as Total_rental_price ".
            "from (select r.reservation_id, ".
            "case when power_source = 'Manual' then CONCAT(t.sub_option,' ',t.sub_type) else CONCAT(t.power_source,' ',t.sub_option,' ',t.sub_type) end as Single_tool, ".
            "r.start_date,r.end_date,r.pick_up_clerk_username,r.drop_off_clerk_username,(r.end_date - r.start_date +1) as Number_of_Days, ".
            "t.purchase_price*0.4 as Deposit_price,t.purchase_price*0.15 as Rental_price ".
            "from Reservation r ".
            "left join ReservationDetail rd on r.reservation_id = rd.reservation_id ".
            "left join Tool t on rd.tool_number = t.tool_number ".
            "where r.customer_username = '{$_SESSION['username']}') tmp group by 1,2,3,4,5,6;";
    $result_resv = mysqli_query($db, $query);
    include('lib/show_queries.php');
    
?>

<?php include("lib/header.php"); ?>
<title>Tool4Rent Profile</title>
</head>

<body>
  <div id="main_container">
    <?php include("lib/menu_customer.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name">
                <?php print "Customer Info"; ?>
            </div>          
            <div class="features">   
                <div class="profile_section">  
                    <table>
                        <tr>
                            <td class="item_label">E-mail:</td>
                            <td>
                                <?php print $row_cust_info['email'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="item_label">Full Name:</td>
                            <td>
                                <?php print $row_cust_info['Full_Name'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="item_label">Home Phone:</td>
                            <td>
                                <?php print $row_cust_info['home_phone'] ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="item_label">Work Phone:</td>
                            <td>
                                <?php print $row_cust_info['work_phone'] ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="item_label">Cell Phone:</td>
                            <td>
                                <?php print $row_cust_info['cell_phone'] ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="item_label">Address:</td>
                            <td>
                                <?php print $row_cust_info['Address'] ?>
                            </td>
                        </tr>
                    </table>						
                </div>     
            </div> 			
        </div>
        <div class="center_content">
            <div class="center_left">
                <div class="title_name"><?php print "Reservation"; ?> </div>
            </div>
        </div>   

        <table>
            <tr>
                <th>Reservation ID</th>
                <th>Tools</th>
                <th>Start Data</th>
                <th>End Data</th>
                <th>Pick-up Clerk</th>
                <th>Drop-off Clerk</th>
                <th>Number of Days</th>
                <th>Total Deposit Price</th>
                <th>Total Rental Price</th>
            </tr>
            </tr>
            <?php
            if (isset($result_resv)) {
                while ($row = mysqli_fetch_array($result_resv, MYSQLI_ASSOC)){                            
                  print "<tr>";
                  print "<td>{$row['reservation_id']}</td>";
                  print "<td>{$row['Tools']}</td>";
                  print "<td>{$row['start_date']}</td>";
                  print "<td>{$row['end_date']}</td>";
                  print "<td>{$row['pick_up_clerk_username']}</td>";
                  print "<td>{$row['drop_off_clerk_username']}</td>";
                  print "<td>{$row['Number_of_Days']}</td>";
                  print "<td>{$row['Total_deposit_price']}</td>";
                  print "<td>{$row['Total_rental_price']}</td>";
                  print "</tr>";
              }
            } ?>                          
          <tr>                     
      </table>                           
        

        <?php include("lib/error.php"); ?>

        <div class="clear"></div> 		
    </div>    

    <?php include("lib/footer.php"); ?>

</div>
</body>
</html>