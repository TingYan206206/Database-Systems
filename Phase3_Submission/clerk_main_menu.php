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
<title>Tools4Rent Clerk Main Menu</title>
</head>

<body>
		<div id="main_container">
    <?php include("lib/menu_clerk.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name">
                <?php print "Main Menu"; ?>
            </div>          
            <div class="features">   
            
                <div class="profile_section">
                    <a href="pick_up.php" <?php if($current_filename=='clerk_main_menu.php') echo "class='subtitle'"; ?>> Pick-up Reservation</a>					
                </div>

                <div class="profile_section">
                    <a href="drop_off.php" <?php if($current_filename=='clerk_main_menu.php') echo "class='subtitle'"; ?>> Drop-off Reservation</a>
                </div>	

                <div class="profile_section">
                    <a href="add_new_tool.php" <?php if($current_filename=='clerk_main_menu.php') echo "class='subtitle'"; ?>> Add New Tool</a>					
                </div>

                <div class="profile_section">
                    <a href="generate_reports.php" <?php if($current_filename=='clerk_main_menu.php') echo "class='subtitle'"; ?>> Generate Reports</a>                     
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