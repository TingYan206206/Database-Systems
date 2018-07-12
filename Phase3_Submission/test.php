
<?php
function pr( $result, $filed_name ) { 
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		print "<li>{$row[$filed_name]}</li>";
	}
} 
?>

<?php
include('lib/common.php');
?>

<html>
<body>
	<table>
		<tr>
			<td>
				<ul>
					<?php
					$query = "SELECT type FROM tool";
					$result = mysqli_query($db, $query);
					pr($result,'type');
					?>
				</ul>
			</td>
		</tr>
	</table>
</body>
</html>