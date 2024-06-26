<?php
session_start();
include'config.php';


//for upload
$target_dir = "uploads/profile_pics/";
$target_file = "";
$uploadOk = 0;
$imageFileType = "";
$uploadfileName = "";

// Check if the user is logged in
if (isset($_SESSION["SID"])) {
    $studentID = $_SESSION["SID"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $userName = mysqli_real_escape_string($conn, $_POST["userName"]);
        $userEmail = mysqli_real_escape_string($conn, $_POST["userEmail"]);
		$phoneNo = mysqli_real_escape_string($conn, $_POST["phoneNo"]);
        $program = mysqli_real_escape_string($conn, $_POST["program"]);
		$intakeBatch = mysqli_real_escape_string($conn, $_POST["intakeBatch"]);
        $mentorName = mysqli_real_escape_string($conn, $_POST["mentorName"]);
		$address = mysqli_real_escape_string($conn, $_POST["address"]);
		$state = mysqli_real_escape_string($conn, $_POST["state"]);
        $studyMotto = mysqli_real_escape_string($conn, $_POST["studyMotto"]);

        // Update user information in the database
        $updateQuery = "UPDATE user SET userName='$userName', userEmail='$userEmail', phoneNo='$phoneNo', program='$program', intakeBatch='$intakeBatch', mentorName='$mentorName', address='$address', state='$state', studyMotto='$studyMotto' WHERE studentID='$studentID'";

        if (mysqli_query($conn, $updateQuery)) {
            echo "Profile updated successfully!<br>";

            // Check if a new file is provided for upload
            if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == UPLOAD_ERR_OK) {
    $target_dir = "uploads/profile_pics/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file size is within limits and file type is allowed
    // Add more checks as needed

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        // Extract the filename from the full path
        $imgpath = basename($_FILES["fileToUpload"]["name"]);

        // Update photo name (filename) in the database
        $updatePhotoQuery = "UPDATE user SET imgpath='$imgpath' WHERE studentID='$studentID'";
        mysqli_query($conn, $updatePhotoQuery);
        echo "Profile picture updated successfully!<br>";
    } else {
        // Handle file upload error
        echo "Error uploading profile picture.";
    }
}

            echo '<a href="profile.php">Back</a>';
        } else {
            echo "Error updating profile: " . mysqli_error($conn);
        }
    } else {
        // Redirect to the edit profile page if accessed without a POST request
        header("location: edit_profile.php");
        exit();
    }
} else {
    // Redirect to the login page if the user is not logged in
    header("location: login.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
