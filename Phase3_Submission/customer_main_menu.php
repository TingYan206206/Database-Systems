<?php

include('lib/common.php');
// written by GTusername4

if (!isset($_SESSION['username'])) {
	header('Location: login.php');
	exit();
}
    /*
    // ERROR: demonstrating SQL error handlng, to fix
    // replace 'sex' column with 'gender' below:
    $query = "SELECT firstname, lastname, gender, birthdate, currentcity, hometown " .
		 "FROM User INNER JOIN RegularUser ON User.email=RegularUser.email " .
		 "WHERE User.email='{$_SESSION['email']}'";

    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
 
    if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to get User profile...<br>" . __FILE__ ." line:". __LINE__ );
    }
    */
?>

<?php include("lib/header.php"); ?>
<title>Tools4Rent Customer Main Menu</title>
</head>

<body>
		<div id="main_container">
    <?php include("lib/menu_customer.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name">
                <?php print "Main Menu"; ?>
            </div>          
            <div class="features">   
            
                <div class="profile_section">
                    <a href="view_profile.php" <?php if($current_filename=='customer_main_menu.php') echo "class='subtitle'"; ?>> View Profile</a>					
                </div>

                <div class="profile_section">
                    <a href="check_tool_availability.php" <?php if($current_filename=='customer_main_menu.php') echo "class='subtitle'"; ?>> Check Tool Availability</a>
                </div>	

                <div class="profile_section">
                    <a href="make_reservation.php" <?php if($current_filename=='customer_main_menu.php') echo "class='subtitle'"; ?>> Make Reservation</a>					
                </div>

                <div class="profile_section">
                    <a href="login.php" <?php if($current_filename=='customer_main_menu.php') echo "class='subtitle'"; ?>> Exit</a>                     
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