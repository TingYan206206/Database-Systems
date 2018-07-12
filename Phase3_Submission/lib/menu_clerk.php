
			<div id="header">
                <div class="logo"><img src="img/Tool4Rent_logo.png" style="opacity:0.6;background-color:E9E5E2;" border="0" alt="" title="GT Online Logo"/></div>
			</div>
			
			<div class="nav_bar">
				<ul>    
                    <li><a href="pick_up.php" <?php if($current_filename=='pick_up.php') echo "class='active'"; ?>>Pick Up</a></li>                       
					<li><a href="drop_off.php" <?php if($current_filename=='drop_off.php') echo "class='active'"; ?>>Drop-Off</a></li>  
                    <li><a href="add_new_tool.php" <?php if($current_filename=='add_new_tool.php') echo "class='active'"; ?>>Add New Tool</a></li>  
                    <li><a href="generate_reports.php" <?php if($current_filename=='generate_reports.php') echo "class='active'"; ?>>Reports</a></li>
                    <li><a href="logout.php" <span class='glyphicon glyphicon-log-out'></span> Log Out</a></li>
                    <li><a >Welcome <?php echo $_SESSION['username']; ?></a></li>

                </ul>
			</div>