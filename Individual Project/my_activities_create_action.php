<?PHP
include'config.php';

//variables
$action="";
$id="";
$sem = "";
$year = "";
$activities =" ";
$position = "";

//for upload
$target_dir = "uploads/activities/";
$target_file = "";
$uploadOk = 0;
$imageFileType = "";
$uploadfileName = "";

// Retrieve studentID from the form
$studentID = $_POST["studentID"];

//this block is called when button Submit is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Values for add or edit
$sem = isset($_POST["sem"]) ? $_POST["sem"] : "";
$year = isset($_POST["year"]) ? $_POST["year"] : "";
$activities = isset($_POST["activities"]) ? trim($_POST["activities"]) : "";
$position = isset($_POST["position"]) ? trim($_POST["position"]) : "";
$filetmp = isset($_FILES["fileToUpload"]) ? $_FILES["fileToUpload"] : "";
$uploadfileName = isset($filetmp["name"]) ? $filetmp["name"] : "";


//Check if there is an image to be uploaded
//IF no image
if(isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["name"] == ""){
$sql = "INSERT INTO activities (studentID, sem, year, activities, position, img_path)
        VALUES ('$studentID', $sem, '$year', '$activities', '$position', '$uploadfileName')";

$status = insertTo_DBTable($conn, $sql);
if ($status) {
echo "Form data saved successfully!<br>";
echo '<a href="my_activities.php">Back</a>';
} else {
echo '<a href="my_activities.php">Back</a>';
}
}
//IF there is image
else if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == UPLOAD_ERR_OK) {
//Variable to determine for image upload is OK
$uploadOk = 1;
$filetmp = $_FILES["fileToUpload"];
//file of the image/photo file
$uploadfileName = $filetmp["name"];
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if file already exists
if (file_exists($target_file)) {
echo "ERROR: Sorry, image file $uploadfileName already exists.<br>";
$uploadOk = 0;
}
// Check file size <= 488.28KB or 500000 bytes
if ($_FILES["fileToUpload"]["size"] > 500000) {
echo "ERROR: Sorry, your file is too large. Try resizing your image.<br>";
$uploadOk = 0;
}
// Allow only these file formats

if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
echo "ERROR: Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
$uploadOk = 0;
}
//If uploadOk, then try add to database first
//uploadOK=1 if there is image to be uploaded, filename not exists, file size is ok and format ok
if($uploadOk){
$sql = "INSERT INTO activities (studentID, sem, year, activities, position, img_path)
        VALUES ('$studentID', $sem, '$year', '$activities', '$position', '$uploadfileName')";

$status = insertTo_DBTable($conn, $sql);
if ($status) {
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
//Image file successfully uploaded
//Tell successfull record
echo "Form data saved successfully!<br>";
echo '<a href="my_activities.php">Back</a>';
}
else{
//There is an error while uploading image
echo "Sorry, there was an error uploading your file.<br>";
echo '<a href="javascript:history.back()">Back</a>';
}
}
else {
echo '<a href="javascript:history.back()">Back</a>';
}
}
else{
echo '<a href="javascript:history.back()">Back</a>';
}
}
}
//close db connection

mysqli_close($conn);
//Function to insert data to database table
function insertTo_DBTable($conn, $sql){
if (mysqli_query($conn, $sql)) {
return true;
} else {
echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
return false;
}
}
?>