<?php

$fileContent = file_get_contents('https://mapon.com/integration/?key=6BD030BBB9E0E34C63672757DC065B8B&offset=50');
$dataPackage = json_decode($fileContent);
$hexString = $dataPackage->data[0];

$hexLongitude = substr($hexString, 38, 8);
$hexLatitude = substr($hexString, 46,8);
echo $hexString . "<br>";

echo "Longitude: " . $hexLongitude . "<br>";
echo "Latitude: " . $hexLatitude . "<br>"; 

$decimalLongitude = hexdec($hexLongitude);
$decimalLatitude = hexdec($hexLatitude);
echo $decimalLongitude;
echo $decimalLatitude;