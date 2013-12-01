<?php
	session_start();
	$title = 'Search a Tag';
?>

<?php include "include/header.php"; ?>

<div class="page-header">
	<h1>Search a Tag</h1>

</div>
	<div class="large-3">
		<input type="text" id="userSearch" placeholder="Enter a Tag" />
	</div>
	<div class="lead">
		<div class="alert-box success radius" id="userResult" style="display:none;"></div>
		<div class="alert-box alert radius" id="userError" style="display:none;"></div>
	</div>
	<div>
	<table class="table table-bordered" id="tags_table">
	<thead>
		<tr>
			<th id="th_tag">Tag</th>
			<th id="th_rev">Rev</th>
			<th id="th_date">Date</th>
			<th id="th_desc">Description</th>
			<th id="th_subcat">Sub Cat.</th>
			<th id="th_hvl">HVL</th>
			<th id="th_cc4">HVL CC4</th>
			<th id="th_mclad">Metal Clad</th>
			<th id="th_mvcc"> MV MCC</th>
			<th id="th_spec">Special Items</th>
			<th id="th_nodes">Notes</th>
			<th id="th_install">Install Cost</th>
			<th id="th_pricenote">Price Note</th>
			<th id="th_creator">Created By</th>
		</tr>
	</thead>
	<tbody>
	<!--- Fill in with PHP Results --->
	<tr class="active">
		<th>07-5804</th>
		<th>0</th>
		<th>05/02/2013</th>
		<th>This Tag is for a MiCom is for a "Single or Three Phase High Impedance Differential</th>
		<th>Relays</th>
		<th>Yes</th>
		<th>Yes</th>
		<th>Yes</th>
		<th>Yes</th>
		<th>No</th>
		<th>Q2C #33215451 - Nova Chemicals</th>
		<th>$750.76</th>
		<th>Price is per each</th>
		<th>D. White</th>
	</tr>
	<tr>
		<th>07-5804</th>
		<th>0</th>
		<th>05/02/2013</th>
		<th>This Tag is for a MiCom is for a "Single or Three Phase High Impedance Differential</th>
		<th>Relays</th>
		<th>Yes</th>
		<th>Yes</th>
		<th>Yes</th>
		<th>Yes</th>
		<th>No</th>
		<th>Q2C #33215451 - Nova Chemicals</th>
		<th>$750.76</th>
		<th>Price is per each</th>
		<th>D. White</th>
	</tr>
	<tr class="active">
		<th>07-5804</th>
		<th>0</th>
		<th>05/02/2013</th>
		<th>This Tag is for a MiCom is for a "Single or Three Phase High Impedance Differential</th>
		<th>Relays</th>
		<th>Yes</th>
		<th>Yes</th>
		<th>Yes</th>
		<th>Yes</th>
		<th>No</th>
		<th>Q2C #33215451 - Nova Chemicals</th>
		<th>$750.76</th>
		<th>Price is per each</th>
		<th>D. White</th>
	</tr>
	</tbody>
	</table>
</div>

<?php include "include/footer.php"; ?>