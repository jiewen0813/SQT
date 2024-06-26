<?php
include'config.php';

if(isset($_GET["id"]) && $_GET["id"] != "") {
    $id = $_GET["id"];

    // Retrieve the image path before deleting the record
    $getImagePathQuery = "SELECT img_path FROM challenge WHERE ch_id=" . $id;
    $result = mysqli_query($conn, $getImagePathQuery);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $imagePath = $row["img_path"];

        // Delete the record from the challenge table
        $deleteChallengeQuery = "DELETE FROM challenge WHERE ch_id=" . $id;
        if (mysqli_query($conn, $deleteChallengeQuery)) {
            echo "Record deleted successfully.<br>";

            // Check if there's an image associated with the record
            if (!empty($imagePath) && file_exists("uploads/challenge/" . $imagePath)) {
                // If the image exists, delete it
                unlink("uploads/challenge/" . $imagePath);
                echo "Image deleted successfully.<br>";
            }

            echo '<a href="my_challenge.php">Back</a>';
        } else {
            echo "Error deleting record: " . mysqli_error($conn) . "<br>";
            echo '<a href="my_challenge.php">Back</a>';
        }
    } else {
        echo "Error retrieving image path: " . mysqli_error($conn) . "<br>";
        echo '<a href="my_challenge.php">Back</a>';
    }
}

mysqli_close($conn);
?>
