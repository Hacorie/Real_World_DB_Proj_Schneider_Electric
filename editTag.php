<?php
	
	require_once('include/db.php');

	date_default_timezone_set('America/Chicago');

	session_start();
	gateway(2);
	$title = 'Edit Tag';

	$db = dbConnect();

	if (isset($_POST['fosubmit'])) {
		$sql = "INSERT INTO Applied_FO_Table VALUES (?, ?, ?, ?, ?)";
		$stmt = $db->prepare($sql);

		$stmt->bind_param("isiis",
			$_POST['fonumber'],
			$_POST['notes'],
			$_POST['tag'],
			$_POST['rev'],
			$_POST['type']);

		$stmt->execute();

		if ($db->affected_rows == 1) {
			// Success
			$flash = 'FO added!';
			
		} else {
			$error = 'Error adding FO<br />' . $db->error;
		}

		$stmt->close();

	}

	if (isset($_POST['submit'])) {

		// Add an entry to the log_in table
		$sql = "INSERT INTO Tag(Num, Revision, LeadTime, CreationDate, Description, TagNotes, 
				PriceNotes, PriceExpire, MaterialCost, LaborCost, EngineeringCost, 
				InstallCost, Subcategory, Complexity, Owner, HVL, HVLCC, MC, MVMCC)
				VALUES (?, ?, ?, CURRENT_DATE, ?, ?, ?, ADDDATE(CURRENT_DATE, INTERVAL ? MONTH), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $db->prepare($sql);

		$totalCost = $_POST['mcost'] + $_POST['lcost'] + $_POST['ecost'];

		$hvl = (isset($_POST['hvl']) ? 1 : 0);
		$hvlcc = (isset($_POST['hvlcc']) ? 1 : 0);
		$mc = (isset($_POST['mc']) ? 1 : 0);
		$mvmcc = (isset($_POST['mvmcc']) ? 1 : 0);

		$stmt->bind_param("iiisssiddddsssiiii",
			$_POST['tag'],
			$_POST['rev'],
			$_POST['leadTime'],
			$_POST['desc'],
			$_POST['tagNotes'],
			$_POST['priceNotes'],
			$_POST['priceExpiration'],
			$_POST['mcost'],
			$_POST['labor'],
			$_POST['engineering'],
			$totalCost,
			$_POST['sCategory'],
			$_POST['complexity'],
			$_SESSION['username'],
			$hvl,
			$hvlcc,
			$mc,
			$mvmcc);

		$stmt->execute();

		if ($db->affected_rows == 1) {
			// Success
			$flash = 'Tag updated!';
			Header('Location: viewTag.php?tag=' . $_POST['tag'] . '&rev=' . $_POST['rev']);
			
		} else {
			$error = 'Error updating Tag<br />' . $db->error;
		}

		$stmt->close();

	}

	if (isset($_POST['fileadd'])) {

		$uploaddir = './files/';
		$uploadfile = $uploaddir . basename($_FILES['file']['name']);

		if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
		    $flash = "File uploaded!";

		    $sql = "INSERT INTO Attachment VALUES (?, ?, ?)";
			$stmt = $db->prepare($sql);

			$stmt->bind_param("sss",
				$_GET['tag'],
				$_FILES['file']['name'],
				$uploadfile);

			$stmt->execute();
		} else {
			$error = "Error uploading file";
		}

	}

	if (isset($_POST['filedelete']) && !empty($_POST['deleteFile'])) {
		$sql = "DELETE FROM Attachment WHERE Tag = ? AND Name = ?";
		$stmt = $db->prepare($sql);

		$stmt->bind_param("ss",
			$_GET['tag'],
			$file);

		foreach($_POST['deleteFile'] as $file) {
			$stmt->execute();
		}

		$flash = "Files removed";

	}

	// Verify that a valid tag was specified
	if (isset($_GET['tag'])) {


		// Get the tag
	
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


    $laborMult = dbQuery($db, "SELECT Labor FROM Per_Hour");
    $enginMult = dbQuery($db, "SELECT Engineering FROM Per_Hour");
    $labor = $laborMult[0]['Labor'];
    $engin = $enginMult[0]['Engineering']; 


?>

<?php include "include/header.php"; ?>
<div class="page-header">
	<h1>Edit a Tag</h1>
</div> 
<form name="addtag" action="editTag.php?tag=<?php echo $tag['Num']; ?>" method="post" accept-charset="utf-8">
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
		<td><input id="addTag_tagNum" type="text" name="tag" value="<?php echo $tag['Num']; ?>" readonly="readonly" /></td>
		<td><input id="addTag_rev" type="text" name="rev" value="<?php echo $tag['Revision'] + 1; ?>" readonly="readonly" /></td>
		<td><input id="addTag_date" type="text" name="date" value="<?php echo date('n/j/y'); ?>" readonly="readonly" /></td>
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
			<td><input type="text" name="mcost" id="mCost" value="<?php echo $tag['MaterialCost']; ?>" /></td>
		</tr>
		<tr>
			<td>Labor:</td>
			<td><input id="lprice" name="lcost" type="text" placeholder="Labor Cost" readonly="readonly" /></td>
			<td><input type="text" id="labor"  name="labor" placeholder="Hours" value="<?php echo $tag['LaborCost']; ?>" /></td>
		</tr>
		<tr style="border-bottom: 1px solid #000;">
			<td>Engineering:</td>
			<td><input type="text" name="ecost" id="eprice" placeholder="Engineering Cost" readonly="readonly" /></td>
			<td><input type="text" id="engineering" name="engineering" placeholder="Hours" value="<?php echo $tag['EngineeringCost']; ?>" /></td>
		</tr>
		<tr>
			<td>Initial Cost:</td>
			<td><input type="text" id="install" value="<?php echo $tag['InstallCost']; ?>" readonly="readonly" /></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
	</table>
	<table id="pricingTable">
		<tr>
			<td>TAG Member:</td>
			<td><input type="text" value="<?php echo $_SESSION['username'];?>" readonly="readonly" /></td>
		</tr>
		<tr>
			<td>Price Expires:</td>
			<td><input type="text" name="priceExpiration" value="<?php echo $tag['PriceExpire']; ?>" /></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
	</table>
	<button class="btn btn-danger" id="attachmentButton">Click Box to Make TAG Permanently Obsolete</button><br />
	<input type="submit" name="submit" class="btn btn-success" id="attachmentButton" value="Save" /><br /><br />
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
			<td><input type="checkbox" <?php if($tag['HVL'] == 1) { echo 'checked="checked"'; }?> name="hvl" value="HVL" />HVL</td>
			<td><input id="usahvl" type="text" value="<?php echo $tag['InstallCost'] * $products['HVL'] * $countries['USA']; ?>" /></td>
			<td><input id="canadahvl" type="text" value="<?php echo $tag['InstallCost'] * $products['HVL'] * $countries['Canada']; ?>" /></td>
			<td><input id="mexicohvl" type="text" value="<?php echo $tag['InstallCost'] * $products['HVL'] * $countries['Mexico']; ?>" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" <?php if($tag['HVLCC'] == 1) { echo 'checked="checked"'; }?> name="hvlcc" value="hvlcc" />HVL/CC</td>
			<td><input id="usahvlcc" type="text" value="<?php echo $tag['InstallCost'] * $products['HVL/CC'] * $countries['USA']; ?>" /></td>
			<td><input id="canadahvlcc" type="text" value="<?php echo $tag['InstallCost'] * $products['HVL/CC'] * $countries['Canada']; ?>" /></td>
			<td><input id="mexicohvlcc" type="text" value="<?php echo $tag['InstallCost'] * $products['HVL/CC'] * $countries['Mexico']; ?>" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" <?php if($tag['MC'] == 1) { echo 'checked="checked"'; }?> name="mc" value="mc" />Metal Clad</td>
			<td><input id="usamc" type="text" value="<?php echo $tag['InstallCost'] * $products['Metal Clad'] * $countries['USA']; ?>" /></td>
			<td><input id="canadamc" type="text" value="<?php echo $tag['InstallCost'] * $products['Metal Clad'] * $countries['Canada']; ?>" /></td>
			<td><input id="mexicomc" type="text" value="<?php echo $tag['InstallCost'] * $products['Metal Clad'] * $countries['Mexico']; ?>" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" <?php if($tag['MVMCC'] == 1) { echo 'checked="checked"'; }?> name="mvmcc" value="mvmcc" />MVMCC</td>
			<td><input id="usamvmcc" type="text" value="<?php echo $tag['InstallCost'] * $products['MVMCC'] * $countries['USA']; ?>" /></td>
			<td><input id="canadamvmcc" type="text" value="<?php echo $tag['InstallCost'] * $products['MVMCC'] * $countries['Canada']; ?>" /></td>
			<td><input id="mexicomvmcc" type="text" value="<?php echo $tag['InstallCost'] * $products['MVMCC'] * $countries['Mexico']; ?>" /></td>
		</tr>
	</table>
    
	</div>
</form>
<div id="section4">

<!-- Nav tabs -->
<ul class="nav nav-tabs nav-success">
  <li><a href="#all" data-toggle="tab">All</a></li>
  <li><a href="#quote" data-toggle="tab">Quote</a></li>
  <li><a href="#factoryorder" data-toggle="tab">Factory Order</a></li>
</ul>


<!-- Tab panes -->
<div class="tab-content" style="margin-bottom: 100px; margin-top:0px;" >
  <div class="tab-pane active" id="all">
	<table class="table-bordered" width=100%>
	<tr>
		<th>Tag Number</th>
		<th>FO Number Applied To</th>
		<th>Notes</th>
	</tr>
	<?php if (!empty($fotable)) { foreach($fotable as $fo) { ?>
		<tr>
			<td><?php echo $tag['Num']; ?></td>
			<td><?php echo $fo['FONumber']; ?></td>
			<td><?php echo $tag['Notes']; ?></td>
		</tr>
	<?php } } ?>
	</table>
  </div>
  <div class="tab-pane" id="quote">
	<table class="table-bordered" width=100%>
	<tr>
		<th>Tag Number</th>
		<th>FO Number Applied To</th>
		<th>Notes</th>
	</tr>
	<?php if (!empty($fotable)) { foreach($fotable as $fo) { ?>
		<?php if ($fo['Typeof'] == 'Q') { ?>
			<tr>
				<td><?php echo $tag['Num']; ?></td>
				<td><?php echo $fo['FONumber']; ?></td>
				<td><?php echo $tag['Notes']; ?></td>
			</tr>
		<?php } ?>	
	<?php } } ?>
	</table>
	<form style="margin-top: 20px;" action="editTag.php?tag=<?php echo $tag['Num']; ?>" method="post">
		<input type="hidden" name="tag" value="<?php echo $tag['Num']; ?>" />
		<input type="hidden" name="rev" value="<?php echo $tag['Revision']; ?>" />
		<input type="hidden" name="type" value="Q" />
		<input type="text" placeholder="Enter FO Number Applied To" name="fonumber" />
		<input type="text" placeholder="Enter Notes" name="notes" />
		<input type="submit" name="fosubmit" class="btn btn-success" id="appliedFO_addButton" value="Add to FO" /><br />
	</form>
  </div>
  <div class="tab-pane" id="factoryorder">
	<table class="table-bordered" width=100%>
	<tr>
		<th>Tag Number</th>
		<th>FO Number Applied To</th>
		<th>Notes</th>
	</tr>
	<?php if (!empty($fotable)) { foreach($fotable as $fo) { ?>
		<?php if ($fo['Typeof'] == 'F') { ?>
			<tr>
				<td><?php echo $tag['Num']; ?></td>
				<td><?php echo $fo['FONumber']; ?></td>
				<td><?php echo $tag['Notes']; ?></td>
			</tr>
		<?php } ?>	
	<?php } } ?>
	</table>
	<form style="margin-top: 20px;" action="editTag.php?tag=<?php echo $tag['Num']; ?>" method="post">
		<input type="hidden" name="tag" value="<?php echo $tag['Num']; ?>" />
		<input type="hidden" name="rev" value="<?php echo $tag['Revision']; ?>" />
		<input type="hidden" name="type" value="F" />
		<input type="text" placeholder="Enter FO Number Applied To" name="fonumber" />
		<input type="text" placeholder="Enter Notes" name="notes" />
		<input type="submit" name="fosubmit" class="btn btn-success" id="appliedFO_addButton" value="Add to FO" /><br />
	</form>
  </div>

</div>

</div>
<div id="section5">
	<div id="attachmentList">
		<form action="editTag.php?tag=<?php echo $tag['Num']; ?>" enctype="multipart/form-data" method="POST">
			<strong>Attachments:</strong>
			<ul style="list-style-type: none">
				<?php if (!empty($attachments)) { foreach($attachments as $attachment) { ?>
					<li>
						<input type="checkbox" name="deleteFile[]" value="<?php echo $attachment['Name']; ?>" />
						<a href="<?php echo $attachment['Path']; ?>" target="_blank"><?php echo $attachment['Name']; ?></a>
					</li>
				<?php } } ?>
			</ul><br />

			<input type="file" name="file" id="file"><br />
			<input type="submit" name="fileadd" class="btn btn-success" id="viewTag_button" value="Add" />
			<input type="submit" name="filedelete" class="btn btn-danger" id="viewTag_button" value="Delete" /><br />
		</form>
	</div>
</div>
</div>

<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script>
$("#labor")
    .change(function() {
        var cost = $(this).val();
        cost *= <?php echo $labor;?>;
        $("#lprice").val(cost);
        var mCost = parseInt($("#mCost").val());
        var lCost = parseFloat(cost);
        var eCost = parseFloat($("#eprice").val());
        if(isNaN(eCost))
            eCost = 0;
        if(isNaN(mCost))
            mCost = 0;

        var iCost = mCost+lCost+eCost;
        $("#install").val(iCost );
        $("#usahvl").val(iCost*parseFloat(<?php echo $products['HVL']?>)*parseFloat(<?php echo $countries['USA']?> ) );
        $("#canadahvl").val(iCost*parseFloat(<?php echo $products['HVL']?>)*parseFloat(<?php echo $countries['Canada']?>) );
        $("#mexicohvl").val(iCost*parseFloat(<?php echo $products['HVL']?>)*parseFloat(<?php echo $countries['Mexico']?>) );

        $("#usahvlcc").val(iCost*parseFloat(<?php echo $products['HVL/CC']?>)*parseFloat(<?php echo $countries['USA']?>) );
        $("#canadahvlcc").val(iCost*parseFloat(<?php echo $products['HVL/CC']?>)*parseFloat(<?php echo $countries['Canada']?>) );
        $("#mexicohvlcc").val(iCost*parseFloat(<?php echo $products['HVL/CC']?>)*parseFloat(<?php echo $countries['Mexico']?>) );

        $("#usamc").val(iCost*parseFloat(<?php echo $products['Metal Clad']?>)*parseFloat(<?php echo $countries['USA']?>) );
        $("#canadamc").val(iCost*parseFloat(<?php echo $products['Metal Clad']?>)*parseFloat(<?php echo $countries['Canada']?>) );
        $("#mexicomc").val(iCost*parseFloat(<?php echo $products['Metal Clad']?>)*parseFloat(<?php echo $countries['Mexico']?>) );

        $("#usamvmcc").val(iCost*parseFloat(<?php echo $products['MVMCC']?>)*parseFloat(<?php echo $countries['USA']?>) );
        $("#canadamvmcc").val(iCost*parseFloat(<?php echo $products['MVMCC']?>)*parseFloat(<?php echo $countries['Canada']?>) );
        $("#mexicomvmcc").val(iCost*parseFloat(<?php echo $products['MVMCC']?>)*parseFloat(<?php echo $countries['Mexico']?>) );

    }).change()

$("#engineering")
    .change(function() {
        var cost = $(this).val();
        cost *= <?php echo $engin;?>;
        $("#eprice").val(cost);
        var mCost = parseInt($("#mCost").val());
        var eCost = parseFloat(cost);
        var lCost = parseFloat($("#lprice").val());
        if(isNaN(lCost))
            lCost = 0;
        if(isNaN(mCost))
            mCost = 0;

        var iCost = mCost+lCost+eCost;
        $("#install").val(iCost );
        $("#usahvl").val(iCost*parseFloat(<?php echo $products['HVL']?>)*parseFloat(<?php echo $countries['USA']?> ) );
        $("#canadahvl").val(iCost*parseFloat(<?php echo $products['HVL']?>)*parseFloat(<?php echo $countries['Canada']?>) );
        $("#mexicohvl").val(iCost*parseFloat(<?php echo $products['HVL']?>)*parseFloat(<?php echo $countries['Mexico']?>) );

        $("#usahvlcc").val(iCost*parseFloat(<?php echo $products['HVL/CC']?>)*parseFloat(<?php echo $countries['USA']?>) );
        $("#canadahvlcc").val(iCost*parseFloat(<?php echo $products['HVL/CC']?>)*parseFloat(<?php echo $countries['Canada']?>) );
        $("#mexicohvlcc").val(iCost*parseFloat(<?php echo $products['HVL/CC']?>)*parseFloat(<?php echo $countries['Mexico']?>) );

        $("#usamc").val(iCost*parseFloat(<?php echo $products['Metal Clad']?>)*parseFloat(<?php echo $countries['USA']?>) );
        $("#canadamc").val(iCost*parseFloat(<?php echo $products['Metal Clad']?>)*parseFloat(<?php echo $countries['Canada']?>) );
        $("#mexicomc").val(iCost*parseFloat(<?php echo $products['Metal Clad']?>)*parseFloat(<?php echo $countries['Mexico']?>) );

        $("#usamvmcc").val(iCost*parseFloat(<?php echo $products['MVMCC']?>)*parseFloat(<?php echo $countries['USA']?>) );
        $("#canadamvmcc").val(iCost*parseFloat(<?php echo $products['MVMCC']?>)*parseFloat(<?php echo $countries['Canada']?>) );
        $("#mexicomvmcc").val(iCost*parseFloat(<?php echo $products['MVMCC']?>)*parseFloat(<?php echo $countries['Mexico']?>) );

    }).change()

$("#mCost")
    .change(function() {
        var cost = $(this).val();
        var lCost = parseFloat($("#lprice").val());
        var eCost = parseFloat($("#eprice").val());
        var mCost = parseFloat(cost);
        if(isNaN(lCost))
            lCost = 0;
        if(isNaN(eCost))
            eCost = 0;
        
        var iCost = mCost+lCost+eCost;
        $("#install").val(iCost );
        $("#usahvl").val(iCost*parseFloat(<?php echo $products['HVL']?>)*parseFloat(<?php echo $countries['USA']?> ) );
        $("#canadahvl").val(iCost*parseFloat(<?php echo $products['HVL']?>)*parseFloat(<?php echo $countries['Canada']?>) );
        $("#mexicohvl").val(iCost*parseFloat(<?php echo $products['HVL']?>)*parseFloat(<?php echo $countries['Mexico']?>) );

        $("#usahvlcc").val(iCost*parseFloat(<?php echo $products['HVL/CC']?>)*parseFloat(<?php echo $countries['USA']?>) );
        $("#canadahvlcc").val(iCost*parseFloat(<?php echo $products['HVL/CC']?>)*parseFloat(<?php echo $countries['Canada']?>) );
        $("#mexicohvlcc").val(iCost*parseFloat(<?php echo $products['HVL/CC']?>)*parseFloat(<?php echo $countries['Mexico']?>) );

        $("#usamc").val(iCost*parseFloat(<?php echo $products['Metal Clad']?>)*parseFloat(<?php echo $countries['USA']?>) );
        $("#canadamc").val(iCost*parseFloat(<?php echo $products['Metal Clad']?>)*parseFloat(<?php echo $countries['Canada']?>) );
        $("#mexicomc").val(iCost*parseFloat(<?php echo $products['Metal Clad']?>)*parseFloat(<?php echo $countries['Mexico']?>) );

        $("#usamvmcc").val(iCost*parseFloat(<?php echo $products['MVMCC']?>)*parseFloat(<?php echo $countries['USA']?>) );
        $("#canadamvmcc").val(iCost*parseFloat(<?php echo $products['MVMCC']?>)*parseFloat(<?php echo $countries['Canada']?>) );
        $("#mexicomvmcc").val(iCost*parseFloat(<?php echo $products['MVMCC']?>)*parseFloat(<?php echo $countries['Mexico']?>) );

    }).change()
        
</script>

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
<?php include "include/footer.php"; ?>
