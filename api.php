 <?php
$servername = "sql7.freesqldatabase.com";
$username = "sql7310820";
$password = "IpvMaWLchD";
$dbname="sql7310820";
$i = 1;
// Create connection
$conn = new mysqli($servername, $username, $password,$dbname,3306);
// Check connectionn 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "SELECT * FROM reviewtable";
$result= mysqli_query($conn,$sql);
$count = mysqli_num_rows($result);
if ($count>0) {
	$review = array();
    while($row = mysqli_fetch_assoc($result)){
    	$review[] = array('rest_name' => $row['rest_name'],'location' => $row['location'],'username' => $row['username'],'review' => $row['review']);
    	$response['data'] = $review;
    }
} else {
$response['data'] = '';
}
echo json_encode($response);
$conn->close();
?> 
