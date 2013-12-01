<?php
	
	require_once('include/db.php');

	session_start();
	$title = 'View Log';
	$admin = true;

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
	<?php foreach($log as $item) { ?>
		<tr>
			<td> <?php echo $item['UID'] ?> </td> 
			<td> <?php echo $item['UName'] ?> </td> 
			<td> <?php echo $item['LTime'] ?> </td> 
			<td> <?php echo $item['IP'] ?> </td> 
			<td> <?php echo $item['MName'] ?> </td> 
	<?php } ?>
</table>

<?php include "include/footer.php"; ?>
