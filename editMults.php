<?php
	
	session_start();
	$title = 'Edit Multipliers';

?>

<?php include "include/header.php"; ?>  
<form name="editCountryMults" action="editMults.php" method="post" accept-charset="utf-8">
	<ul> Country Multipliers <br>
		<table>
			<tr>
				<th> Country </th>
				<th> Multiplier</th>
			</tr>
			<tr>
				<td> <input type="text" name="country" placeholder="Country Name" required /> </td>
				<td> <input type="text" name="cmultiplier" placeholder="Multiplier" required /> </td>
			</tr>
		</table>
		
		<li><input type="submit" name="submitCountry" value="Edit Country Multipliers" /></li>
	</ul>
</form>
<br><br>
<form name="editPartMults" action="editMults.php" method="post" accept-charset="utf-8">
	<ul> Part Multipliers <br>
		<table>
			<tr>
				<th> Part </th>
				<th> Multiplier</th>
			</tr>
			<tr>
				<td> <input type="text" name="part" placeholder="Part Name" required /> </td>
				<td> <input type="text" name="pmultiplier" placeholder="Multiplier" required /> </td>
			</tr>
		</table>
		
		<li><input type="submit" name="submitCountry" value="Edit Country Multipliers" /></li>
	</ul>
</form>
<?php include "include/footer.php"; ?>