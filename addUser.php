<?php

    function dbConnect() {
        $db = new mysqli("mysql.cs.mtsu.edu", "ncr2g", "donthackmebro", "ncr2g");
        
        if ($db->connect_errno) {
            echo "Failed to connect to MySQL: " . $db->connect_error;
            exit(1);
        }

        return $db;
    }

    session_start();

    $db = dbConnect();

    // Get a list of groups
    $res = $db->query('SELECT GName from Groups');
    $groups = Array();
    while ($row = $res->fetch_assoc()) {
        $groups[] = $row;
    }

    $errMsg = Array();

    // If the form was submitted
    if (isset($_POST['submit'])) {
        if ($_POST['password'] != $_POST['confirmPassword']) {
            $errMsg[] = 'Passwords do not match.';
        } else {


            // Add an entry to the log_in table
            $sql = "INSERT INTO User(UName, Password) 
                    VALUES (?, ?)";
            $stmt = $db->prepare($sql);
            
            $stmt->bind_param("ss", $_POST['username'], sha1($_POST['password']));
            $stmt->execute();

            if ($db->affected_rows == 1) {
                // Add groups
                $sql = "INSERT INTO Member_Of(Username, GName) VALUES (?, ?)";
                $stmt = $db->prepare($sql);
                
                $stmt->bind_param("ss", $_POST['username'], $g);

                $grp = $_POST['group'];
                foreach ($grp as $g) {
                    $stmt->execute();
                }
            } else {
                $errMsg[] = 'Error adding User';
                $errMsg[] = $db->error;
            }
            $stmt->close();


        }


    }

?>

<!DOCTYPE html>
<html>
<head>
        <title>Add User</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="images/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
</head>
<body>
        <section class="adduser">

                <center><img src ="images/logo.gif"></center><br/>
                <form name="addUser" action="addUser.php" method="post" accept-charset="utf-8">
                        <ul>
                                <?php if (!empty($errMsg)) { ?>
                                    <li><?php echo join('<br />', $errMsg); ?></li>
                                <?php } ?>
                                <li> Username: <input type="text" name="username" placeholder="Username" required /></li>
                                <li> Password: <input type="password" name="password" placeholder="Password" required /></li>
                                <li> Confirm Password: <input type="password" name="confirmPassword" placeholder="Retype Password" required /></li>
                                <li>
                                    Groups:<br>
                                    <?php foreach($groups as $group) { ?>
                                        <input type="checkbox" name="group[]" value="<?php echo $group['GName'] ?>" /> <?php echo $group['GName'] ?><br />
                                    <?php } ?>
                                </li>
                                <li><input type="submit" name="submit" value="Create User" /></li>
                        </ul>
                </form>
        </section>
</body>
</html>
