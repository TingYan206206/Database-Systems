<?php
include('lib/common.php');
// written by GTusername4

if (!isset($_SESSION['username'])) {
	header('Location: login.php');
	exit();
}

$cookie_name = "tool_number";
if(!isset($_COOKIE[$cookie_name])) {
    array_push($error_msg,'No tool selected!');
} else {
    $tool_number = $_COOKIE[$cookie_name];
}

$query =  "SELECT * FROM Tool WHERE tool_number=$tool_number;";
$result = mysqli_query($db, $query);
include('lib/show_queries.php');
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
if ($row['power_source'] == 'Manual') {
	$short_desc = $row['sub_option']." ".$row['sub_type']." ";
}
else {
	$short_desc = $row['power_source']." ".$row['sub_option']." ".$row['sub_type']." ";
}
$common_desc = $row['width_diameter']." in.W x ". $row['length']." in. L ".$row['weight']." lb. ".$short_desc;
$manufacturer = " by ".$row['manufacturer'];
$sub_type = strtolower(str_replace(' ', '',$row['sub_type']));
$type = strtolower(str_replace(' ', '', $row['type']));
$rental_price = $row['purchase_price']*0.15;
$deposit_price = $row['purchase_price']*0.4;
$query = "SELECT a.*, b.*, c.* ".
		 "FROM tool a ".
		 "JOIN $type b ".
		 "on a.tool_number = b.tool_number ".
		 "JOIN $sub_type c ".
		 "on a.tool_number = c.tool_number ".
		 "where a.tool_number = $tool_number;";
$result = mysqli_query($db, $query);
include('lib/show_queries.php');
$row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
// Hand tools     
if ($sub_type == "screwdriver") {
	$full_desc = $common_desc.$row['screw_size']." in. drive ".$manufacturer;
}
if ($sub_type == "socket") {
	$full_desc = $common_desc.$row['drive_size']." in. drive ".$row['sae_size']." in. sae ".$manufacturer;
}
if ($sub_type == "ratchet" or $sub_type == "wrench") {
	$full_desc = $common_desc.$row['drive_size']." in. drive ".$manufacturer;
}
if ($sub_type == "plier") {
	if ($row['adjustable']) {
		$full_desc = $common_desc."adjustable ".$manufacturer;
	}
	else {
		$full_desc = $common_desc."non adjustable ".$manufacturer;
	}
}
if ($sub_type == "gun") {
	$full_desc = $common_desc.$row['gauge_rating']." G ".$row['capacity']." number of nails/staples ".$manufacturer;
}
if ($sub_type == "hammer") {
	if ($row['anit_vibration']) {
		$full_desc = $common_desc."anti-vibration ".$manufacturer;
	}
	else {
		$full_desc = $common_desc."non anti-vibration ".$manufacturer;
	}
}
// garden tool
if ($sub_type == "pruner") {
	$full_desc = $common_desc." with ".$row['handle_material']." handle ".$row['blade_length']." in. ".$row['blade_material']." blade ".$manufacturer;
}
if ($sub_type == "striking") {
	$full_desc = $common_desc." with ".$row['handle_material']." handle ".$row['head_weight']." lb. head weigth ".$manufacturer;
}
if ($sub_type == "digger") {
	$full_desc = $common_desc." with ".$row['handle_material']." handle ".$row['blade_width']." in. W ".$row['blade-length']." in. L blade ".$manufacturer;
}
if ($sub_type == "rakes") {
	$full_desc = $common_desc." with ".$row['handle_material']." handle ".$row['tine_count']." tine ".$manufacturer;
}
if ($sub_type == "wheelbarrows") {
	$full_desc = $common_desc." with ".$row['handle_material']." handle ".$row['bin_material']." bin ".$row['bin_volume']." cu ft bin ".$row['wheel_count']." wheel ".$manufacturer;
}
// Ladder
if ($sub_type == "straight") {
	if ($row['rubber_feet']) {
		$full_desc = $common_desc.$row['step_count']."-step ".$row['weight_capacity']." lb.capacity "."rubber-feet".$manufacturer;
	}
	else {
		$full_desc = $common_desc.$row['step_count']."-step ".$row['weight_capacity']." lb.capacity "."non rubber-feet ".$manufacturer;
	}
}
if ($sub_type == "step") {
	if ($row['pail_shelf']) {
		$full_desc = $common_desc.$row['step_count']."-step ".$row['weight_capacity']." lb.capacity "."pail-shelf".$manufacturer;
	}
	else {
		$full_desc = $common_desc.$row['step_count']."-step ".$row['weight_capacity']." lb.capacity "."non pail-shelf ".$manufacturer;
	}
}
// Power Tool

if ($type = "powertool") {
	if ($row['max_rpm_rating']!=null) {
		$common_desc_powertool = $row['volt_rating']." V ".$row['amp_rating']." A ".$row['min_rpm_rating']." RPM single speed ";
	}
	else {
		$common_desc_powertool = $row['volt_rating']." V ".$row['amp_rating']." A ".$row['min_rpm_rating']." RPM and ".$row['max_rpm_rating']." RPM variable speed ";
	}
	
	if (strtolower($row['power_source'])=="cordless") {
		$query = "SELECT a.*, b.* ".
		 "FROM tool a ".
		 "JOIN cordlesspowertool b ".
		 "on a.tool_number = b.tool_number ".
		 "where a.tool_number = $tool_number;";
		$result_batt = mysqli_query($db, $query);
		include('lib/show_queries.php');
	}
	
	$query = "SELECT a.*, b.* ".
				  "FROM tool a ".
		 		  "JOIN accessory b ".
		    	  "on a.tool_number = b.tool_number ".
		  		  "where a.tool_number = $tool_number;";
	$result_acc = mysqli_query($db, $query);
	include('lib/show_queries.php');
	
}
if ($sub_type == "drill") {
	
	if ($row['adjustable_clutch']==true and $row['max_torque_rating']==null) {
		$full_desc = $common_desc.$common_desc_powertool."with adjustable clutch ".$row['min_torque_rating']." ft-lb single torque".$manufacturer;
	}
	if ($row['adjustable_clutch']==true and $row['max_torque_rating']!==null) {
		$full_desc = $common_desc.$common_desc_powertool."with adjustable clutch ".$row['min_torque_rating']." ft-lb variable torque".$manufacturer;
	}
	if ($row['adjustable_clutch']==false and $row['max_torque_rating']==null) {
		$full_desc = $common_desc.$common_desc_powertool."with non-adjustable clutch ".$row['min_torque_rating']." ft-lb single torque".$manufacturer;
	}
	
	if ($row['adjustable_clutch']==false and $row['max_torque_rating']!==null) {
		$full_desc = $common_desc.$common_desc_powertool."with non-adjustable clutch ".$row['min_torque_rating']." ft-lb variable torque".$manufacturer;
	}
	
}
if ($sub_type == "saw") {
	$full_desc = $common_desc.$common_desc_powertool.$row['blade_size']." in. blade ".$manufacturer;
}
if ($sub_type == "sander") {
	if ($row['dust_bag']) {
		$full_desc = $common_desc.$common_desc_powertool."with dust bag".$manufacturer;
	}
	else {
		$full_desc = $common_desc.$common_desc_powertool."without dust bag".$manufacturer;
	}
}
if ($sub_type == "aircompressor") {
	if ($row['pressure_rating']!=null) {
		$full_desc = $common_desc.$common_desc_powertool.$row['tank_size']." gal tank".$manufacturer;
	}
	else {
		$full_desc = $common_desc.$common_desc_powertool.$row['tank_size']." gal tank ".$row['pressure-rating']." psi".$manufacturer;
	}
}
if ($sub_type == "mixer") {
	$full_desc = $common_desc.$common_desc_powertool.$row['motor_rating']." HP ".$row['drum_size']." cu-ft drum".$manufacturer;
}
if ($sub_type == "genrator") {
	$full_desc = $common_desc.$common_desc_powertool.$row['power_rating']." Watt ".$manufacturer;
}
?>

<!DOCTYPE html>
<head>
<?php include("lib/header.php"); ?>
<title>Tool4Rent Tool Details</title>
</head>

<body>
  <div id="main_container">
    <div class="center_content">
        <div class="center_left">
            <div class="title_name">
                <?php print "Tool Details"; ?>
            </div>          
            <div class="features">   
                <div class="profile_section">  
                    <table>
                        <tr>
                            <td class="item_label">Tool ID</td>
                        </tr>
                        <tr>
                            <td ><?php print $tool_number;?></td>
                        </tr>
                        <tr>
                            <td class="item_label">Tool Type</td>
                        </tr>
                        <tr>
                            <td ><?php print $row['type'];?></td>
                        </tr>
                        <tr>
                            <td class="item_label">Short Description</td>
                        </tr>
                        <tr>
                            <td ><?php print $short_desc;?></td>
                        </tr>
                        <tr>
                            <td class="item_label">Full Description</td>
                        </tr>
                        <tr>
                            <td ><?php print $full_desc;?></td>
                        </tr>
                        <tr>
                            <td class="item_label">Deposit Price</td>
                        </tr>
                        <tr>
                            <td ><?php print "$".strval($deposit_price);?></td>
                        </tr>
                        <tr>
                            <td class="item_label">Rental Price</td>
                        </tr>
                        <tr>
                            <td ><?php print "$".strval($rental_price);?></td>
                        </tr>
                        <tr>
                            <td class="item_label">Accessories</td>
                        </tr>
                        <tr>
                            <td >
                            <?php 
                            if ($type != "powertool"){
                            	print "None";
                            }
                            else {
                            	if (isset($result_batt)) {
                            		while ($row = mysqli_fetch_array($result_batt, MYSQLI_ASSOC)){
                            			print "<li>"."(".$row['battery_quantity'].") ".$row['dc_volt_rating']." V ".$row['battery_type']." battery</li>";
                            		}
                            	}
                            	if (isset($result_acc)) {
                            		while ($row = mysqli_fetch_array($result_acc, MYSQLI_ASSOC)){
                            			print "<li>"."(".$row['quantity'].") ".$row['accessory_description']."</li>";
                            		}
                            	}                 	
                            }                            
                            ?>
                            </td>
                        </tr>                                                                                                        
                    </table>						
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