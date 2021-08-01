<?php header('Refresh: 10'); ?>

<?php
$username = "root";
$password = "password";
$hostname = "localhost";
$database ="name";
$server = mysqli_connect($hostname,$username,$password);
$connection = mysqli_select_db($server,$database);

if (!$server)
{
echo "Connection lost";
}
$myquery = "SELECT * from `worlddata` WHERE name='Ram'";
$query = mysqli_query($server,$myquery);
$json_array = array();
while($row = mysqli_fetch_assoc($query))
{
//$json_array[]= $row;
$lat = $row['latitude'] ;
$lon = $row['longitude'];

}
//echo json_encode($json_array);
echo $file =  '{'  . PHP_EOL .  '"geometry" : {'  . PHP_EOL .  '"type" : "Point",'  . PHP_EOL .  '"coordinates" : [' . $lon . ',' . $lat .  ']'  . PHP_EOL .  '},'  . PHP_EOL .  ' "type" : "Feature",'  . PHP_EOL .  ' "properties" : {}'  . PHP_EOL .  '}';

$jsonfile = fopen("myfile.json", "w")  or die ("Unable to open file!");
fwrite($jsonfile, $file);
fclose($jsonfile);
?>
