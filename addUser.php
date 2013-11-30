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
                                <li> Username: <input type="text" name="user" placeholder="Username" required /></li>
                                <li> Password: <input type="password" name="pass" placeholder="Password" required /></li>
                                <li> Confirm Password: <input type="password" name="confirmPass" placeholder="Retype Password" required /></li>
                                <li>
                                    Groups:<br>
                                    Administrator: <input type="checkbox" name="admin" value="admin"><br>
                                    Tag Member: <input type="checkbox" name="tmember" value="tember"><br>
                                    OE: <input type="checkbox" name="oe" value="oe"><br>
                                    User: <input type="checkbox" name="user" value="user">
                                </li>
                                <li><input type="submit" name="submit" value="Create User" /></li>
                        </ul>
                </form>
        </section>
</body>
</html>
