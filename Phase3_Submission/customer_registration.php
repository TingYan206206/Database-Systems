<?php

include('lib/common.php');
// written by GTusername4

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$first_name = mysqli_real_escape_string($db, $_POST['first_name']);
	$middle_name = mysqli_real_escape_string($db, $_POST['middle_name']);
	$last_name = mysqli_real_escape_string($db, $_POST['last_name']);
	$home_phone = mysqli_real_escape_string($db, $_POST['home_phone']);
	$work_phone = mysqli_real_escape_string($db, $_POST['work_phone']);
	$cell_phone = mysqli_real_escape_string($db, $_POST['cell_phone']);
	$primary_phone = mysqli_real_escape_string($db, $_POST['primary_phone']);
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$password = mysqli_real_escape_string($db, $_POST['password']);
	$re_type_password = mysqli_real_escape_string($db, $_POST['re_type_password']);
	$street_address = mysqli_real_escape_string($db, $_POST['street_address']);
	$city = mysqli_real_escape_string($db, $_POST['city']);
	$state = mysqli_real_escape_string($db, $_POST['state']);
	$zip_code = mysqli_real_escape_string($db, $_POST['zip_code']);
	$name_on_card = mysqli_real_escape_string($db, $_POST['name_on_card']);
	$card_number = mysqli_real_escape_string($db, $_POST['card_number']);  
	$exp_month = mysqli_real_escape_string($db, $_POST['exp_month']);
	$exp_year = mysqli_real_escape_string($db, $_POST['exp_year']);
	$cvc = mysqli_real_escape_string($db, $_POST['cvc']);

	$flag = true;
	if (empty($first_name)) {
		array_push($error_msg,  "Please enter your first name.");
		$flag = false;
	} 

	if (empty($middle_name)) {
		array_push($error_msg,  "Please enter your middle name");
		$flag = false;
	}

	if (empty($last_name)) {
		array_push($error_msg,  "Please enter your last name");
		$flag = false;
	}

	if (empty($home_phone) and empty($cell_phone) and empty($work_phone)) {
		array_push($error_msg,  "Please enter a phone number.");
		$flag = false;
	}

	if (!is_phone_num($home_phone) or !is_phone_num($cell_phone) or !is_phone_num($work_phone)) {
		array_push($error_msg,  "Please enter a valid phone number.");
		$flag = false;
	}

	if (empty($primary_phone)) {
		array_push($error_msg,  "Please select a primary phone number.");
		$flag = false;
	}

	if ($primary_phone == "home_phone" and empty($home_phone)) {
		array_push($error_msg,  "Please enter a home phone number as your primary phone.");
		$flag = false;
	}

	if ($primary_phone == "work_phone" and empty($work_phone)) {
		array_push($error_msg,  "Please enter a work phone number as your primary phone.");
		$flag = false;
	}

	if ($primary_phone == "cell_phone" and empty($cell_phone)) {
		array_push($error_msg,  "Please enter a cell phone number as your primary phone.");
		$flag = false;
	}

	if (empty($email)) {
		array_push($error_msg,  "Please enter an email address.");
		$flag = false;
	}

    if (empty($password)) {
        array_push($error_msg,  "Please enter a new password.");
        $flag = false;
    }

	if (empty($re_type_password)) {
		array_push($error_msg,  "Please retype the password.");
		$flag = false;
	}

	if ($password != $re_type_password) {
		array_push($error_msg, "The password does not match, please re-enter");
		$flag = false;
	}

	if (empty($name_on_card)) {
		array_push($error_msg,  "Please enter the name shown on the credit card");
		$flag = false;
	}

	if (empty($card_number)) {
		array_push($error_msg,  "Please enter credit card number.");
		$flag = false;
	} 

	if (empty($cvc)) {
		array_push($error_msg,  "Please enter a cvc of the credit card.");
		$flag = false;
	}
	if ($flag) {
		// update user table
		$query = "INSERT INTO user " .
				"(username, password, email, first_name, middle_name, last_name) " .
				"VALUES ('$email', '$password', '$email', '$first_name', '$middle_name', '$last_name');";
		$result = mysqli_query($db, $query);
		include('lib/show_queries.php');
		// update customer table
		$query = "INSERT INTO customer " .
				"(username, state, street_address, city, zip_code, total_reservations, total_tool_rented, card_number) " .
				"VALUES ('$email', '$state', '$street_address', '$city', $zip_code, 0, 0, '$card_number');";
		$result = mysqli_query($db, $query);
		include('lib/show_queries.php');
		// update credit card info
		$query = "INSERT INTO creditcard " .
				"(card_number, name, cvc, exp_year, exp_month) " .
				"VALUES ('$card_number', '$name_on_card', $cvc, $exp_year, $exp_month);";
		$result = mysqli_query($db, $query);
		include('lib/show_queries.php');
		// update phone info
		$pattern =	"/^[\(]?(\d{0,3})[\)]?[\s]?[\-]?(\d{3})[\s]?[\-]?(\d{4})[\s]?[x]?(\d*)$/";
		if (!empty($home_phone) and is_phone_num($home_phone)) {
			preg_match($pattern, $home_phone, $matches);
		    $area_code = $matches[1];    // 3-digit area code
		    $exchange = $matches[2];     // 3-digit exchange
		    $number = $matches[3];       // 4-digit number
		    $extension = $matches[4];    // extension
		    if ($primary_phone == "home_phone") {
		    	$is_primary = 1;
		    }
		    else {
		    	$is_primary = 0;
		    }
			$query = "INSERT INTO phone " .
					"(username, area_code, exchange,number, extension, phone_type, is_primary) " .
					"VALUES ('$email', $area_code, $exchange, $number, $extension, 'home_phone', $is_primary);";
			$result = mysqli_query($db, $query);
			include('lib/show_queries.php');			    
		}
		if (!empty($cell_phone) and is_phone_num($cell_phone)) {
			preg_match($pattern, $cell_phone, $matches);
		    $area_code = $matches[1];    // 3-digit area code
		    $exchange = $matches[2];     // 3-digit exchange
		    $number = $matches[3];       // 4-digit number
		    $extension = $matches[4];    // extension
		    if ($primary_phone == "cell_phone") {
		    	$is_primary = 1;
		    }
		    else {
		    	$is_primary = 0;
		    }
			$query = "INSERT INTO phone " .
					"(username, area_code, exchange,number, extension, phone_type, is_primary) " .
					"VALUES ('$email', $area_code, $exchange, $number, $extension, 'cell_phone', $is_primary);";
			$result = mysqli_query($db, $query);
			include('lib/show_queries.php');			    
		}		

		if (!empty($work_phone) and is_phone_num($work_phone)) {
			preg_match($pattern, $work_phone, $matches);
		    $area_code = $matches[1];    // 3-digit area code
		    $exchange = $matches[2];     // 3-digit exchange
		    $number = $matches[3];       // 4-digit number
		    $extension = $matches[4];    // extension
		    if ($primary_phone == "work_phone") {
		    	$is_primary = 1;
		    }
		    else {
		    	$is_primary = 0;
		    }
			$query = "INSERT INTO phone " .
					"(username, area_code, exchange,number, extension, phone_type, is_primary) " .
					"VALUES ('$email', $area_code, $exchange, $number, $extension, 'work_phone', $is_primary);";
			$result = mysqli_query($db, $query);
			include('lib/show_queries.php');			    
		}
	}					
}

function is_phone_num($phone) {
	$pattern =	"/^[\(]?(\d{0,3})[\)]?[\s]?[\-]?(\d{3})[\s]?[\-]?(\d{4})[\s]?[x]?(\d*)$/";
	if(preg_match($pattern, $phone)) {
	  return true;
	}
	else {
		return false;
	}
}

function is_date( $str ) { 
	$stamp = strtotime( $str ); 
	if (!is_numeric($stamp)) { 
		return false; 
	} 
	$month = date( 'm', $stamp ); 
	$day   = date( 'd', $stamp ); 
	$year  = date( 'Y', $stamp ); 

	if (checkdate($month, $day, $year)) { 
		return true; 
	} 
	return false; 
} 

?>

<?php include("lib/header.php"); ?>
<title>Tool4Rent Registration</title>
</head>

<body>
	<div id="main_container">
		<div id="header">
			<div class="logo"><img src="img/Tool4Rent_logo.png" style="opacity:0.6;background-color:E9E5E2;" border="0" alt="" title="GT Online Logo"/></div>
		</div>

		<div class="nav_bar">
			<ul>    
				<li><a href="logout.php" <span class='glyphicon glyphicon-log-out'></span> Cancel</a></li>             
			</ul>
		</div>
		<div class="center_content">	
			<div class="center_left">
				<div class="title_name"><?php print "Customer Registration Form"; ?></div>          
				<div class="features">   

					<div class="profile_section">

						<form name="registrationform" action="customer_registration.php" method="post">
							<table>
								<tr>
									<td class="item_label">First Name</td>
									<td>
										<input type="text" name="first_name" value="" />										
									</td>
								</tr>
								<tr>
									<td class="item_label">Middle Name</td>
									<td>
										<input type="text" name="middle_name" value="" />										
									</td>
								</tr>
								<tr>
									<td class="item_label">Last Name</td>
									<td>
										<input type="text" name="last_name" value="" />										
									</td>
								</tr>
								<tr>
									<td class="item_label">Home Phone</td>
									<td>
										<input type="text" name="home_phone" value="(123)456-7890x123" />										
									</td>
								</tr>
								<tr>
									<td class="item_label">Work Phone</td>
									<td>
										<input type="text" name="work_phone" value="(123)456-7890x123" />										
									</td>
								</tr>
								<tr>
									<td class="item_label">Cell Phone</td>
									<td>
										<input type="text" name="cell_phone" value="(123)456-7890x123" />										
									</td>
								</tr>
								<tr>
									<td class="item_label">Primary Phone</td>
									<td>
										<input type="radio" name="primary_phone" value="home_phone" > Home
										<input type="radio" name="primary_phone" value="work_phone" > Work
										<input type="radio" name="primary_phone" value="cell_phone"> Cell
									</td>
								</tr>
								<tr>
									<td class="item_label">Email Address</td>
									<td>
										<input type="text" name="email" value="" />										
									</td>
								</tr>
								<tr>
									<td class="item_label">Password</td>
									<td>
										<input type="text" name="password" value="" />										
									</td>
								</tr>
								<tr>
									<td class="item_label">Re-type Password</td>
									<td>
										<input type="text" name="re_type_password" value="" />										
									</td>
								</tr>
								<tr>
									<td class="item_label">Street Address</td>
									<td>
										<input type="text" name="street_address" value="" />	
									</td>
								</tr>
								<tr>
									<td class="item_label">City</td>
									<td>
										<input type="text" name="city" value="" />	
									</td>
								</tr>
								<tr>
									<td class="item_label">State</td>
									<td>
										<input type="text" name="state" value="" />	
									</td>
								</tr>
								<tr>
									<td class="item_label">Zip Code</td>
									<td>
										<input type="text" name="zip_code" value="" />	
									</td>
								</tr>

							</table>
							<div class="subtitle">Credit Card</div>  
							<table>
								<tr>
									<td class="item_label">Name on Card</td>
									<td>
										<input type="text" name="name_on_card" value="" />	
									</td>
								</tr>
								<tr>
									<td class="item_label">Credit card #</td>
									<td>
										<input type="text" name="card_number" value="" />	
									</td>
								</tr>
								<tr>
									<td class="item_label">Expiration Month</td>
									<td>
										<select name="exp_month">
											<option value="01">01</option>
											<option value="02">02</option>
											<option value="03">03</option>
											<option value="04">04</option>
											<option value="05">05</option>
											<option value="06">06</option>
											<option value="07">07</option>
											<option value="08">08</option>
											<option value="09">09</option>
											<option value="10">10</option>
											<option value="11">11</option>
											<option value="12">12</option>																						
										</select>
									</td>
								</tr>
								<tr>
									<td class="item_label">Expiration Year</td>
									<td>
										<select name="exp_year">
											<option value="2017">2017</option>
											<option value="2018">2018</option>
											<option value="2019">2019</option>
											<option value="2020">2020</option>
											<option value="2021">2021</option>
											<option value="2022">2022</option>
											<option value="2023">2023</option>
											<option value="2024">2024</option>
											<option value="2025">2025</option>																			
										</select>
									</td>
								</tr>																
								<tr>
									<td class="item_label">cvc</td>
									<td>
										<input type="text" name="cvc" value="" />	
									</td>
								</tr>																
							</table>
							<a href="javascript:registrationform.submit();" class="fancy_button">Register</a> 													
						</form>
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