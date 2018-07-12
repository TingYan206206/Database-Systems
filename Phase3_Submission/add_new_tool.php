<?php

include('lib/common.php');

if(!isset($_SESSION['username'])){
    header('Location: login.php');
    exit();
}

if($_SERVER['REQUEST_METHOD']=='POST'){
    $tool_type=mysqli_real_escape_string($db,$_POST['masterType']);
    if(empty($tool_type)){
        array_push($error_msg,"Select Tool Type.");
    }

    $sub_type=mysqli_real_escape_string($db,$_POST['subType']);
    if(empty($sub_type)){
        array_push($error_msg,"Select Sub-Type.");
    }
    $sub_option=mysqli_real_escape_string($db,$_POST['subOption']);
    if(empty($sub_option)){
        array_push($error_msg,"Select Sub-Option.");
    }

    $manufacturer=mysqli_real_escape_string($db,$_POST['manufacturer']);
    if(empty($manufacturer)){
        array_push($error_msg,"Enter manufacturer");
    }

    if($tool_type!='Power Tool'){
        $power_source='Manual';
    }else{
        $power_source=mysqli_real_escape_string($db,$_POST['power_source']);
        if(empty($power_source)){
            array_push($error_msg,"Select Power-Source.");
        }
    }

    $material=mysqli_real_escape_string($db,$_POST['material']);

    $current_status='for rent';

    $width_diameter=mysqli_real_escape_string($db,$_POST['width']);
    if(empty($_POST['width'])){
        array_push($error_msg,"Enter Width.");
    }

    $weight=mysqli_real_escape_string($db,$_POST['weight']);
    if(empty($_POST['weight'])){
        array_push($error_msg,"Enter Weight.");
    }

    $length=mysqli_real_escape_string($db,$_POST['length']);
    if(empty($_POST['length'])){
        array_push($error_msg,"Enter Length.");
    }

    $purchase_prise=(float)$_POST['purchase_price'];
    if(empty($purchase_prise)){
        array_push($error_msg,"Enter Purchase Price");
    }

    if(!empty($tool_type)&&!empty($sub_type)&&!empty($sub_option)&&!empty($power_source)&&!empty($_POST['width'])&&!empty($_POST['length'])&&!empty($purchase_prise)){
        $query="INSERT INTO Tool (type,sub_type,sub_option,manufacturer,power_source,material,current_status,width_diameter,length,weight,purchase_price,number_of_rent_time)".
            "VALUES('$tool_type','$sub_type','$sub_option','$manufacturer','$power_source','$material','$current_status','$width_diameter','$length','$weight','$purchase_prise',0)";

        $result=mysqli_query($db,$query);
        include('lib/show_queries.php');
    }else{
        exit("Incorrect Inputs");
    }

    $tool_number=mysqli_insert_id($db);

    if($tool_type=='Hand Tool'){
        $typeQuery="INSERT INTO HandTool (tool_number)".
            "VALUES('$tool_number')";
        if($sub_type=='Screwdriver'){
            $screw_size=mysqli_real_escape_string($db,$_POST['screw_size']);
            $subQuery="INSERT INTO Screwdriver (tool_number,screw_size)".
                "VALUES('$tool_number','$screw_size')";
        }else if($sub_type=='Socket'){
            $drive_size=mysqli_real_escape_string($db,$_POST['drive_size']);
            $sae_size=mysqli_real_escape_string($db,$_POST['sae_size']);
            $subQuery="INSERT INTO Socket (tool_number,drive_size,sae_size)".
                "VALUES('$tool_number','$drive_size','$sae_size')";
        }else if($sub_type=='Ratchet') {
            $drive_size = mysqli_real_escape_string($db, $_POST['drive_size']);
            $subQuery = "INSERT INTO Ratchet (tool_number,drive_size)" .
                "VALUES('$tool_number','$drive_size')";
        }else if($sub_type=='Wrench'){
                $drive_size=mysqli_real_escape_string($db,$_POST['drive_size_non_req']);
                $subQuery="INSERT INTO Wrench (tool_number,drive_size)".
                    "VALUES('$tool_number','$drive_size')";
        }else if($sub_type=='Plier'){
            $adjustable=$_POST['adjustable'];
            $subQuery="INSERT INTO Plier (tool_number,adjustable)".
                "VALUES('$tool_number','$adjustable')";
        }else if($sub_type=='Gun'){
            $capacity=(int)$_POST['capacity'];
            $gauge_rating=(int)$_POST['gauge_rating'];
            $subQuery="INSERT INTO Gun (tool_number,capacity,gauge_rating)".
                "VALUES('$tool_number','$capacity','$gauge_rating')";
        }else if($sub_type=='Hammer'){
            $anti_vibration=$_POST['anti_vibration'];
            $subQuery="INSERT INTO Hammer (tool_number,anti_vibration)".
                "VALUES('$tool_number','$anti_vibration')";
        }
    }else if($tool_type=='Garden Tool'){
        $handle_material=mysqli_real_escape_string($db,$_POST['handle_material']);
        $typeQuery="INSERT INTO GardenTool (tool_number,handle_material)".
            "VALUES('$tool_number','$handle_material')";
        if($sub_type=='Pruner'){
            $blade_length=mysqli_real_escape_string($db,$_POST['blade_length1']);
            $blade_material=mysqli_real_escape_string($db,$_POST['blade_material']);
            $subQuery="INSERT INTO Pruner (tool_number,blade_length,blade_material)".
                "VALUES('$tool_number','$blade_length','$blade_material')";
        }else if($sub_type=='Striking'){
            $head_weight=(float)$_POST['head_weight'];
            $subQuery="INSERT INTO Striking (tool_number,head_weight)".
                "VALUES('$tool_number','$head_weight')";
        }else if($sub_type=='Digger'){
            $blade_length=mysqli_real_escape_string($db,$_POST['blade_length2']);
            $blade_width=mysqli_real_escape_string($db,$_POST['blade_width']);
            $subQuery="INSERT INTO Digger (tool_number,blade_length,blade_width)".
                "VALUES('$tool_number','$blade_length','$blade_width')";
        }else if($sub_type=='Rakes'){
            $tine_count=(int)$_POST['tine_count'];
            $subQuery="INSERT INTO Rakes (tool_number,tine_count)".
                "VALUES('$tool_number','$tine_count')";
        }else if($sub_type=='Wheelbarrows'){
            $bin_material=mysqli_real_escape_string($db,$_POST['bin_material']);
            if($sub_option=='1-wheel'){
                $wheel_count='1';
            }else{
                $wheel_count='2';
            }
            $bin_volume=(float)$_POST['bin_volume'];
            $subQuery="INSERT INTO Wheelbarrows (tool_number,bin_material,wheel_count,bin_volume)".
                "VALUES('$tool_number','$bin_material','$wheel_count','$bin_volume')";
        }
    }else if($tool_type=='Ladder Tool'){
        $step_count=(int)$_POST['step_count'];
        $weight_capacity=(int)$_POST['weight_capacity'];
        $typeQuery="INSERT INTO Ladder (tool_number,step_count,weight_capacity)".
            "VALUES('$tool_number','$step_count','$weight_capacity')";
        if($sub_type=='Straight'){
            $rubber_feet=$_POST['rubber_feet'];
            $subQuery="INSERT INTO Straight (tool_number,rubber_feet)".
                "VALUES('$tool_number','$rubber_feet')";
        }else if($sub_type=='Step'){
            $pail_shelf=$_POST['pail_shelf'];
            $subQuery="INSERT INTO Step (tool_number,pail_shelf)".
                "VALUES('$tool_number','$pail_shelf')";
        }
    }else if($tool_type=='Power Tool'){
        $volt_rating=(int)$_POST['volt_rating'];
        $amp_rating=(float)$_POST['amp_rating'];
        $min_rpm_rating=(int)$_POST['min_rpm'];
        $max_rpm_rating=(int)$_POST['max_rpm'];
        $typeQuery="INSERT INTO PowerTool (tool_number,volt_rating,amp_rating,min_rpm_rating,max_rpm_rating)".
            "VALUES('$tool_number','$volt_rating','$amp_rating','$min_rpm_rating','$max_rpm_rating')";
        if($sub_type=='Drill'){
            $adjustable_clutch=$_POST['adjustable_clutch'];
            $min_torque_rating=(int)$_POST['min_torque'];
            $max_torque_rating=(int)$_POST['max_torque'];
            $subQuery="INSERT INTO Drill(tool_number,adjustable_clutch,min_torque_rating,max_torque_rating)".
                "VALUES('$tool_number','$adjustable_clutch','$min_torque_rating','$max_torque_rating')";
        }else if($sub_type=='Saw'){
            $blade_size=mysqli_real_escape_string($db,$_POST['blade_size']);
            $subQuery="INSERT INTO Saw (tool_number,blade_size)".
                "VALUES('$tool_number','$blade_size')";
        }else if($sub_type=='Sander'){
            $dust_bag=$_POST['dust_bag'];
            $subQuery="INSERT INTO Sander (tool_number,dust_bag)".
                "VALUES('$tool_number','$dust_bag')";
        }else if($sub_type=='Air Compressor'){
            $tank_size=(int)$_POST['tank_size'];
            $pressure_rating=(int)$_POST['pressure_rating'];
            $subQuery="INSERT INTO AirCompressor (tool_number,tank_size,pressure_rating)".
                "VALUES('$tool_number','$tank_size','$pressure_rating')";
        }else if($sub_type=='Mixer'){
            $drum_size=(int)$_POST['drum_size'];
            $motor_rating=mysqli_real_escape_string($db,$_POST['motor_rating']);
            $subQuery="INSERT INTO Mixer (tool_number,motor_rating,drum_size)".
                "VALUES('$tool_number','$motor_rating','$drum_size')";
        }else if($sub_type=='Generator'){
            $power_rating=(int)$_POST['power_rating'];
            $subQuery="INSERT INTO Generator (tool_number,power_rating)".
                "VALUES('$tool_number','$power_rating')";
        }

    }

    $typeResult=mysqli_query($db,$typeQuery);
    include('lib/show_queries.php');

    $subResult=mysqli_query($db,$subQuery);
    include('lib/show_queries.php');

    if($tool_type=='Power Tool'){
        $accessory_description=$_POST['majorType'].' '.$_POST['minorType'];
        $quantity=(int)$_POST['accessory_quantity'];
        $accessoryQuery="INSERT INTO Accessory (accessory_description,tool_number,quantity)".
            "VALUES('$accessory_description','$tool_number','$quantity')";

        if ($power_source=='Cordless'){
            $battery_type=mysqli_real_escape_string($db,$_POST['battery_type']);
            $battery_quantity=$quantity;
            $dc_volt_rating=$volt_rating;
            $batteryQuery="INSERT INTO CordlessPowerTool (tool_number,battery_type,battery_quantity,dc_volt_rating)".
                "VALUES('$tool_number','$battery_type','$battery_quantity','$dc_volt_rating')";
            $batteryResult=mysqli_query($db,$batteryQuery);
            include('lib/show_queries.php');
        }
        $accessoryResult=mysqli_query($db,$accessoryQuery);
        include('lib/show_queries.php');
    }

    if (mysqli_affected_rows($db) == -1) {
        array_push($error_msg, "UPDATE ERROR");
    }

    echo "<script>alert('Successfully Added Tool');</script>";
}


?>


<!DOCTYPE html>
<head>
    <?php include("lib/header.php"); ?>
	<title>Add New Tool</title>
</head>

<style type="text/css">
    input[type=text], input[type=number],select {
        width: 138px;
    }
</style>
<body>
    <div id="main_container">
        <?php include ("lib/menu_clerk.php"); ?>

        <div class="center_content">
                <div class="features">
                    <div class="profile_section">
                        <form name="add_tool_form" action="add_new_tool.php" method="post">
                            <fieldset id="toolTypes">
                                <div class="subtitle">Add Tool</div>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="masterType">Tool Type</label><br>
                                            <select class="masterType" name="masterType" id="masterType" required></select><br>
                                        </td>
                                        <td>
                                            <label for="subType">Sub-Type</label><br>
                                            <select class="subType" name="subType" id="subType" required></select><br>
                                        </td>
                                        <td>
                                            <label for="subOption">Sub-Option</label><br>
                                            <select class="subOption" name="subOption" id="subOption" required></select><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <label for="purchase_price">Purchase Price</label><br>
                                                <input type="number" name="purchase_price" id="purchase_price" step="any" required>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <label for="manufacturer">manufacturer</label><br>
                                                <input type="text" name="manufacturer" id="manufacturer" required>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <label for="material">Material</label><br>
                                                <input type="text" name="material" id="material">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <label for="width">Width</label><br>
                                                <input type="text" name="width" id="width" required>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <label for="weight">Weight(lbs)</label><br>
                                                <input type="text" name="weight" id="weight" required>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <label for="length">Length</label><br>
                                                <input type="text" name="length" id="length" required>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>

                            <fieldset id="handToolDetail" style="display: none;" disabled>
                                <div class="subtitle">Hand Tool Detail</div>
                                <table>
                                    <tr>
                                        <td>
                                            <div id="screwSize" style="display: none;">
                                                <label for="screw_size">Screw Size</label><br>
                                                <input type="text" name="screw_size" id="screw_size" step="1" disabled required>
                                            </div>
                                        </td>
                                        <td>
                                            <div id="saeSize" style="display:none;">
                                                <label for="sae_size">Sae Size</label><br>
                                                <input type="text" name="sae_size" id="sae_size" disabled required>
                                            </div>
                                        </td>
                                        <td>
                                            <div id="driveSize" style="display: none;">
                                                <label for="drive_size">Drive Size</label><br>
                                                <input type="text" name="drive_size" id="drive_size" disabled required>
                                            </div>
                                        </td>
                                        <td>
                                            <div id="driveSizeNonReq" style="display: none;">
                                                <label for="drive_size_non_req">Drive Size</label><br>
                                                <input type="text" name="drive_size_non_req" id="drive_size_non_req">
                                            </div>
                                        </td>
                                        <td>
                                            <div id="plierDetail" style="display: none;">
                                                <label for="adjustable">Adjustable</label><br>
                                                <select name="adjustable" id="adjustable" disabled required>
                                                    <option value="">-</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div id="gunDetail" style="display: none;">
                                                <label for="gauge_rating">Gauge Rating</label><br>
                                                <input type="number" name="gauge_rating" id="gauge_rating" step="1" disabled required><br>
                                                <label for="capacity">Capacity</label><br>
                                                <input type="number" name="capacity" id="capacity" step="1" disabled required>
                                            </div>
                                        </td>
                                        <td>
                                            <div id="hammerDetail" style="display: none;">
                                                <label for="anti_vibration">Anti Vibration</label><br>
                                                <select name="anti_vibration" id="anti_vibration">
                                                    <option value="">-</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>

                            <fieldset id="gardenToolDetail" style="display: none;" disabled>
                                <div class="subtitle">Garden Tool Detail</div>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="handle_material">Handle Material</label><br>
                                            <input type="text" name="handle_material" id="handle_material" required>
                                        </td>
                                        <td>
                                            <div id="prunerDetail" style="display: none;">
                                                <label for="blade_material">Blade Material</label><br>
                                                <input type="text" name="blade_material" id="blade_material"><br>
                                                <label for="blade_length1">Blade Length</label><br>
                                                <input type="text" name="blade_length1" id="blade_length1" disabled required>
                                            </div>
                                        </td>
                                        <td>
                                            <div id="strikingDetail" style="display: none;">
                                                <label for="head_weight">Head Weight</label><br>
                                                <input type="number" name="head_weight" id="head_weight" disabled required>
                                            </div>
                                        </td>
                                        <td>
                                            <div id="diggerDetail" style="display: none;">
                                                <label for="blade_width">Blade Width</label><br>
                                                <input type="text" name="blade_width" id="blade_width" disabled required><br>
                                                <label for="blade_length2">Blade Length</label><br>
                                                <input type="text" name="blade_length2" id="blade_length2" disabled required>
                                            </div>
                                        </td>
                                        <td>
                                            <div id="rakeDetail" style="display: none;">
                                                <label for="tine_count">Tine Count</label><br>
                                                <input type="number" name="tine_count" id="tine_count" step="1" disabled required>
                                            </div>
                                        </td>
                                        <td>
                                            <div id="wheelbarrowDetail" style="display: none;">
                                                <label for="bin_material">Bin Material</label><br>
                                                <input type="text" name="bin_material" id="bin_material" disabled required><br>
                                                <label for="bin_volume">Bin Volume</label><br>
                                                <input type="number" name="bin_volume" id="bin_volume">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>

                            <fieldset id="powerToolDetail" style="display: none;" disabled>
                                <div class="subtitle">Power Tools Only</div>
                                <table>
                                    <tr>
                                        <td>
                                            <div>
                                                <label for="power_source">Power Source</label><br>
                                                <select name="power_source" id="power_source" required>
                                                    <option value="">Please Select</option>
                                                    <option value="Gas">Gas</option>
                                                    <option value="Electric">Electric(AC)</option>
                                                    <option value="Cordless">Cordless(DC)</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <label for="volt_rating">Volt Rating</label><br>
                                                <select name="volt_rating" id="volt_rating" required>
                                                    <option value="">-</option>
                                                    <option value="110">110</option>
                                                    <option value="120">120</option>
                                                    <option value="220">220</option>
                                                    <option value="240">240</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <label for="amp_rating">Amp Rating</label><br>
                                                <input type="number" name="amp_rating" id="amp_rating" required>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <label for="min_rpm">Min RPM Rating</label><br>
                                                <input type="number" name="min_rpm" id="min_rpm" step="100" required>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <label for="max_rpm">Max RPM Rating</label><br>
                                                <input type="number" name="max_rpm" id="max_rpm" step="100">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="drillDetail" style="display: none;">
                                                <label for="adjustable_clutch">Adjustable Clutch</label><br>
                                                <select name="adjustable_clutch" id="adjustable_clutch">
                                                    <option value="">-</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                                <label for="min_torque">Min Torque Rating</label><br>
                                                <input type="number" name="min_torque" id="min_torque" disabled required><br>
                                                <label for="max_torque">Max Torque Rating</label><br>
                                                <input type="number" name="max_torque" id="max_torque">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="sawDetail" style="display: none;">
                                                <label for="blade_size">Blade Size</label><br>
                                                <input type="text" name="blade_size" id="blade_size" disabled required>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="sanderDetail" style="display: none;">
                                                <label for="dust_bag">Dust Bag</label><br>
                                                <select name="dust_bag" id="dust_bag" disabled required>
                                                    <option value="">-</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="airDetail" style="display: none;">
                                                <label for="tank_size">Tank Size</label><br>
                                                <input type="number" name="tank_size" id="tank_size" disabled required><br>
                                                <label for="pressure_rating">Pressure Rating</label><br>
                                                <input type="number" name="pressure_rating" id="pressure_rating">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="mixerDetail" style="display: none;">
                                                <label for="motor_rating">Motor Rating</label><br>
                                                <input type="text" name="motor_rating" id="motor_rating" disabled required><br>
                                                <label for="drum_size">Drum Size</label><br>
                                                <input type="number" name="drum_size" id="drum_size" disabled required>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="generatorDetail" style="display: none;">
                                                <label for="power_rating">Power Rating</label><br>
                                                <input type="number" name="power_rating" id="power_rating" disabled required>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <div class="subtitle">Power Tool Accessory</div>
                                <table>
                                    <tr>
                                        <td>
                                            <div>
                                                <label for="battery_type">Battery Type</label><br>
                                                <select name="battery_type" id="battery_type" disabled required>
                                                    <option value="">-</option>
                                                    <option value="Li-Ion">Li-Ion</option>
                                                    <option value="NiCd">NiCd</option>
                                                    <option value="NiMH">NiMH</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <label for="accessory_quantity">Accessory Quantity</label><br>
                                                <input type="number" name="accessory_quantity" id="accessory_quantity" step="1" required>
                                            </div>
                                        </td>
                                        <td>
                                            <div id="accessoryType">
                                                <label for="majorType">Accessory Type</label><br>
                                                <select class="majorType" name="majorType" id="majorType" required></select>
                                                <br>
                                                <label for="minorType">Sub-type</label><br>
                                                <select class="minorType" name="minorType" id="minorType" required></select>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>

                            <fieldset id="ladderToolDetail" style="display:none;" disabled>
                                <div class="subtitle">Ladder Tool Detail</div>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="step_count">Step Count</label><br>
                                            <input type="number" name="step_count" id="step_count" step="1" required><br>
                                            <label for="weight_capacity">Weight Capacity</label><br>
                                            <input type="number" name="weight_capacity" id="weight_capacity" required>
                                        </td>
                                        <td>
                                            <div id="straightDetail" style="display: none">
                                                <label for="rubber_feet">Rubber Feet</label><br>
                                                <select name="rubber_feet" id="rubber_feet">
                                                    <option value="">-</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div id="stepDetail" style="display: none">
                                                <label for="pail_shelf">Pail Shelf</label><br>
                                                <select name="pail_shelf" id="pail_shelf">
                                                    <option value="">-</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>

                            <input type="submit" name="add_tool"value="Add Tool"/>
                        </form>
                    </div>
                </div>

            <?php include("lib/error.php"); ?>
            <div class="clear"></div>
        </div>

        <?php include("lib/footer.php"); ?>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="js/jquery.cxselect.js"></script>
    <script>
        $(document).ready(function(){
            $('#masterType').on('change',function(){
                var selection = $(this).val();
                switch(selection){
                    case "Hand Tool":
                        $("#handToolDetail").show()
                        $("#handToolDetail").prop('disabled',false)
                        $("#gardenToolDetail").hide()
                        $("#gardenToolDetail").prop('disabled',true)
                        $("#powerToolDetail").hide()
                        $("#powerToolDetail").prop('disabled',true)
                        $("#ladderToolDetail").hide()
                        $("#ladderToolDetail").prop('disabled',true)
                        break;
                    case "Garden Tool":
                        $("#handToolDetail").hide()
                        $("#handToolDetail").prop('disabled',true)
                        $("#gardenToolDetail").show()
                        $("#gardenToolDetail").prop('disabled',false)
                        $("#powerToolDetail").hide()
                        $("#powerToolDetail").prop('disabled',true)
                        $("#ladderToolDetail").hide()
                        $("#ladderToolDetail").prop('disabled',true)
                        break;
                    case "Power Tool":
                        $("#handToolDetail").hide()
                        $("#handToolDetail").prop('disabled',true)
                        $("#gardenToolDetail").hide()
                        $("#gardenToolDetail").prop('disabled',true)
                        $("#powerToolDetail").show()
                        $("#powerToolDetail").prop('disabled',false)
                        $("#ladderToolDetail").hide()
                        $("#ladderToolDetail").prop('disabled',true)
                        break;
                    case "Ladder Tool":
                        $("#handToolDetail").hide()
                        $("#handToolDetail").prop('disabled',true)
                        $("#gardenToolDetail").hide()
                        $("#gardenToolDetail").prop('disabled',true)
                        $("#powerToolDetail").hide()
                        $("#powerToolDetail").prop('disabled',true)
                        $("#ladderToolDetail").show()
                        $("#ladderToolDetail").prop('disabled',false)
                        break;
                    default:
                        $("#handToolDetail").hide()
                        $("#handToolDetail").prop('disabled',true)
                        $("#gardenToolDetail").hide()
                        $("#gardenToolDetail").prop('disabled',true)
                        $("#powerToolDetail").hide()
                        $("#powerToolDetail").prop('disabled',true)
                        $("#ladderToolDetail").hide()
                        $("#ladderToolDetail").prop('disabled',true)
                }
            });

            $('#power_source').on('change',function(){
               var selection = $(this).val();
               switch(selection){
                   case "Cordless":
                       $("#subType option[value='Drill']").show();
                       $("#subType option[value='Saw']").show();
                       $("#subType option[value='Sander']").show();
                       $("#subType option[value='Air Compressor']").hide();
                       $("#subType option[value='Mixer']").hide();
                       $("#subType option[value='Generator']").hide();

                       $("#battery_type").prop('disabled',false)
                       break;
                   case "Electric":
                       $("#subType option[value='Air Compressor']").show();
                       $("#subType option[value='Mixer']").show();
                       $("#subType option[value='Drill']").show();
                       $("#subType option[value='Saw']").show();
                       $("#subType option[value='Sander']").show();
                       $("#subType option[value='Generator']").hide();

                       $("#battery_type").prop('disabled',true)
                       break;
                   case "Gas":
                       $("#subType option[value='Air Compressor']").show();
                       $("#subType option[value='Mixer']").show();
                       $("#subType option[value='Generator']").show();
                       $("#subType option[value='Drill']").hide();
                       $("#subType option[value='Saw']").hide();
                       $("#subType option[value='Sander']").hide();

                       $("#battery_type").prop('disabled',true)
                       break;
                   default:
//                       $("#subType option[value='Air Compressor']").show();
//                       $("#subType option[value='Mixer']").show();
//                       $("#subType option[value='Generator']").show();
//                       $("#subType option[value='Drill']").show();
//                       $("#subType option[value='Saw']").show();
//                       $("#subType option[value='Sander']").show();
               }
            });



            $('#subType').on('change',function () {
                var subselection = $(this).val();
                switch (subselection){
//          Hand Tool Section
                    case "Screwdriver":
                        $("#screwSize").show()
                        $("#screw_size").prop('disabled',false)

                        $("#saeSize").hide()
                        $("#sae_size").prop('disabled',true)

                        $("#driveSize").hide()
                        $("#drive_size").prop('disabled',true)

                        $("#driveSizeNonReq").hide()

                        $("#plierDetail").hide()
                        $("#adjustable").prop('disabled',true)

                        $("#gunDetail").hide()
                        $("#gauge_rating").prop('disabled',true)
                        $("#capacity").prop('disabled',true)

                        $("#hammerDetail").hide()
                        break;
                    case "Socket":
                        $("#screwSize").hide()
                        $("#screw_size").prop('disabled',true)

                        $("#saeSize").show()
                        $("#sae_size").prop('disabled',false)

                        $("#driveSize").show()
                        $("#drive_size").prop('disabled',false)

                        $("#driveSizeNonReq").hide()

                        $("#plierDetail").hide()
                        $("#adjustable").prop('disabled',true)

                        $("#gunDetail").hide()
                        $("#gauge_rating").prop('disabled',true)
                        $("#capacity").prop('disabled',true)

                        $("#hammerDetail").hide()
                        break;
                    case "Ratchet":
                        $("#screwSize").hide()
                        $("#screw_size").prop('disabled',true)

                        $("#saeSize").hide()
                        $("#sae_size").prop('disabled',true)

                        $("#driveSize").show()
                        $("#drive_size").prop('disabled',false)

                        $("#driveSizeNonReq").hide()

                        $("#plierDetail").hide()
                        $("#adjustable").prop('disabled',true)

                        $("#gunDetail").hide()
                        $("#gauge_rating").prop('disabled',true)
                        $("#capacity").prop('disabled',true)

                        $("#hammerDetail").hide()
                        break;
                    case "Wrench":
                        $("#screwSize").hide()
                        $("#screw_size").prop('disabled',true)

                        $("#saeSize").hide()
                        $("#sae_size").prop('disabled',true)

                        $("#driveSize").hide()
                        $("#drive_size").prop('disabled',true)

                        $("#driveSizeNonReq").show()

                        $("#plierDetail").hide()
                        $("#adjustable").prop('disabled',true)

                        $("#gunDetail").hide()
                        $("#gauge_rating").prop('disabled',true)
                        $("#capacity").prop('disabled',true)

                        $("#hammerDetail").hide()
                        break;
                    case "Plier":
                        $("#screwSize").hide()
                        $("#screw_size").prop('disabled',true)

                        $("#saeSize").hide()
                        $("#sae_size").prop('disabled',true)

                        $("#driveSize").hide()
                        $("#drive_size").prop('disabled',true)

                        $("#driveSizeNonReq").hide()

                        $("#plierDetail").show()
                        $("#adjustable").prop('disabled',false)

                        $("#gunDetail").hide()
                        $("#gauge_rating").prop('disabled',true)
                        $("#capacity").prop('disabled',true)

                        $("#hammerDetail").hide()
                        break;
                    case "Gun":
                        $("#screwSize").hide()
                        $("#screw_size").prop('disabled',true)

                        $("#saeSize").hide()
                        $("#sae_size").prop('disabled',true)

                        $("#driveSize").hide()
                        $("#drive_size").prop('disabled',true)

                        $("#driveSizeNonReq").hide()

                        $("#plierDetail").hide()
                        $("#adjustable").prop('disabled',true)

                        $("#gunDetail").show()
                        $("#gauge_rating").prop('disabled',false)
                        $("#capacity").prop('disabled',false)

                        $("#hammerDetail").hide()
                        break;
                    case "Hammer":
                        $("#screwSize").hide()
                        $("#screw_size").prop('disabled',true)

                        $("#saeSize").hide()
                        $("#sae_size").prop('disabled',true)

                        $("#driveSize").hide()
                        $("#drive_size").prop('disabled',true)

                        $("#plierDetail").hide()
                        $("#adjustable").prop('disabled',true)

                        $("#gunDetail").hide()
                        $("#gauge_rating").prop('disabled',true)
                        $("#capacity").prop('disabled',true)

                        $("#hammerDetail").show()
                        break;
//          Garden Tool section
                    case "Pruner":
                        $("#prunerDetail").show()
                        $("#blade_length1").prop('disabled',false)

                        $("#strikingDetail").hide()
                        $("#head_weight").prop('disabled',true)

                        $("#diggerDetail").hide()
                        $("#blade_width").prop('disabled',true)
                        $("#blade_length2").prop('disabled',true)

                        $("#rakeDetail").hide()
                        $("#tine_count").prop('disabled',true)

                        $("#wheelbarrowDetail").hide()
                        $("#bin_material").prop('disabled',true)
                        $("#bin_volume").prop('disabled',true)
                        break;
                    case "Striking":
                        $("#prunerDetail").hide()
                        $("#blade_length1").prop('disabled',true)

                        $("#strikingDetail").show()
                        $("#head_weight").prop('disabled',false)

                        $("#diggerDetail").hide()
                        $("#blade_width").prop('disabled',true)
                        $("#blade_length2").prop('disabled',true)

                        $("#rakeDetail").hide()
                        $("#tine_count").prop('disabled',true)

                        $("#wheelbarrowDetail").hide()
                        $("#bin_material").prop('disabled',true)
                        $("#bin_volume").prop('disabled',true)
                        break;
                    case "Digger":
                        $("#prunerDetail").hide()
                        $("#blade_length1").prop('disabled',true)

                        $("#strikingDetail").hide()
                        $("#head_weight").prop('disabled',true)

                        $("#diggerDetail").show()
                        $("#blade_width").prop('disabled',false)
                        $("#blade_length2").prop('disabled',false)

                        $("#rakeDetail").hide()
                        $("#tine_count").prop('disabled',true)

                        $("#wheelbarrowDetail").hide()
                        $("#bin_material").prop('disabled',true)
                        $("#bin_volume").prop('disabled',true)
                        break;
                    case "Rakes":
                        $("#prunerDetail").hide()
                        $("#blade_length1").prop('disabled',true)

                        $("#strikingDetail").hide()
                        $("#head_weight").prop('disabled',true)

                        $("#diggerDetail").hide()
                        $("#blade_width").prop('disabled',true)
                        $("#blade_length2").prop('disabled',true)

                        $("#rakeDetail").show()
                        $("#tine_count").prop('disabled',false)

                        $("#wheelbarrowDetail").hide()
                        $("#bin_material").prop('disabled',true)
                        $("#bin_volume").prop('disabled',true)
                        break;
                    case "Wheelbarrows":
                        $("#prunerDetail").hide()
                        $("#blade_length1").prop('disabled',true)

                        $("#strikingDetail").hide()
                        $("#head_weight").prop('disabled',true)

                        $("#diggerDetail").hide()
                        $("#blade_width").prop('disabled',true)
                        $("#blade_length2").prop('disabled',true)

                        $("#rakeDetail").hide()
                        $("#tine_count").prop('disabled',true)

                        $("#wheelbarrowDetail").show()
                        $("#bin_material").prop('disabled',false)
                        $("#bin_volume").prop('disabled',false)
                        break;
//          Ladder Tool section
                    case "Straight":
                        $("#straightDetail").show()

                        $("#stepDetail").hide()
                        break;
                    case "Step":
                        $("#straightDetail").hide()

                        $("#stepDetail").show()
                        break;
//          Power Tool section
                    case "Drill":
                        $("#drillDetail").show()
                        $("#min_torque").prop('disabled',false)

                        $("#sawDetail").hide()
                        $("#blade_size").prop('disabled',true)

                        $("#sanderDetail").hide()
                        $("#dust_bag").prop('disabled',true)

                        $("#airDetail").hide()
                        $("#tank_size").prop('disabled',true)

                        $("#mixerDetail").hide()
                        $("#motor_rating").prop('disabled',true)
                        $("#drum_size").prop('disabled',true)

                        $("#generatorDetail").hide()
                        $("#power_rating").prop('disabled',true)
                        break;
                    case "Saw":
                        $("#drillDetail").hide()
                        $("#min_torque").prop('disabled',true)

                        $("#sawDetail").show()
                        $("#blade_size").prop('disabled',false)

                        $("#sanderDetail").hide()
                        $("#dust_bag").prop('disabled',true)

                        $("#airDetail").hide()
                        $("#tank_size").prop('disabled',true)

                        $("#mixerDetail").hide()
                        $("#motor_rating").prop('disabled',true)
                        $("#drum_size").prop('disabled',true)

                        $("#generatorDetail").hide()
                        $("#power_rating").prop('disabled',true)
                        break;
                    case "Sander":
                        $("#drillDetail").hide()
                        $("#min_torque").prop('disabled',true)

                        $("#sawDetail").hide()
                        $("#blade_size").prop('disabled',true)

                        $("#sanderDetail").show()
                        $("#dust_bag").prop('disabled',false)

                        $("#airDetail").hide()
                        $("#tank_size").prop('disabled',true)

                        $("#mixerDetail").hide()
                        $("#motor_rating").prop('disabled',true)
                        $("#drum_size").prop('disabled',true)

                        $("#generatorDetail").hide()
                        $("#power_rating").prop('disabled',true)
                        break;
                    case "Air Compressor":
                        $("#drillDetail").hide()
                        $("#min_torque").prop('disabled',true)

                        $("#sawDetail").hide()
                        $("#blade_size").prop('disabled',true)

                        $("#sanderDetail").hide()
                        $("#dust_bag").prop('disabled',true)

                        $("#airDetail").show()
                        $("#tank_size").prop('disabled',false)

                        $("#mixerDetail").hide()
                        $("#motor_rating").prop('disabled',true)
                        $("#drum_size").prop('disabled',true)

                        $("#generatorDetail").hide()
                        $("#power_rating").prop('disabled',true)
                        break;
                    case "Mixer":
                        $("#drillDetail").hide()
                        $("#min_torque").prop('disabled',true)

                        $("#sawDetail").hide()
                        $("#blade_size").prop('disabled',true)

                        $("#sanderDetail").hide()
                        $("#dust_bag").prop('disabled',true)

                        $("#airDetail").hide()
                        $("#tank_size").prop('disabled',true)

                        $("#mixerDetail").show()
                        $("#motor_rating").prop('disabled',false)
                        $("#drum_size").prop('disabled',false)

                        $("#generatorDetail").hide()
                        $("#power_rating").prop('disabled',true)
                        break;
                    case "Generator":
                        $("#drillDetail").hide()
                        $("#min_torque").prop('disabled',true)

                        $("#sawDetail").hide()
                        $("#blade_size").prop('disabled',true)

                        $("#sanderDetail").hide()
                        $("#dust_bag").prop('disabled',true)

                        $("#airDetail").hide()
                        $("#tank_size").prop('disabled',true)

                        $("#mixerDetail").hide()
                        $("#motor_rating").prop('disabled',true)
                        $("#drum_size").prop('disabled',true)

                        $("#generatorDetail").show()
                        $("#power_rating").prop('disabled',false)
                        break;
                    default:

                }
            });

        });
    </script>

    <script>
        (function() {
            var tooltypes = [
                {'v':'Hand Tool','n':'Hand Tool', 's': [
                    {'v':'Screwdriver','n':'Screwdriver','s':[
                        {'v':'philips(cross)','n':'philips'},
                        {'v':'hex','n':'hex'},
                        {'v':'torx','n':'torx'},
                        {'v':'slotted(flat)','n':'slotted'}
                    ]},
                    {'v':'Socket','n':'Socket','s':[
                        {'v':'deep','n':'deep'},
                        {'v':'standard','n':'standard'}
                    ]},
                    {'v':'Ratchet','n':'Ratchet','s':[
                        {'v':'adjustable','n':'adjustable'},
                        {'v':'fixed','n':'fixed'}
                    ]},
                    {'v':'Wrench','n':'Wrench','s':[
                        {'v':'crescent','n':'crescent'},
                        {'v':'torque','n':'torque'},
                        {'v':'pipe','n':'pipe'}
                    ]},
                    {'v': 'Plier','n':'Plier','s':[
                        {'v':'needle nose','n':'needle nose'},
                        {'v':'cutting','n':'cutting'},
                        {'v':'crimper','n':'crimper'}
                    ]},
                    {'v': 'Gun','n':'Gun','s':[
                        {'v':'nail','n':'nail'},
                        {'v':'staple','n':'staple'}
                    ]},
                    {'v': 'Hammer','n':'Hammer','s':[
                        {'v':'claw','n':'claw'},
                        {'v':'sledge','n':'sledge'},
                        {'v':'farming','n':'farming'}
                    ]}
                ]},
                {'v':'Garden Tool','n':'Garden Tool', 's': [
                    {'v':'Digger','n':'Digger', 's':[
                        {'v':'pointed shovel','n':'pointed shovel'},
                        {'v':'flat shovel','n':'flat shovel'},
                        {'v':'scoop shovel','n':'scoop shovel'},
                        {'v':'edger','n':'edger'}
                    ]},
                    {'v': 'Pruner','n':'Pruner','s':[
                        {'v': 'sheer','n':'sheer'},
                        {'v': 'loppers','n':'loppers'},
                        {'v': 'hedge','n':'hedge'}
                    ]},
                    {'v': 'Rakes','n':'Rakes','s':[
                        {'v': 'leaf','n':'leaf'},
                        {'v': 'landscaping','n':'landscaping'},
                        {'v': 'rock','n':'rock'}
                    ]},
                    {'v': 'Wheelbarrows','n':'Wheelbarrows','s':[
                        {'v': '1-wheel','n':'1-wheel'},
                        {'v': '2-wheel','n':'2-wheel'}
                    ]},
                    {'v': 'Striking','n':'Striking', 's':[
                        {'v': 'bar pry','n':'bar pry'},
                        {'v': 'rubber mallet','n':'rubber mallet'},
                        {'v': 'tamper','n':'tamper'},
                        {'v': 'pick axe','n':'pick axe'},
                        {'v': 'single bit axe','n':'single bit axe'}
                    ]}
                ]},
                {'v':'Ladder Tool','n':'Ladder Tool', 's': [
                    {'v': 'Straight','n':'Straight', 's': [
                        {'v': 'rigid','n':'rigid'},
                        {'v': 'telescoping','n':'telescoping'}
                    ]},
                    {'v': 'Step','n':'Step', 's': [
                        {'v': 'folding','n':'folding'},
                        {'v': 'multi-position','n':'multi-position'}
                    ]}
                ]},
                {'v':'Power Tool','n':'Power Tool', 's': [
                    {'v': 'Drill','n':'Drill', 's': [
                        {'v': 'driver','n':'driver'},
                        {'v': 'hammer','n':'hammer'}
                    ]},
                    {'v': 'Saw','n':'Saw', 's': [
                        {'v': 'circular','n':'circular'},
                        {'v': 'reciprocating','n':'reciprocating'},
                        {'v': 'jig','n':'jig'}
                    ]},
                    {'v': 'Sander','n':'Sander', 's': [
                        {'v': 'finish','n':'finish'},
                        {'v': 'sheet','n':'sheet'},
                        {'v': 'belt','n':'belt'},
                        {'v': 'random orbital','n':'random orbital'}
                    ]},
                    {'v': 'Air Compressor','n':'Air Compressor', 's': [
                        {'v': 'reciprocating','n':'reciprocating'}
                    ]},
                    {'v': 'Mixer','n':'Mixer', 's': [
                        {'v': 'concrete','n':'concrete'}
                    ]},
                    {'v':'Generator','n':'Generator', 's': [
                        {'v':'electric','n':'electric'}
                    ]}
                ]}
            ];

            var accessorytypes=[
                {'v':'Drill Bits','n':'Drill Bits'},
                {'v':'Saw Blade','n':'Saw Blade'},
                {'v':'Soft Case','n':'Soft Case'},
                {'v':'Hard Case','n':'Hard Case'},
                {'v':'Batteries','n':'Batteries','s':[
                    {'v':'7.2V','n':'7.2V'},
                    {'v':'80V','n':'80V'}
                ]},
                {'v':'Battery Charger','n':'Battery Charger'},
                {'v':'Safety','n':'Safety','s':[
                    {'v':'hat','n':'hat'},
                    {'v':'pants','n':'pants'},
                    {'v':'goggles','n':'goggles'},
                    {'v':'vest','n':'vest'}
                ]},
                {'v':'Hose','n':'Hose'},
                {'v':'Gas Tank','n':'Gas Tank'}
            ];

            // 自定义选项
            $('#toolTypes').cxSelect({
                selects: ['masterType', 'subType', 'subOption'],
                jsonValue: 'v',
                data: tooltypes
            });

            $('#accessoryType').cxSelect({
                selects: ['majorType','minorType'],
                jsonValue: 'v',
                data: accessorytypes
            });

        })();
    </script>

</body>
</html>


