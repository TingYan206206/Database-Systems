<?php include('lib/common.php');
?>


<?php include("lib/header.php"); ?>
<?php include("lib/menu.php"); ?>

<?php
	include("lib/header.php");
?>
<html>
		<title>Generate reports</title>
</head>
<body>
      <div id="main_container">
				<?php include('lib/menu_clerk.php');?>
          <div class="center_content">
            <div class="reservation_summary_section">
              <div class="subtitle">Select a Report</div>
							<?php

								print '<td><a href="clerk_report.php">Clerk Report </a></td>';
								echo "<br>";
								echo "<br>";
								print '<td><a href="customer_report.php">Customer Report </a></td>';
								echo "<br>";
								echo "<br>";
								print '<td><a href="tool_inventory_report.php">Tool inventory Report </a></td>';
								echo "<br>";

								// print '<td><a href="reservation_detail.php?reservation_id=' . urlencode ($row['reservation_id']) . '">$id</a></td>';
								// print '<td><a href="view_requests.php?accept_request=' . urlencode($row['email']) . '">Accept</a></td>';
							?>
              <?php include("lib/footer.php"); ?>

              </div>
              </div>
              </div>
</body>
</html>
