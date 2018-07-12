<?php
include('lib/common.php');
// written by GTusername1

if($showQueries){
  array_push($query_msg, "showQueries currently turned ON, to disable change to 'false' in lib/common.php");
}

//Note: known issue with _POST always empty using PHPStorm built-in web server: Use *AMP server instead
if( $_SERVER['REQUEST_METHOD'] == 'POST') {

	$enteredUsername = mysqli_real_escape_string($db, $_POST['username']);
	$enteredPassword = mysqli_real_escape_string($db, $_POST['password']);
    $selectedRole = mysqli_real_escape_string($db, $_POST['role']);

    if (empty($enteredUsername)) {
            array_push($error_msg,  "Please enter an email address.");
    }

	if (empty($enteredPassword)) {
			array_push($error_msg,  "Please enter a password.");
	}

    if (empty($selectedRole)) {
            array_push($error_msg,  "Please select a role.");
    }

    if ($selectedRole == 'Customer') {
        $query = "SELECT a.username, b.password FROM Customer a LEFT JOIN User b ON a.username = b.username WHERE a.username = '$enteredUsername';";
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');
        $count = mysqli_num_rows($result);
        if (!empty($result) && ($count > 0) ) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $storedPassword = $row['password']; 
            $options = [
                'cost' => 8,
            ];
            $storedHash = password_hash($storedPassword, PASSWORD_DEFAULT , $options);   //may not want this if $storedPassword are stored as hashes (don't rehash a hash)
            $enteredHash = password_hash($enteredPassword, PASSWORD_DEFAULT , $options); 
            if (password_verify($enteredPassword, $storedHash) ) {
                array_push($query_msg, "Password is Valid! ");
                $_SESSION['username'] = $enteredUsername;
                array_push($query_msg, "logging in... ");
                header(REFRESH_TIME . 'url=customer_main_menu.php');      //to view the password hashes and login success/failure
                
            } else {
                array_push($error_msg, "Login failed: " . $enteredUsername . NEWLINE);
                array_push($error_msg, "To demo enter: ". NEWLINE . "michael@bluthco.com". NEWLINE ."michael123");
            }
        }
        else {
            array_push($error_msg, "Username does not exist, go to registeration page");
            header(REFRESH_TIME . 'url=customer_registration.php');
        }
    }
    if ($selectedRole == 'Clerk') {
        $query = "SELECT a.username, a.is_first_time_login, b.password FROM Clerk a LEFT JOIN User b ON a.username = b.username WHERE a.username = '$enteredUsername';";
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');
        $count = mysqli_num_rows($result);
        if (!empty($result) && ($count > 0) ) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $storedPassword = $row['password'];
            $is_first_time_login = $row['is_first_time_login'];
            $options = [
                'cost' => 8,
            ];
            $storedHash = password_hash($storedPassword, PASSWORD_DEFAULT , $options);   //may not want this if $storedPassword are stored as hashes (don't rehash a hash)
            $enteredHash = password_hash($enteredPassword, PASSWORD_DEFAULT , $options); 
            if (password_verify($enteredPassword, $storedHash) and $is_first_time_login == false ) {
                array_push($query_msg, "Password is Valid! and is not first time login");
                $_SESSION['username'] = $enteredUsername;
                array_push($query_msg, "logging in... ");
                header(REFRESH_TIME . 'url=clerk_main_menu.php');      //to view the password hashes and login success/failure
                
            }
            elseif (password_verify($enteredPassword, $storedHash) and $is_first_time_login == true) {
                array_push($query_msg, "Password is Valid! and is first time login");
                $_SESSION['username'] = $enteredUsername;
                array_push($query_msg, "logging in... ");
                header(REFRESH_TIME . 'url=clerk_reset_password.php');      //to view the password hashes and login success/failure
             } 
            else {
                array_push($error_msg, "Login failed: " . $enteredUsername . NEWLINE);
                array_push($error_msg, "To demo enter: ". NEWLINE . "michael@bluthco.com". NEWLINE ."michael123");
            }
        }
        else {
            array_push($error_msg, "Username does not exist, please re-enter");
            header(REFRESH_TIME . 'url=login.php');
        }
    }    
	
}
?>

<?php include("lib/header.php"); ?>
<title>Tools4Rent Login</title>
</head>
<body>
    <div id="main_container">
        <div id="header">
            <div class="logo">
                <img src="img/Tool4Rent_logo.png" style="opacity:0.5;background-color:E9E5E2;" border="0" alt="" title="GT Online Logo"/>
            </div>
        </div>

        <div class="center_content">
            <div class="text_box">

                <form action="login.php" method="post" enctype="multipart/form-data">
                    <div class="title">Tools4Rent Login</div>
                    <div class="login_form_row">
                        <label class="login_label">Email:</label>
                        <input type="text" name="username" value="kmalone@gmail.com" class="login_input"/>
                    </div>
                    <div class="login_form_row">
                        <label class="login_label">Password:</label>
                        <input type="password" name="password" value="kmalone123" class="login_input"/>
                    </div>
                    <div class="login_form_row">
                        <label class="login_label"></label>
                        <input type="radio" name="role" value="Customer" > Customer
                        <input type="radio" name="role" value="Clerk"> Clerk <br>
                        <label class="login_label"></label>
                        <input type="submit" value="Sign in">
                    </div>
                    
                <form/>
            </div>

                <?php include("lib/error.php"); ?>

                <div class="clear"></div>
        </div>
   
            <!-- 
			<div class="map">
			<iframe style="position:relative;z-index:999;" width="820" height="600" src="https://maps.google.com/maps?q=801 Atlantic Drive, Atlanta - 30332&t=&z=14&ie=UTF8&iwloc=B&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"><a class="google-map-code" href="http://www.embedgooglemap.net" id="get-map-data">801 Atlantic Drive, Atlanta - 30332</a><style>#gmap_canvas img{max-width:none!important;background:none!important}</style></iframe>
			</div>
             -->
					<?php include("lib/footer.php"); ?>

        </div>
    </body>
</html>