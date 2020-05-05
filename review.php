<?php

$servername = "sql7.freesqldatabase.com";
$username = "sql7310820";
$password = "IpvMaWLchD";
$dbname="sql7310820";

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);
// Check connectionn 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if( isset($_GET['type']) ) if( $_GET['type'] == 'getRevs' ) getReviews($_GET['resName'],$_GET['resLocality']);
/*
// Create database
$sql = "INSERT INTO reviewtable(rest_name,location,username,review) VALUES('".$_POST['restoName']."','".$_POST['restoLocality']."','".$_POST['personname']."','".$_POST['review']."');";
if ($conn->query($sql) === TRUE) {
    //echo "Database linked successfully";
} else {
    echo "Error creating database: " . $conn->error;
}*/

function getReviews($n, $m) {
	global $conn;
	$qrevname = "select username,review from reviewtable where rest_name='".$n."' AND location='".$m."'";
	$resrev = $conn->query($qrevname);
	if($resrev) {
		$rowsrev = $resrev->fetch_all(MYSQLI_ASSOC);
	
		if( count($rowsrev)<1) echo "No reviews available";
		else {
			for($i=0;$i<count($rowsrev);$i++) {
				echo $rowsrev[$i]['username'].",".$rowsrev[$i]['review'].".";
			}
		}
	} else {
		echo "No reviews available.";
	}
}

$conn->close();
?> 

