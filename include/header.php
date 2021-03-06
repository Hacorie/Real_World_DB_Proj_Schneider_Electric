<?php

function isActive($id) {
	global $title;
	if ($id == $title) {
		echo 'class="active"';
	}
}

function isAdmin() {
	global $admin;
	if (isset($admin) && $admin) {
		echo " active";
	}
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="shortcut icon" href="images/favicon.ico">

		<title><?php echo $title; ?></title>
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/sticky-footer-navbar.css" rel="stylesheet">
		<link href="css/print.css" rel="stylesheet">
	</head>

	<body>
		<div id="wrap">
			<div class="navbar navbar-default navbar-fixed-top" role="navigation">
				<div class="container">
					<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<!-- <a class="navbar-brand" href="#">Schneider Electric</a> -->
			<img src="images/favicon.ico">
		</div>
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li <?php isActive('Home');?>><a href="homepage.php">Home</a></li>
				<?php if ($_SESSION['role'] >= 1) { ?>
					<li <?php isActive('Search Tags');?>><a href="searchTag.php">Search</a></li>
					<li <?php isActive('Add Tag');?>><a href="addTag.php">Add</a></li>
				<?php } ?>
				<li <?php isActive('View Tag');?>><a href="viewTag.php">View</a></li>
				<!-- ADMIN LIST ITEMS -->
				<?php if ($_SESSION['role'] >= 3) { ?>
					<li class="dropdown <?php isAdmin(); ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Administration <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li <?php isActive('View Log');?>><a href="viewLog.php">Login Record</a></li>
							<li <?php isActive('Edit Multipliers');?>><a href="editMults.php">Edit Multipliers</a></li>
							<li <?php isActive('Edit Complexities');?>><a href="editComp.php">Edit Complexities</a></li>
							<li <?php isActive('Edit Sub-Categories');?>><a href="editSC.php">Edit Sub-Categories</a></li>
							<li class="divider"></li>
							<li <?php isActive('Manage Users');?>><a href="users.php">Manage Users</a></li>
							<li <?php isActive('Manage Groups');?>><a href="groups.php">Manage Groups</a></li>
						</ul>
					</li>
				<?php } ?>
				<li><a href="logout.php">Logout</a></li>
			</ul>
		</div>
				</div>
			</div>

			<div class="container">

				<?php if (isset($flash) && $flash != '') { ?>
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<?php echo $flash; ?>
					</div>
				<?php } ?>
				<?php if (isset($error) && $error != '') { ?>
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<?php echo $error; ?>
					</div>
				<?php } ?>

				<div id="printedBy">
					<img src="images/favicon.ico">
					Printed by <?php echo $_SESSION['username']; ?>
				</div>