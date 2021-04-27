<?php
require_once 'db_conn.php';

$fileContent = file_get_contents('https://mapon.com/integration/?key=6BD030BBB9E0E34C63672757DC065B8B&offset=50');
$dataPackage = json_decode($fileContent);
$hexString = $dataPackage->data[0];
$ioData = array();
$ioDataNotDecoded = array();

$hexLongitude = substr($hexString, 38, 8);
$hexLatitude = substr($hexString, 46, 8);

function hexToDecimal($hex) {
    $dot = '.';
    $decimal = hexdec($hex);
    $decimalWithDot = substr_replace($decimal, $dot, 2, 0);
    return $decimalWithDot;
}

$longitude = hexToDecimal($hexLongitude);
$latitude = hexToDecimal($hexLatitude);

// Getting all IO ID and their Values
for ($i = 74; $i < 106; $i += 4) {
    $ioID = hexdec(substr($hexString, $i, 2));
    $ioValue = hexdec(substr($hexString, $i + 2, 2));
    $ioData += array($ioID => $ioValue);
}
for ($i = 108; $i < 150; $i += 6) {
    $ioID = hexdec(substr($hexString, $i, 2));
    $ioValue = hexdec(substr($hexString, $i + 2, 4));
    $ioData += array($ioID => $ioValue);
}
for ($i = 152; $i < 182; $i += 10) {
    $ioID = hexdec(substr($hexString, $i, 2));
    $ioValue = hexdec(substr($hexString, $i + 2, 8));
    $ioData += array($ioID => $ioValue);
}
$encodedIoData = json_encode($ioData);

// Not decoded IO ID and Values
for ($i = 74; $i < 106; $i += 4) {
    $ioIdNotDecoded = substr($hexString, $i, 2);
    $ioValue = substr($hexString, $i + 2, 2);
    $ioDataNotDecoded += array($ioIdNotDecoded => $ioValue);
}
for ($i = 108; $i < 150; $i += 6) {
    $ioIdNotDecoded = substr($hexString, $i, 2);
    $ioValue = substr($hexString, $i + 2, 4);
    $ioDataNotDecoded += array($ioIdNotDecoded => $ioValue);
}
for ($i = 152; $i < 182; $i += 10) {
    $ioIdNotDecoded = substr($hexString, $i, 2);
    $ioValue = substr($hexString, $i + 2, 8);
    $ioDataNotDecoded += array($ioIdNotDecoded => $ioValue);
}
$notDecodedIoData = json_encode($ioDataNotDecoded);

// Inserting into Database
$sql = "INSERT INTO data (longitude, latitude, iodata) VALUES (?, ?, ?)";
if ($stmt = mysqli_prepare($conn,$sql)) {
    mysqli_stmt_bind_param($stmt, "sss", $param_longitude, $param_latitude, $param_encodedIoData);

    $param_longitude = $longitude;
    $param_latitude = $latitude;
    $param_encodedIoData = $encodedIoData;
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

if(isset($_POST['decoded'])) {
    echo "<table style='position:absolute; top:150px;'>
        <tr style='background-color:lime'>
            <th>Izveidošanas laiks</th>
            <th>GPS</th>
            <th>IO Dati</th>
        </tr>
        <tr>
            <td>" . date("Y-m-d H:i:s") . "</td>" . 
            "<td>" . $longitude . ", " . $latitude . "</td>" . 
            "<td>" . $encodedIoData . "</td>" . 
        "</tr>
    </table>";
}
if(isset($_POST['notDecoded'])) {
    echo "<table style='position:absolute; top:150px;'>
        <tr style='background-color:lime'>
            <th>Izveidošanas laiks</th>
            <th>GPS</th>
            <th>IO Dati</th>
        </tr>
        <tr>
            <td>" . date("Y-m-d H:i:s") . "</td>" . 
            "<td>" . $hexLongitude . ", " . $hexLatitude . "</td>" . 
            "<td>"  . $notDecodedIoData . "</td>" . 
        "</tr>
    </table>";
}

/* echo "<br>" . "Timestamp:" . substr($hexString, 20, 16);
echo "<br>" . "Priority:" . substr($hexString, 36, 2);
echo "<br>" . "Longitude:" . $hexLongitude . "<br>";
echo "Latitude:" . $hexLatitude . "<br>";
echo "Altitude:" . substr($hexString, 54, 4) . "<br>";
echo "Angle:" . substr($hexString, 58, 4) . "<br>";
echo "Satellites:" . substr($hexString, 62, 2) . "<br>";
echo "Speed:" . substr($hexString, 64, 4) . "<br>";
echo "Event IO ID:" . substr($hexString, 68, 2) . "<br>";
echo "N of Total ID:" . substr($hexString, 70, 2) . "<br>";

echo "N1 of One Byte IO:" . substr($hexString, 72, 2) . "<br>";
echo "1'st IO ID:" . substr($hexString, 74, 2) . "<br>";
echo "1'st IO Value:" . substr($hexString, 76, 2) . "<br>";
echo "2'st IO ID:" . substr($hexString, 78, 2) . "<br>";
echo "2'st IO Value:" . substr($hexString, 80, 2) . "<br>";
echo "3'st IO ID:" . substr($hexString, 82, 2) . "<br>";
echo "3'st IO Value:" . substr($hexString, 84, 2) . "<br>";
echo "4'st IO ID:" . substr($hexString, 86, 2) . "<br>";
echo "4'st IO Value:" . substr($hexString, 88, 2) . "<br>";
echo "5'st IO ID:" . substr($hexString, 90, 2) . "<br>";
echo "5'st IO Value:" . substr($hexString, 92, 2) . "<br>";
echo "6'st IO ID:" . substr($hexString, 94, 2) . "<br>";
echo "6'st IO Value:" . substr($hexString, 96, 2) . "<br>";
echo "7'st IO ID:" . substr($hexString, 98, 2) . "<br>";
echo "7'st IO Value:" . substr($hexString, 100, 2) . "<br>";
echo "8'st IO ID:" . substr($hexString, 102, 2) . "<br>";
echo "8'st IO Value:" . substr($hexString, 104, 2) . "<br>";

echo "N2 of Two Bytes IO:" . substr($hexString, 106, 2) . "<br>";
echo "1'st IO ID:" . substr($hexString, 108, 2) . "<br>";
echo "1'st IO Value:" . substr($hexString, 110, 4) . "<br>";
echo "2'st IO ID:" . substr($hexString, 114, 2) . "<br>";
echo "2'st IO Value:" . substr($hexString, 116, 4) . "<br>";
echo "3'st IO ID:" . substr($hexString, 120, 2) . "<br>";
echo "3'st IO Value:" . substr($hexString, 122, 4) . "<br>";
echo "4'st IO ID:" . substr($hexString, 126, 2) . "<br>";
echo "4'st IO Value:" . substr($hexString, 128, 4) . "<br>";
echo "5'st IO ID:" . substr($hexString, 132, 2) . "<br>";
echo "5'st IO Value:" . substr($hexString, 134, 4) . "<br>";
echo "6'st IO ID:" . substr($hexString, 138, 2) . "<br>";
echo "6'st IO Value:" . substr($hexString, 140, 4) . "<br>";
echo "7'st IO ID:" . substr($hexString, 144, 2) . "<br>";
echo "7'st IO Value:" . substr($hexString, 146, 4) . "<br>";

echo "N4 of Four Bytes IO:" . substr($hexString, 150, 2) . "<br>";
echo "1'st IO ID:" . substr($hexString, 152, 2) . "<br>";
echo "1'st IO Value:" . substr($hexString, 154, 8) . "<br>";
echo "2'st IO ID:" . substr($hexString, 162, 2) . "<br>";
echo "2'st IO Value:" . substr($hexString, 164, 8) . "<br>";
echo "3'st IO ID:" . substr($hexString, 172, 2) . "<br>";
echo "3'st IO Value:" . substr($hexString, 174, 8) . "<br>";

echo "N8 of Eight bytes IO:" . substr($hexString, 182, 2) . "<br>";
echo "Number of Data 2(Number of Total Records):" . substr($hexString, 184, 2) . "<br>";
echo "CRC-16:" . substr($hexString, 186, 8) . "<br>";
*/
?>