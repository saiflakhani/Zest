
 <?php
$servername = "sql7.freesqldatabase.com";
$username = "sql7310820";
$password = "IpvMaWLchD";
$dbname="sql7310820";

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname,3306);
// Check connectionn 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "INSERT INTO reviewtable(rest_name,location,username,review) VALUES('".$_POST['restoName']."','".$_POST['restoLocality']."','".$_POST['personname']."','".$_POST['review']."');";
if ($conn->query($sql) === TRUE) {
    //echo "Database linked successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();
?> 

<html>
<head>
<title>Zest</title>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<style>
body {
    margin:0;
    padding:0;
    background:url(back.jpg);
    background-size:cover;
}
.search {
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    width:80px;
    height:80px;
    background:#fff;
    box-shadow:0 5px 20px rgba(0,0,0,.5);
    border-radius:4px;
    transition:width .5s;
    overflow:hidden;
}
.search.active {
    width:1000px;
}
.search label {
    position:absolute;
    top:10px;
    left:10px;
    width:calc(100% - 90px);
    height:60px;
    border:none;
    outline:none;
    font-size:28px;
    padding:0 10px;
    color:#000000;
    
    
}
.search.active .icon {
    background:#ff355a;
}
.icon {
    position:absolute;
    top:10px;
    right:10px;
    width:60px;
    height:60px;
    cursor:pointer;
    transition:.5s;
    border-radius:4px;
}
.search.active .icon:before {
    content:'';
    position:absolute;
    top:7px;
    left:13px;
    width:18px;
    height:30px;
    background:transparent;
    border:none;
    border-right:2px solid #fff;
    border-radius:0;
    transition:.5%;
    transform:rotate(45deg);
}
.search.active .icon:after {
    content:'';
    position:absolute;
    top:20px;
    left:13px;
    width:18px;
    height:30px;
    background:transparent;
    border:none;
    border-right:2px solid #fff;
    border-radius:0;
    transition:.5%;
    transform:rotate(-45deg);
}
.icon:before {
    content:'';
    position:absolute;
    top:12px;
    left:12px;
    width:24px;
    height:24px;
    background:transparent;
    border:2px solid #262626;
    border-radius:50%;
    transition:.5%;
}
.icon:after {
    content:'';
    position:absolute;
    top:25px;
    left:35px;
    width:18px;
    height:18px;
    background:transparent;
    border-left:2px solid #262626;
    border-radius:0;
    transform:rotate(-45deg);
    transition:.5%;
}
</style>
<script>
$(document).ready(function(){
    
        $('.search').toggleClass('active');
   
});


</script>
<body>
<form action="main.php" method="POST" id="search">
<div class="search">
   <center> <label>Thank you for your review. We are analysing it now! Stay put</label>
   
   </center>
    
</div>
<br><br>

   <center>
<a href="index.php" class="button btn-submit">Back to Home</a></center>
</form>

</body>


