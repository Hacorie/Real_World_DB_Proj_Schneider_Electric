<?php
		
	require_once('include/db.php');

	session_start();
	gateway(2);
	$title = 'Add / Insert a Tag';

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

    $db = dbConnect(); 
    $complexity = dbQuery($db, 'SELECT CName FROM Complexity');
    $subCategory = dbQuery($db, 'SELECT SName FROM Subcategory');
    

?>

<?php include "include/header.php"; ?> 

<div class="page-header">
	<h1>Add / Insert a Tag</h1>
</div> 
<form name="addtag" action="addTag.php" method="post" accept-charset="utf-8">
	<div id="section_wrapper">
	<div id="section1">
	<table id="addTagDiv">
	<tr>
		<td width=10%>Tag #</td>
		<td width=8%>Rev # </td>
		<td width=13%>Date </td>
		<td>Sub-Category</td>
		<td>Complexity</td>
		<td>Lead Time</td>
	</tr>
	<tr>
		<td><input id="addTag_tagNum" type="text" name="tagNum" placeholder="XX-XXXX" required /></td>
		<td><input id="addTag_rev" type="text" name="rev" placeholder="1" content="1" required /></td>
		<td><input id="addTag_date" type="text" name="date" placeholder="##/##/####" required /></td>
        <td>
            <select id="addTag_sCategory" name="sCategory">
                <?php foreach($subCategory as $category) { ?>
                    <option value="<?php echo $category['SName'] ?>" > <?php echo $category['SName'] ?> </option>
                <?php } ?>
            </select>
        </td>
        <td>
            <select id="addTag_complexity" name="complexity">
                <?php foreach($complexity as $item) { ?>
                    <option value="<?php echo $item['CName'] ?>" > <?php echo $item['CName'] ?> </option>
                <?php } ?>
            </select>
        </td>
		<!--<td><input id="addTag_sCategory" type="text" name="sCategory" placeholder="Sub Category Name (pull from list of sub categories in DB)" required /></td>
		<td><input id="addTag_complexity" type="text" name="complexity" placeholder="Drop Down for Complexities" required /></td>-->
		<td><input id="addTag_leadTime"type="text" name="leadTime" placeholder="Lead Time" required /></td>
	</tr>
	</table>
	<table id="tagTable">
	<tr>
		<td>Tag Description:</td>
	</tr>
	<tr>
		<td ><input id="tagDescCell" type="text" name="desc" placeholder="Enter a Tag Description" required /></td>
	</tr>
	</table>
	<table id="tagTable">
	<tr>
		<td>Tag Notes:</td>
	</tr>
	<tr>
		<td ><input id="tagDescCell"type="text" name="tagNotes" placeholder="Enter Tag Notes" required /></td>
	</tr>
	</table>
	<table id="tagTable">
	<tr>
		<td>Price Note:</td>
	</tr>
	<tr>
		<td ><input id="tagDescCell" type="text" name="priceNotes" placeholder="Enter Price Notes" required /></td>
	</tr>
	</table>
	</div>

	<div id="section3">
		<strong><i>Pricing Information</i></strong>
	<table id="pricingTable">
		<tr>
			<td>Material:</td>
			<td><input type="text" placeholder="$X.XX" /></td>
		</tr>
		<tr>
			<td>Labor:</td>
			<td><input type="text" placeholder="$X.XX" /></td>
		</tr>
		<tr style="border-bottom: 1px solid #000;">
			<td>Engineering:</td>
			<td><input type="text" placeholder="$X.XX" /></td>
		</tr>
		<tr>
			<td>Initial Cost:</td>
			<td><input type="text" placeholder="$SUM" /></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td>TAG Member:</td>
			<td><input type="text" placeholder="Name" /></td>
		</tr>
		<tr>
			<td>Price Expires:</td>
			<td><input type="text" placeholder="##/##/####" /></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
	</table>
	<br />
	<button class="btn btn-success" id="attachmentButton">Save</button><br /><br />
	<hr style="clear: both"/>
	</div>	
	<div id="section2">
		<strong>Product Lines Tag May be Applied To:</strong>
	<table width=60%>
		<tr>
			<td></td>
			<td>USA$</td>
			<td>Canada$</td>
			<td>Mexico$</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="vehicle" value="HVL" />HVL</td>
			<td><input type="text" placeholder="$X.XX" /></td>
			<td><input type="text" placeholder="$X.XX" /></td>
			<td><input type="text" placeholder="$X.XX" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="vehicle" value="HVL/CC" />HVL/CC</td>
			<td><input type="text" placeholder="$X.XX" /></td>
			<td><input type="text" placeholder="$X.XX" /></td>
			<td><input type="text" placeholder="$X.XX" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="vehicle" value="Metal Clad" />Metal Clad</td>
			<td><input type="text" placeholder="$X.XX" /></td>
			<td><input type="text" placeholder="$X.XX" /></td>
			<td><input type="text" placeholder="$X.XX" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="vehicle" value="MVMCC" />MVMCC</td>
			<td><input type="text" placeholder="$X.XX" /></td>
			<td><input type="text" placeholder="$X.XX" /></td>
			<td><input type="text" placeholder="$X.XX" /></td>
		</tr>
	</table>
	</div>
	</div>
<!--- OLD BACKEND CODE
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
-->
</form>
<?php include "include/footer.php"; ?>
