<?php
	
	require_once('include/db.php');

	session_start();
	gateway(2);
	$title = 'Edit Tag';

	// Verify that a valid tag was specified
	if (isset($_GET['tag'])) {


		// Get the tag
		$db = dbConnect();
		$stmt = $db->prepare("SELECT Num, Revision, CreationDate, Description, Subcategory, Complexity, PriceExpire,
			TagNotes, InstallCost, PriceNotes, Owner, LeadTime, MaterialCost, LaborCost, EngineeringCost,
			HVL, HVLCC, MC, MVMCC
			FROM Tag AS T WHERE Num = ? AND Revision = (SELECT MAX(Revision) FROM Tag WHERE Num = T.Num)");
		$stmt->bind_param("i", $_GET['tag']);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows > 0) {

			$stmt->bind_result($num, $revision, $creationDate, $description, $category, $complexity, $priceExpire,
				$notes, $cost, $priceNotes, $owner, $leadTime, $mat, $labor, $eng, $hvl, $hvlcc, $mc, $mvmcc);

			$stmt->fetch();
			$stmt->close();

            $compArr = dbQuery($db, 'SELECT CName FROM Complexity');
            $subCatArr = dbQuery($db, 'SELECT SName FROM Subcategory');

			$tag = array(
					'Num' => $num,
					'Revision' => $revision,
					'CreationDate' => $creationDate,
					'Description' => $description,
					'Subcategory' => $category,
					'Complexity' => $complexity,
					'PriceExpire' => $priceExpire,
					'Notes' => $notes,
					'InstallCost' => $cost,
					'PriceNotes' => $priceNotes,
					'Owner' => $owner,
					'LeadTime' => $leadTime,
					'MaterialCost' => $mat,
					'LaborCost' => $labor,
					'EngineeringCost' => $eng,
					'HVL' => $hvl,
					'HVLCC' => $hvlcc,
					'MC' => $mc,
					'MVMCC' => $mvmcc,
                    'compArr' => $compArr,
                    'subCatArr' => $subCatArr
				);

			
		} else {
			$error = "Invalid Tag Number";
		}

		// Get the multipliers
		$countryDB = dbQuery($db, 'SELECT * FROM Country');
		$countries = array();
		foreach($countryDB as $country) {
			$countries[$country['CName']] = $country['Multiplier'];
		}

		$productDB = dbQuery($db, 'SELECT * FROM Product_Type');
		$products = array();
		foreach($productDB as $product) {
			$products[$product['PName']] = $product['Multiplier'];
		}

		$attachments = dbQuery($db, "SELECT * FROM Attachment WHERE Tag = '" . intval($_GET['tag']) . "'");
		$fotable = dbQuery($db, "SELECT * FROM Applied_FO_Table WHERE Num = '" . intval($_GET['tag']) . "'");

		$complexity = dbQuery($db, 'SELECT CName FROM Complexity');
    	$subCategory = dbQuery($db, 'SELECT SName FROM Subcategory');

	}




?>

<?php include "include/header.php"; ?>
<div class="page-header">
	<h1>Edit a Tag</h1>
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
		<td><input id="addTag_tagNum" type="text" name="tagNum" value="<?php echo $tag['Num']; ?>" disabled="disabled" /></td>
		<td><input id="addTag_rev" type="text" name="rev" value="<?php echo $tag['Revision'] + 1; ?>" disabled="disabled" /></td>
		<td><input id="addTag_date" type="text" name="date" value="<?php echo date('n/j/y'); ?>" disabled="disabled" /></td>
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
        <td><input id="addTag_leadTime"type="text" name="leadTime" value="<?php echo $tag['LeadTime']; ?>" /></td>
	</tr>
	</table>
	<table id="tagTable">
	<tr>
		<td>Tag Description:</td>
	</tr>
	<tr>
		<td ><input id="tagDescCell" type="text" name="desc" value="<?php echo $tag['Description']; ?>" /></td>
	</tr>
	</table>
	<table id="tagTable">
	<tr>
		<td>Tag Notes:</td>
	</tr>
	<tr>
		<td ><input id="tagDescCell"type="text" name="tagNotes" value="<?php echo $tag['Notes']; ?>" /></td>
	</tr>
	</table>
	<table id="tagTable">
	<tr>
		<td>Price Note:</td>
	</tr>
	<tr>
		<td ><input id="tagDescCell" type="text" name="priceNotes" value="<?php echo $tag['PriceNotes']; ?>" /></td>
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
			<td><input type="text" value="<?php echo $_SESSION['username'];?>" disabled="disabled" /></td>
		</tr>
		<tr>
			<td>Price Expires:</td>
			<td><input type="text" value="<?php echo $tag['PriceExpire']; ?>" /></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
	</table>
	<button class="btn btn-danger" id="attachmentButton">Click Box to Make TAG Permanently Obsolete</button><br />
	<button class="btn btn-success" id="attachmentButton">Save</button><br /><br />
	<hr style="clear: both"/>
	<div id="attachmentList">
	<strong>Attachments:</strong>
	<ul style="list-style-type: none">	
		<li>Attachment1.pdf<input type="checkbox" value="MVMCC" id="attachmentCheckbox" /></li>
		<li>Attachment2.txt<input type="checkbox" value="MVMCC" id="attachmentCheckbox" /></li>
		<li>TheNRealEngine-eBook.pdf<input type="checkbox" value="MVMCC" id="attachmentCheckbox" /></li>
		<!-- Add PHP to retrieve list of attachments and their respective links -->
	</ul><br />
	<form>
		<input type="file" name="file" id="file"><br />
		<button class="btn btn-success" id="viewTag_button">Add</button></form><button class="btn btn-danger" id="viewTag_button">Delete</button><br />
	</div>
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
			<td><input type="checkbox" <?php if($tag['HVL'] == 1) { echo 'checked="checked"'; }?> />HVL</td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['HVL'] * $countries['USA']; ?>" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['HVL'] * $countries['Canada']; ?>" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['HVL'] * $countries['Mexico']; ?>" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" <?php if($tag['HVLCC'] == 1) { echo 'checked="checked"'; }?> />HVL/CC</td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['HVL/CC'] * $countries['USA']; ?>" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['HVL/CC'] * $countries['Canada']; ?>" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['HVL/CC'] * $countries['Mexico']; ?>" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" <?php if($tag['MC'] == 1) { echo 'checked="checked"'; }?> />Metal Clad</td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['Metal Clad'] * $countries['USA']; ?>" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['Metal Clad'] * $countries['Canada']; ?>" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['Metal Clad'] * $countries['Mexico']; ?>" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" <?php if($tag['MVMCC'] == 1) { echo 'checked="checked"'; }?> />MVMCC</td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['MVMCC'] * $countries['USA']; ?>" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['MVMCC'] * $countries['Canada']; ?>" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['MVMCC'] * $countries['Mexico']; ?>" /></td>
		</tr>
	</table>
	</div>
<div id="section4">

<!-- Nav tabs -->
<ul class="nav nav-tabs nav-success">
  <li><a href="#quote" data-toggle="tab">Quote</a></li>
  <li><a href="#factoryorder" data-toggle="tab">Factory Order</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content" style="margin-top: -100px" >
  <div class="tab-pane active" id="quote">
	<table class="table-bordered" width=100%>
	<tr>
		<th>Tag Number</th>
		<th>FO Number Applied To</th>
		<th>Notes</th>
	</tr>
	<tr>
		<td>Hi</td>
		<td>Chyna</td>
		<td>BOOM</td>
	</tr>
	</table>
	<form style="margin-top: 20px;">
	<input type="text" placeholder="Enter Tag Number" />
	<input type="text" placeholder="Enter FO Number Applied To" />
	<input type="text" placeholder="Enter Notes" />
	<button class="btn btn-success" id="appliedFO_addButton">Add to FO</button><br />
	</form>
  </div>
  <div class="tab-pane" id="factoryorder">
	<table class="table-bordered" width=100%>
	<tr>
		<th>Tag Number</th>
		<th>FO Number Applied To</th>
		<th>Notes</th>
	</tr>
	<tr>
		<td>Hello</td>
		<td>Shawn Michaels</td>
		<td>ROASTED</td>
	</tr>
	</table>
	<form style="margin-top: 20px;">
	<input type="text" placeholder="Enter Tag Number" />
	<input type="text" placeholder="Enter FO Number Applied To" />
	<input type="text" placeholder="Enter Notes" />
	<button class="btn btn-success" id="appliedFO_addButton">Add to FO</button><br />
	</form>
</div>
</div>

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