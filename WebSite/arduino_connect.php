<html>
<body>

<?php

$dbname = 'name';
$dbuser = 'root';
$dbpass = 'password';
$dbhost = 'localhost';

$connect = @mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

if(!$connect){
	echo "Error: " . mysqli_connect_error();
	exit();
}

echo "Connection Success!<br><br>";

$temperature = $_GET["temperature"];
$BPM= $_GET["BPM"];
$latitude = $_GET["latitude"];
$longitude = $_GET["longitude"];


$query = "INSERT INTO worlddata (id,name,homelat,homelng,latitude,longitude,heartbeat,bodytemp) VALUES (1,12.28620,75.131370,$latitude,$longitude,$BPM,$temperature)";
$result = mysqli_query($connect,$query);

echo "Insertion Success!<br>";

?>
</body>
</html>
