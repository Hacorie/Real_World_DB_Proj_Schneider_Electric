<?php
	
	function dbConnect() {
		$db = new mysqli("mysql.cs.mtsu.edu", "ncr2g", "donthackmebro", "ncr2g");
			
		if ($db->connect_errno) {
			echo "Failed to connect to MySQL: " . $db->connect_error;
			exit(1);
		}

		return $db;
	}

	session_start();
	$title = 'View Log';

	$db = dbConnect();

	// Put log into a list
	$res = $db->query('SELECT * from Log');

	$log = Array();
	while ($row = $res->fetch_assoc()) {
		$log[] = $row;
	}

	$errMsg = Array();

?>

<?php include "include/header.php"; ?>  
<table border="1">
	<tr>
		<th> ID </th>
		<th> User Name </th>
		<th> Login Date/Time </th>
		<th> IP Address </th>
		<th> Machine Name </th>
	</tr>
	<?php foreach($groups as $group) { ?>
		<tr>
			<td> <?php echo $group['UID'] ?> </td> 
			<td> <?php echo $group['UName'] ?> </td> 
			<td> <?php echo $group['LTime'] ?> </td> 
			<td> <?php echo $group['IP'] ?> </td> 
			<td> <?php echo $group['MName'] ?> </td> 
	<?php } ?>
	<tr>
		<td>UID</td>
		<td>UName</td>
		<td>LTime</td>
		<td>IP</td>
		<td>MName</td>
	</tr>
</table>

<?php include "include/footer.php"; ?>