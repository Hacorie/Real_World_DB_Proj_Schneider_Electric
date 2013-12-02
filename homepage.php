<?php
	session_start();
	$title = 'Home';
?>

<?php include "include/header.php"; ?>

<div class="page-header">
	<h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
</div>

<div class="panel panel-success" id="homepage_id">
  <!-- Default panel contents -->
  <div class="panel-heading"><strong>How to Use the Schneider Electric Tag System:</strong></div>

  <!-- List group -->
  <ul class="list-group">
    	<li class="list-group-item"><strong>Searching for a Tag</strong><br/>In you are wanting to search for a tag , you can navigate to the Search tab above and input all available information related to the tag you're looking for. The search will retrieve a list of tags based on the given criteria.</li>
	<li class="list-group-item"><strong>Viewing an Existing Tag</strong><br/>If you are wanting to view a tag, you can navigate to the View tab above. This page holds a list of all existing tags in the system. You can click on any tag number to reveal more information about the tag.</li>
    	<li class="list-group-item"><strong>Adding a New Tag</strong><br/>If you are wanting to add or insert a new tag to the system, you can navigate to the Insert tab above. After you have given all available information for the tag, click Save and the tag will be added to the system.</li>
    	<li class="list-group-item"><strong>Editing an Existing Tag</strong><br/>If you are wanting to edit an existing tag, you can navigate to the View tab above. After you have navigated to the detailed view for the specific tab, clicking the 'Make Revision' button will allow you to alter values associated with the tag.</li>
    </ul>
</div>

<hr />
<div class="panel panel-primary" id="homepage_id2">
  <!-- Default panel contents -->
  <div class="panel-heading"><strong>About</strong></div>
  <div class="panel-body">
	This system was jointly developed by Nathan Perry, Nathan Reale, and Alex Williams.
  </div>
</div>

<?php include "include/footer.php"; ?>