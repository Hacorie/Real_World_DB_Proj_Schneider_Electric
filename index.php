<?php
	$error = false;

	if (isset($_POST['submit'])) {
		$db = new mysqli("mysql.cs.mtsu.edu", "ncr2g", "donthackmebro", "ncr2g");
		
		if ($db->connect_errno) {
			echo "Failed to connect to MySQL: " . $db->connect_error;
		}

		$stmt = $db->prepare("SELECT UName FROM User WHERE Uname = ? AND Password = ?");
		$stmt->bind_param("ss", $_POST['username'], sha1($_POST['password']));
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			// Logged in successfully

			// Store username in the SESSIONS variable
			$stmt->bind_result($username);
			$stmt->fetch();

			session_start();
			$_SESSION['username'] = $username;

			// Redirect to homepage
			header('Location: homepage.php');
		} else {
			$error = true;
		}

		$stmt->close();
		$db->close();


	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Schneider Electric</title>
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
</head>
<body>
	<section class="loginform cf">

		<center><img src ="images/logo.gif"></center><br/>
		<form name="login" action="index.php" method="post" accept-charset="utf-8">
			<ul>
				<?php if ($error) { ?>
					<li>
						Invalid Username or Password
					</li>
				<?php } ?>
				<li>
					<input type="text" name="username" placeholder="Username" required>
				</li>
				<li>
					<input type="password" name="password" placeholder="Password" required></li>
				<li>
					<input type="submit" name="submit" value="Login">
				</li>
			</ul>
		</form>
	</section>
</body>
</html>