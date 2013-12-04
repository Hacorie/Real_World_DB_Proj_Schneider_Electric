<?php
		
	require_once('include/db.php');

	date_default_timezone_set('America/Chicago');

	session_start();
	gateway(2);
	$title = 'Add Tag';

	$db = dbConnect();

	if (isset($_POST['submit'])) {

		// Add an entry to the log_in table
		$sql = "INSERT INTO Tag(Revision, LeadTime, CreationDate, Description, TagNotes, 
				PriceNotes, PriceExpire, MaterialCost, LaborCost, EngineeringCost, 
				InstallCost, Subcategory, Complexity, Owner, HVL, HVLCC, MC, MVMCC)
				VALUES (1, ?, CURRENT_DATE, ?, ?, ?, ADDDATE(CURRENT_DATE, INTERVAL ? MONTH), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $db->prepare($sql);

		$totalCost = $_POST['mCost'] + $_POST['labor'] + $_POST['engineering'];

		$hvl = (isset($_POST['hvl']) ? 1 : 0);
		$hvlcc = (isset($_POST['hvlcc']) ? 1 : 0);
		$mc = (isset($_POST['mc']) ? 1 : 0);
		$mvmcc = (isset($_POST['mvmcc']) ? 1 : 0);

		$stmt->bind_param("isssiddddsssiiii", 
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
			$_SESSION['username'],
			$hvl,
			$hvlcc,
			$mc,
			$mvmcc);

		$stmt->execute();

		if ($db->affected_rows == 1) {
			// Success
			$flash = 'Tag added!';
			Header('Location: homepage.php');
			
		} else {
			$error = 'Error adding Tag<br />' . $db->error;
		}

		$stmt->close();

	}

    $id = dbQuery($db, "SELECT Auto_increment FROM information_schema.tables WHERE table_name='Tag'");
    $complexity = dbQuery($db, 'SELECT CName FROM Complexity');
    $subCategory = dbQuery($db, 'SELECT SName FROM Subcategory');
    $laborMult = dbQuery($db, "SELECT Labor FROM Per_Hour");
    $enginMult = dbQuery($db, "SELECT Engineering FROM Per_Hour");
    $labor = $laborMult[0]['Labor'];
    $engin = $enginMult[0]['Engineering']; 

    

?>

<?php include "include/header.php";?> 
<div class="page-header">
	<h1>Add a Tag</h1>
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
		<td><input id="addTag_tagNum" type="text" name="tagNum" value="<?php echo $id[0]['Auto_increment']; ?>" disabled="disabled" /></td>
		<td><input id="addTag_rev" type="text" name="rev" value="1" disabled="disabled" /></td>
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
		<td><input id="addTag_leadTime"type="text" name="leadTime" placeholder="Lead Time" /></td>
	</tr>
	</table>
	<table id="tagTable">
	<tr>
		<td>Tag Description:</td>
	</tr>
	<tr>
		<td ><input id="tagDescCell" type="text" name="desc" placeholder="Enter a Tag Description" /></td>
	</tr>
	</table>
	<table id="tagTable">
	<tr>
		<td>Tag Notes:</td>
	</tr>
	<tr>
		<td ><input id="tagDescCell"type="text" name="tagNotes" placeholder="Enter Tag Notes" /></td>
	</tr>
	</table>
	<table id="tagTable">
	<tr>
		<td>Price Note:</td>
	</tr>
	<tr>
		<td ><input id="tagDescCell" type="text" name="priceNotes" placeholder="Enter Price Notes" /></td>
	</tr>
	</table>
	</div>

	<div id="section3">
		<strong><i>Pricing Information</i></strong>
	<table id="pricingTable">
		<tr>
			<td>Material:</td>
			<td><input type="text" id="mCost" name="mCost" placeholder="Price" /></td>
		</tr>
		<tr>
			<td>Labor:</td>
			<td><input type="text" id="lprice" name="lprice" disabled="disabled" /></td>
			<td><input type="text" id="labor"name="labor" placeholder="Hours" /></td>
		</tr>
		<tr style="border-bottom: 1px solid #000;" >
			<td>Engineering:</td>
			<td><input type="text" id="eprice"  name="eprice" disabled="disabled" /></td>
			<td><input type="text" id="engineering" name="engineering" placeholder="Hours" /></td>
		</tr>
		<tr>
			<td>Initial Cost:</td>
			<td><input type="text" id="install"  name="install" disabled="disabled" /></td>
		</tr>
		<tr id="emptyRow"><td>&nbsp;</td></tr>
		<tr id="emptyRow"><td>&nbsp;</td></tr>
		<tr id="emptyRow"><td>&nbsp;</td></tr>
	</table>
	<table id="pricingTable">
		<tr>
			<td>TAG Member:</td>
			<td><input type="text" value="<?php echo $_SESSION['username'];?>" disabled="disabled" /></td>
		</tr>
		<tr>
			<td>Price Expires:</td>
			<td><input type="text" name="priceExpiration" placeholder="Months" /></td>
		</tr>
		<tr id="emptyRow"><td>&nbsp;</td></tr>
		<tr id="emptyRow"><td>&nbsp;</td></tr>
		<tr id="emptyRow"><td>&nbsp;</td></tr>
	</table>
	<br />
	<input type="submit" name="submit" class="btn btn-success" id="attachmentButton" value="Save" /><br /><br />
	<hr style="clear: both"/>
	</div>	
	<div id="section2">
		<strong>Product Lines Tag May be Applied To:</strong>
	<table width=60% id="plTable">
		<tr>
			<td></td>
			<td>USA$</td>
			<td>Canada$</td>
			<td>Mexico$</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="hvl" value="HVL" /> HVL</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="hvlcc" value="HVL/CC" /> HVL/CC</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="mc" value="Metal Clad" /> Metal Clad</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="mvmcc" value="MVMCC" /> MVMCC</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
	</table>
	</div>
	</div>
</form>
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

        $("#install").val(mCost + lCost + eCost );
    })

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

        $("#install").val(mCost + lCost + eCost );
    })

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
        $("#install").val(mCost + lCost + eCost );
    })
        
</script>
<?php include "include/footer.php"; ?>
