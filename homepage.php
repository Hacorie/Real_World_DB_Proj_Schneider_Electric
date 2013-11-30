<?php
  session_start();
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

    <title>Home</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/sticky-footer-navbar.css" rel="stylesheet">
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
              <li class="active"><a href="homepage.php">Home</a></li>
              <li><a href="search.html">Search</a></li>
              <li><a href="insert.html">Insert</a></li>
              <li><a href="view.html">View</a></li>
              <!-- ADMIN LIST ITEMS -->
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Administration <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="userlog.html">Login Record</a></li>
                  <li class="divider"></li>
                  <li><a href="addUser.php">Add User</a></li>
                  <li><a href="groups.php">Manage Groups</a></li>
                </ul>
              </li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>

      <div class="container">
        <div class="page-header">
          <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
        </div>
        <p class="lead">Use the navigation bar above to search, insert, or view a specific tag entry.</p>
        <p>If you are experiencing difficulties with this site, please contact your system administrator.</p>
      </div>
    </div>

    <div id="footer">
      <div class="container">
        <p class="text-muted credit">
          &copy; Schneider Electric 2013.
        </p>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
