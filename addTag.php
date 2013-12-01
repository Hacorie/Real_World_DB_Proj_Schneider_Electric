<?php
		
	require_once('include/db.php');

	session_start();
	$title = 'Add Tag';

	if (isset($_POST['submit'])) {

		$db = dbConnect();

		// Add an entry to the log_in table
		$sql = "INSERT INTO Tag(Revision, LeadTime, CreationDate, Description, TagNotes, 
				PriceNotes, PriceExpire, MaterialCost, LaborCost, EngineeringCost, 
				InstallCost, Subcategory, Complexity, Owner)
				VALUES (1, ?, CURRENT_DATE, ?, ?, ?, ADDDATE(CURRENT_DATE, INTERVAL ? MONTH), ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $db->prepare($sql);
		$totalCost = $_POST['mCost'] + $_POST['labor'] + $_POST['engineering'];
		$stmt->bind_param("isssiddddsss", 
			$_POST['leadTime'],
			$_POST['desc'],
			$_POST['tagNotes'],
			$_POST['priceNotes'],
			$_POST['priceExpiration'],
			$_POST['mCost'],
			$_POST['labor'],
			$_POST['engineering'],
			$totalCost,
			$_POST['sCategory'],
			$_POST['complexity'],
			$_SESSION['username']);

		$stmt->execute();

		if ($db->affected_rows == 1) {
			// Success
			
		} else {
			$errMsg[] = 'Error adding Tag';
			$errMsg[] = $db->error;
		}

		$stmt->close();

	}


?>

<?php include "include/header.php"; ?>  
<form name="addtag" action="addTag.php" method="post" accept-charset="utf-8">
	<ul>
		<li> Description: <input type="text" name="desc" placeholder="Tag Description" required /></li>
		<li> Tag Notes: <input type="text" name="tagNotes" placeholder="Tag Notes" required /></li>
		<li> Price Notes: <input type="text" name="priceNotes" placeholder="Price Notes" required /></li>
		<li> Sub Category: <input type="text" name="sCategory" placeholder="Sub Category Name (pull from list of sub categories in DB)" required /></li>
		<li> Material Cost: <input type="text" name="mCost" placeholder="Cost of Materials" required /></li>
		<li> Labor Hours: <input type="text" name="labor" placeholder="Hours of Labor" required /></li>
		<li> Engineering Hours: <input type="text" name="engineering" placeholder="Hours of Engineering" required /></li>
		<li> Price Expiration Date: <input type="text" name="priceExpiration" placeholder="Price Expiration mm/dd/yyyy" required /></li>
		<li> Lead Time: <input type="text" name="leadTime" placeholder="Lead Time" required /></li>
		<li> Complexity: <input type="text" name="complexity" placeholder="Drop Down for Complexities" required /></li>
		<li> Attachments: will get back to this</li>
		
		<li><input type="submit" name="submit" value="Create Tag" /></li>
	</ul>
</form>
<?php include "include/footer.php"; ?>