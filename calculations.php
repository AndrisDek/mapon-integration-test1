<?php
require_once 'db_conn.php';
require_once 'includes/functions.inc.php';

$fileContent = file_get_contents('https://mapon.com/integration/?key=6BD030BBB9E0E34C63672757DC065B8B&offset=50');
$dataPackage = json_decode($fileContent);
$hexString = $dataPackage->data[0];
$ioData = array();
$ioDataNotDecoded = array();

$hexLongitude = substr($hexString, 38, 8);
$hexLatitude = substr($hexString, 46, 8);

$longitude = hexToDecimal($hexLongitude);
$latitude = hexToDecimal($hexLatitude);

$decodedIoElements = decodeAllIoElements($hexString, $ioData);
$notDecodedIoElements = getAllIoElements($hexString, $ioDataNotDecoded);
insertDataIntoDatabase($conn, $longitude, $latitude, $decodedIoElements);
