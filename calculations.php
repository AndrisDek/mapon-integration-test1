<?php
require_once 'config/Database.php';
require_once 'models/Converter.php';

$database = new Database();
$db = $database->connect();

$item = new Converter($db,'https://mapon.com/integration/?key=6BD030BBB9E0E34C63672757DC065B8B&offset=50');
$item->getGpsCoordinates();
$longitude = $item->hexToDecimalLongitude();
$latitude = $item->hexToDecimalLatitude();
$decodedIoElements = $item->decodeAllIoElements();
$notDecodedIoElements = $item->getAllIoElements();
$hexLongitude = $item->hexLongitude;
$hexLatitude = $item->hexLatitude;
$item->createLogs();

if ($item->insert()) {
    return true;
} else {
    echo "Didn't insert.";
}