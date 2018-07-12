<?php
include('lib/common.php');
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit();
}
//if ($_SERVER['REQUEST_METHOD'] == 'POST' and _POST['search']) {
if ($_POST['search']) {
    $entered_start_date = mysqli_real_escape_string($db, $_POST['start_date']);
    $entered_end_date = mysqli_real_escape_string($db, $_POST['end_date']);
    $entered_suboption = mysqli_real_escape_string($db, $_POST['keywords']);
    $entered_type = mysqli_real_escape_string($db, $_POST['masterType']);
    $entered_power_source = mysqli_real_escape_string($db, $_POST['powerSource']);
    $entered_subtype = mysqli_real_escape_string($db, $_POST['subType']);
    $entered_order = mysqli_real_escape_string($db, $_POST['group_order']);

    if (empty($entered_start_date) or !is_date($entered_start_date)) {
            array_push($error_msg,  "Please enter a valid starting date.");
    }

    if (empty($entered_end_date) or !is_date($entered_end_date)) {
      array_push($error_msg,  "Please enter a valid ending date");
    }
    
    $query =  "SELECT a.tool_number, a.sub_type, a.power_source, a.sub_option, a.purchase_price ".
              "FROM Tool a ".
              "WHERE a.tool_number not in ".
              "(SELECT distinct d.tool_number ".
              "FROM Tool d ".
              "LEFT JOIN ReservationDetail b ".
              "on d.tool_number = b.tool_number ".
              "LEFT JOIN Reservation c ".
              "on b.reservation_id = c.reservation_id ".
              "WHERE (CONVERT('$entered_end_date',DATE) > c.start_date and CONVERT('$entered_start_date', DATE)<c.end_date)) ".
              "AND (case when '$entered_type' != 'All' then a.type = '$entered_type' else a.type is not NULL end) ".
              "AND (case when '$entered_power_source'!= 'All' then a.power_source = '$entered_power_source' else a.power_source is not NULL end) ".
              "AND (case when '$entered_subtype'!= 'All' then a.sub_type = '$entered_subtype' else a.sub_type is not NULL end) ".
              "AND (case when '$entered_suboption' != '' then a.sub_option like '%$entered_suboption%' else a.sub_option is not NULL end) ".
              "ORDER BY $entered_order;";
        
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    // store start and end dates
    $cookie_name = "start_date";
    $cookie_value = $entered_start_date;
    setcookie($cookie_name, "", time() - 3600);
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
    $cookie_name = "end_date";
    $cookie_value = $entered_end_date;
    setcookie($cookie_name, "", time() - 3600);
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
}

if (!empty($_GET['tool_number'])) {
  $cookie_name = "tool_number";
  $cookie_value = $_GET['tool_number'];
  setcookie($cookie_name, "", time() - 3600);
  setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
  header('Refresh: 0.01; ' . 'url=tool_details.php');
}

if ($_POST['add_to_cart']) {
  if( isset($_POST['tool_number']) and is_array($_POST['tool_number']) ) {
    $tool_number_list = implode(', ', $_POST['tool_number']);
  }
  else{
    array_push($error_msg, "no tool number selected");
  }
  $num_selected_tools = count($_POST['tool_number']);
  if ($num_selected_tools > 10) {
    array_push($error_msg, "Please redo the search and select no more than 10 tools!");
  }
  $query = "SELECT * FROM Tool WHERE tool_number IN ($tool_number_list);";
  $result = mysqli_query($db, $query);
  include('lib/show_queries.php');
}

if ($_POST['calculate_total']) {
  if( isset($_POST['tool_number']) and is_array($_POST['tool_number']) ) {
    $tool_number_filtered = array_filter($_POST['tool_number']);
    $tool_number_list = implode(', ', $tool_number_filtered);
  }
  else{
    array_push($error_msg, "no tool number selected");
  }
  $cookie_name = "selected_tool_number";
  $cookie_value = $tool_number_list;
  setcookie($cookie_name, "", time() - 3600);
  setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
  header(REFRESH_TIME . 'url=reservation_summary.php');
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

<script>
    function myOperator(a, b, sign){
        if(sign == ">"){
            return a>b;
        }else if(sign == "<"){
            return a<b;
        }
    }

    function myCompare(x, y, sign){
        if(parseFloat(x) == x){
            return myOperator(parseFloat(x), parseFloat(y), sign);
        }else{
            if(x[0] == "$"){
                return myCompare(x.substring(1), y.substring(1), sign);
            }else{
                return myOperator(x.toLowerCase(), y.toLowerCase(), sign);
            }
        }
    }

    function sortTable(n,tableID) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById(tableID);
        switching = true;
        dir = "desc";
        while (switching) {
            switching = false;
            rows = table.getElementsByTagName("TR");
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                if (dir == "asc") {
                    if (myCompare(x.innerHTML, y.innerHTML, ">")) {
                        shouldSwitch= true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (myCompare(x.innerHTML, y.innerHTML, "<")) {
                        shouldSwitch= true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount ++;
            } else {
                if (switchcount == 0 && dir == "desc") {
                    dir = "asc";
                    switching = true;
                }
            }
        }
    }

</script>

<!DOCTYPE html>
<head>
  <?php include("lib/header.php"); ?>
  <title>Tool4Rent Reservation</title>
</head>
<style type="text/css">
tr td {
  white-space: nowrap;
}
input[type=text], input[type=number],select {
  width: 180px;
}
table {
  empty-cells: inherit;
}
</style>
<body>
  <div id="main_container">
    <?php include("lib/menu_customer.php"); ?>

    <div class="center_content">
      <div class="center_left">
        <div class="title_name"> <?php print "Make Reservation"; ?> </div>          
        <div class="features">   
          <div class="profile_section"> 
            <form name="reservation_search_form" action="make_reservation.php" method="post">
              <fieldset id="toolTypes">
                <table>
                  <tr>
                    <td class="item_label">Start Date </br>
                      <input type="text" name="start_date" value="<?php echo isset($_POST['start_date']) ? $_POST['start_date'] : '2017-11-07' ?>" />                    
                    </td>
                    <td class="item_label">End Date </br>
                      <input type="text" name="end_date" value="<?php echo isset($_POST['end_date']) ? $_POST['end_date'] : '2017-11-13' ?>" />                   
                    </td>
                    <td class="item_label">Custom Search </br>
                      <input type="text" name="keywords" value="<?php echo isset($_POST['keywords']) ? $_POST['keywords'] : '' ?>" />                   
                    </td>
                    <td class="item_label">Order By</br>
                      <select name="group_order">
                        <option value="tool_number" selected="true">Tool ID</option>
                        <option value="power_source">Description</option>
                        <option value="purchase_price">Rental Price</option>
                        <option value="purchase_price">Deposit Price</option>
                      </select>
                    </td>
                  </tr>
                </table>
                <table>
                  <tr>
                    <td>
                      <label for="masterType">Tool Type</label><br>
                      <select class="masterType" name="masterType" required></select><br>
                    </td>
                    <td>
                      <label for="powerSource">Power Source</label><br>
                      <select class="powerSource" name="powerSource" required></select><br>
                    </td>
                    <td>
                      <label for="subType">Sub-Type</label><br>
                      <select class="subType" name="subType" required></select><br>
                    </td>
                    <td> </br>
                      <input type="submit" name="search" value = "search"/>
                    </td>
                  </tr>
                </table> 
              </fieldset>
            </form>                      
          </div>
          <div class="profile_section"> 
            <div class="title_name"> <?php print "Available Tools For Rent"; ?> </div> 
            <form name="reservation_search_form" action="make_reservation.php" method="post">    
                <table id="myTable1" style="width:150%"> 
                          <tr>
                              <td class="heading" style="cursor: pointer" onclick="sortTable(0,'myTable1')">Tool ID</td>
                              <td class="heading" style="cursor: pointer" onclick="sortTable(2,'myTable1')">Description</td>
                              <td class="heading" style="cursor: pointer" onclick="sortTable(3,'myTable1')">Rental Price</td>
                              <td class="heading" style="cursor: pointer" onclick="sortTable(4,'myTable1')">Deposit Price</td>
                              <td class="heading"> Add</td>
                          </tr>             
                          <?php
                            if (isset($result) and ($_POST['search'])) {
                                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                if ($row['power_source'] == 'Manual') {
                                  $desc = $row['sub_option']." ".$row['sub_type'];
                                }
                                else {
                                  $desc = $row['power_source']." ".$row['sub_option']." ".$row['sub_type'];
                                }
                                $rental_price = $row['purchase_price']*0.15;
                                $deposit_price = $row['purchase_price']*0.4;                                
                                print "<tr>";
                                print "<td>{$row['tool_number']}</td>";
                                print "<td>";
                                print "<a href=". "\"make_reservation.php?tool_number=" . 
                                      urlencode($row['tool_number']) . "\"". "target=\"_blank\"". ">".$desc."</a>";
                                print "</td>";
                                print "<td hidden>".$desc."</td>";
                                print "<td>{$rental_price}</td>";
                                print "<td>{$deposit_price}</td>" ;
                                print "<td>";
                                print "<input type=\"checkbox\" name=\"tool_number[]\" value=".strval($row['tool_number']).">";
                                print "</td>";                
                                print "</tr>";
                              }
                            } ?>                                                  
                </table> 
                <table>
                  <tr>
                    <td> <input type="submit" name="add_to_cart" value = "Add to cart"/> </td>
                  </tr>
                </table> 
          </form>                   
          </div>
          <div class="profile_section"> 
            <div class="title_name"> <?php print "Tool Added to Reservation"; ?> </div>
            <form name="reservation_search_form" action="make_reservation.php" method="post">     
                <table id="myTable2" style="width:150%">                
                          <tr>
                              <td class="heading" style="cursor: pointer" onclick="sortTable(0,'myTable2')">Tool ID</td>
                              <td class="heading" style="cursor: pointer" onclick="sortTable(2,'myTable2')">Description</td>
                              <td class="heading" style="cursor: pointer" onclick="sortTable(3,'myTable2')">Rental Price</td>
                              <td class="heading" style="cursor: pointer" onclick="sortTable(4,'myTable2')">Deposit Price</td>
                              <td class="heading"> Remove</td>
                          </tr>
                          <?php
                            if (isset($result) and ($_POST['add_to_cart']) and ($num_selected_tools <= 10)) {
                                $x = 0;
                                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                if ($row['power_source'] == 'Manual') {
                                  $desc = $row['sub_option']." ".$row['sub_type'];
                                }
                                else {
                                  $desc = $row['power_source']." ".$row['sub_option']." ".$row['sub_type'];
                                }
                                $rental_price = $row['purchase_price']*0.15;
                                $deposit_price = $row['purchase_price']*0.4;                                
                                print "<tr>";
                                print "<td>{$row['tool_number']}</td>";
                                print "<td>";
                                print "<a href=". "\"make_reservation.php?tool_number=" . 
                                      urlencode($row['tool_number']) . "\"". "target=\"_blank\"". ">".$desc."</a>";
                                print "</td>";
                                echo "<td hidden>".$desc."</td>";
                                print "<td>{$rental_price}</td>";
                                print "<td>{$deposit_price}</td>" ;
                                print "<td>";
                                print "<input type=\"hidden\" name=\"tool_number[<?=$x?>]\" value=".strval($row['tool_number']).">";
                                print "<input type=\"checkbox\" name=\"tool_number[<?=$x?>]\" value=\"\" >";
                                print "</td>";                
                                print "</tr>";
                                $x = $x+1;
                              }
                            }
                            ?>                                     
                </table>  
                <table>
                  <tr>
                    <td> <input type="submit" name="calculate_total" value = "Calculate Total"/> </td>
                  </tr>              
                </table>
          </form>        
          </div>
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
    (function() {
      var tooltypes = [
      {'v': 'All','n':'All Tool', 's': [
      {'v': 'All','n':'All Power Source','s':[
      {'v':'All','n':'All Sub-type'}
      ]},
      {'v': 'electric','n':'A/C electric','s':[
      {'v':'All','n':'All Sub-type'},
      {'v':'drill','n':'Drill'},
      {'v':'saw','n':'Saw'},
      {'v':'sander','n':'Sander'},
      {'v':'air_compressor','n':'Air-Compressor'},
      {'v':'mixer','n':'Mixer'}
      ]},
      {'v': 'cordless','n':'D/C cordless','s':[
      {'v':'All','n':'All Sub-type'},
      {'v':'drill','n':'Drill'},
      {'v':'saw','n':'Saw'},
      {'v':'sander','n':'Sander'}
      ]},
      {'v': 'gas','n':'Gas-powered','s':[
      {'v':'All','n':'All Sub-type'},
      {'v':'air_compressor','n':'Air-Compressor'},
      {'v':'mixer','n':'Mixer'},
      {'v':'generator','n':'Generator'}
      ]},
      {'v': 'manual','n':'Manual','s':[
      {'v':'All','n':'All Sub-type'},
      {'v':'screwdriver','n':'Screwdriver'},
      {'v':'socket','n':'Socket'},
      {'v':'ratchet','n':'Ratchet'},
      {'v':'wrench','n':'Wrench'},
      {'v':'pliers','n':'Pliers'},
      {'v':'gun','n':'Gun'},
      {'v':'hammer','n':'Hammer'},
      {'v':'digger','n':'Digger'},
      {'v':'pruner','n':'Pruner'},
      {'v':'rakes','n':'Rakes'},
      {'v':'wheelbarrows','n':'Wheelbarrows'},
      {'v':'striking','n':'Striking'},
      {'v':'straight','n':'Straight'},
      {'v':'step','n':'Step'}        
      ]}
      ]},
      {'v': 'hand tool','n':'Hand Tool', 's': [
      {'v': 'manual','n':'Manual','s':[
      {'v':'All','n':'All Sub-type'},
      {'v':'screwdriver','n':'Screwdriver'},
      {'v':'socket','n':'Socket'},
      {'v':'ratchet','n':'Ratchet'},
      {'v':'wrench','n':'Wrench'},
      {'v':'pliers','n':'Pliers'},
      {'v':'gun','n':'Gun'},
      {'v':'hammer','n':'Hammer'}
      ]}
      ]},
      {'v': 'garden tool','n':'Garden Tool', 's': [
      {'v': 'manual','n':'Manual', 's':[
      {'v':'All','n':'All Sub-type'},
      {'v':'digger','n':'Digger'},
      {'v':'pruner','n':'Pruner'},
      {'v':'rakes','n':'Rakes'},
      {'v':'wheelbarrows','n':'Wheelbarrows'},
      {'v':'striking','n':'Striking'}
      ]}
      ]},
      {'v': 'ladder','n':'Ladder', 's': [
      {'v': 'manual','n':'Manual', 's': [
      {'v':'All','n':'All Sub-type'},
      {'v':'straight','n':'Straight'},
      {'v':'step','n':'Step'}
      ]}
      ]},
      {'v': 'power tool','n':'Power Tool', 's': [
      {'v': 'All','n':'All Power Source','s':[
      {'v':'All','n':'All Sub-type'},
      {'v':'drill','n':'Drill'},
      {'v':'saw','n':'Saw'},
      {'v':'sander','n':'Sander'},
      {'v':'air_compressor','n':'Air-Compressor'},
      {'v':'mixer','n':'Mixer'},
      {'v':'generator','n':'Generator'}
      ]},
      {'v': 'electric','n':'A/C electric', 's': [
      {'v':'All','n':'All Sub-type'},
      {'v':'drill','n':'Drill'},
      {'v':'saw','n':'Saw'},
      {'v':'sander','n':'Sander'},
      {'v':'air_compressor','n':'Air-Compressor'},
      {'v':'mixer','n':'Mixer'}
      ]},
      {'v': 'cordless','n':'D/C cordless', 's': [
      {'v':'All','n':'All Sub-type'},
      {'v':'drill','n':'Drill'},
      {'v':'saw','n':'Saw'},
      {'v':'sander','n':'Sander'}
      ]},
      {'v': 'gas','n':'Gas-powered', 's': [
      {'v':'All','n':'All Sub-type'},
      {'v':'air_compressor','n':'Air-Compressor'},
      {'v':'mixer','n':'Mixer'},
      {'v':'generator','n':'Generator'}
      ]}
      ]}
      ];

            // 自定义选项
            $('#toolTypes').cxSelect({
              selects: ['masterType', 'powerSource', 'subType'],
              required: true,
              jsonValue: 'v',
              data: tooltypes
            });

          })();
        </script>

  </body>
  </html>