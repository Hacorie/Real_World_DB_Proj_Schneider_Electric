<?php
	session_start();
	$title = 'View Tag';
?>

<?php include "include/header.php"; ?>

<div class="page-header">
	<h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
</div>
<p class="lead">Use the navigation bar above to search, insert, or view a specific tag entry.</p>
<p>If you are experiencing difficulties with this site, please contact your system administrator.</p>

<?php include "include/footer.php"; ?>