<?php
include('lib/common.php');
// written by GTusername1
if (!isset($_SESSION['username'])) {
	header('Location: login.php');
	exit();
}

//Note: known issue with _POST always empty using PHPStorm built-in web server: Use *AMP server instead
if( $_SERVER['REQUEST_METHOD'] == 'POST') {

	$entered_new_password = mysqli_real_escape_string($db, $_POST['new_password']);
	$entered_retype_password = mysqli_real_escape_string($db, $_POST['retype_password']);

    if (empty($entered_new_password)) {
            array_push($error_msg,  "Please enter a new password.");
    }

	if (empty($entered_retype_password)) {
			array_push($error_msg,  "Please retype the password.");
	}
	if ($entered_new_password == $entered_retype_password) {
		$query = "UPDATE User SET password = '$entered_new_password' WHERE username = '{$_SESSION['username']}'";
		$queryID = mysqli_query($db, $query);
		if ($queryID  == False) {
                 array_push($error_msg, "INSERT ERROR: Update Clerk Password"."<br>". __FILE__ ." line:". __LINE__ );
        }
        $query = "UPDATE Clerk SET is_first_time_login = false WHERE username = '{$_SESSION['username']}'";
		$queryID = mysqli_query($db, $query); 
		if ($queryID  == False) {
                 array_push($error_msg, "INSERT ERROR: Update Clerk Password"."<br>". __FILE__ ." line:". __LINE__ );
        }
        header(REFRESH_TIME . 'url=clerk_main_menu.php');
	}
	else {
		array_push($error_msg, "The password does not match, please re-enter");
		//header(REFRESH_TIME . 'url=clerk_reset_password.php');
	}   
	
}
?>123456

<?php include("lib/header.php"); ?>
		<title>Tool4Rent Clerk First Login</title>
	</head>
	<style type="text/css">
	tr td {
	  white-space: nowrap;
	}
	input[type=text], input[type=number],select {
	  width: 190px;
	}
	table {
	  empty-cells: inherit;
	}
	</style>
	<body>
    	<div id="main_container">
    	<div id="header">
                <div class="logo"><img src="img/Tool4Rent_logo.png" style="opacity:0.6;background-color:E9E5E2;" border="0" alt="" title="Tools4Rent Logo"/></div>
		</div>
		<div class="nav_bar">
			<ul>
                <li><a href="logout.php" <span class='glyphicon glyphicon-log-out'></span> Log Out</a></li>             
			</ul>
		</div>		
			
        <div class="center_content">
            <div class="text_box">

                <form action="clerk_reset_password.php" method="post" enctype="multipart/form-data">
                    <div class="title">Reset Password</div>
                    <div class="login_form_row">
                        <label class="login_label">New Password:</label>
                        <input type="password" name="new_password" value="********" class="login_input"/>
                    </div>
                    <div class="login_form_row">
                        <label class="login_label">Retype Password:</label>
                        <input type="password" name="retype_password" value="********" class="login_input"/>
                    </div>
                    <div class="login_form_row">
                        <label class="login_label"></label>
                        <input type="submit" value="Change Password">
                    </div>
                    
                <form/>
            </div>

                <?php include("lib/error.php"); ?>

                <div class="clear"></div>
        </div>
                
                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 
			</div>    
            
               <?php include("lib/footer.php"); ?>
		 
		</div>
	</body>
</html>