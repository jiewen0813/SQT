<?php
session_start();
include'config.php';
?>
<html>
<head>
<title>Login Action</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
	<link rel="stylesheet" type="text/css" href="mystyle.css" media="screen" />
</head>
<body>
<h2>Login Information</h2>
<?php
//login values from login form
$userEmail = $_POST['userEmail']; 
$userPwd = $_POST['userPwd'];

$sql = "SELECT * FROM user WHERE userEmail='$userEmail' LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {	
    // Check password hash
    $row = mysqli_fetch_assoc($result);
    if (password_verify($_POST['userPwd'], $row['userPwd'])) {
        // Set session variables
        $_SESSION["SID"] = $row["studentID"];
        $_SESSION["userName"] = $row["userName"];
        // Set logged-in time
        $_SESSION['loggedin_time'] = time();

        // Display a JavaScript toast notification
        echo '<script type="text/javascript">
            // Use a toast notification library or create your own
            alert("Login successful! Welcome, ' . htmlspecialchars($row["userName"]) . '");
            window.location.href = "profile.php";
        </script>';
        exit(); // Make sure to exit after the alert

    } else {
        // Handle incorrect password
        echo 'Login error, user email and password are incorrect.<br>';
        echo '<a href="login.php"> | Login |</a> &nbsp;&nbsp;&nbsp; <br>';
    }
} else {
    // Handle user not found
    echo "Login error, user does not exist. <br>";
    echo '<a href="register.php"> | Register |</a>&nbsp;&nbsp;&nbsp; <br>';	
}


mysqli_close($conn);
?>
</body>
</html>