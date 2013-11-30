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
                                <?php if ($error) { ?>
                                        <li>
                                                Invalid Username or Password
                                        </li>
                                <?php } ?>
                                <li><input type="text" name="username" placeholder="Username" required /></li>
                                <li><input type="password" name="password" placeholder="Password" required /></li>
                                <li><input type="password" name="confirmPass" placeholder="Retype Password" required /></li>
                                <li>
                                        <select name="select">
                                                <option value="Administrator"> </option>
                                                <option value="Tag Members"> </option>
                                                <option value="OE"> </option>
                                                <option value="User"> </option>
                                        </select>
                                Group:
                                </li>
                                <li><input type="submit" name="submit" value="Create User" /></li>
                        </ul>
                </form>
        </section>
</body>
</html>