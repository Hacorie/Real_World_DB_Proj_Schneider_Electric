<?php
	
	session_start();
	$title = 'View Log';

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
	<tr>
		<td>UID</td>
		<td>UName</td>
		<td>LTime</td>
		<td>IP</td>
		<td>MName</td>
	</tr>
</table>

<?php include "include/footer.php"; ?>