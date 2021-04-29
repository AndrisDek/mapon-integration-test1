<?php

function hexToDecimal($hex)
{
    $dot = '.';
    $decimal = hexdec($hex);
    $decimalWithDot = substr_replace($decimal, $dot, 2, 0);
    return $decimalWithDot;
}

function decodeAllIoElements($hexString, $allElementsArray)
{
    for ($i = 74; $i < 106; $i += 4) {
        $ioID = hexdec(substr($hexString, $i, 2));
        $ioValue = hexdec(substr($hexString, $i + 2, 2));
        $allElementsArray += array($ioID => $ioValue);
    }
    for ($i = 108; $i < 150; $i += 6) {
        $ioID = hexdec(substr($hexString, $i, 2));
        $ioValue = hexdec(substr($hexString, $i + 2, 4));
        $allElementsArray += array($ioID => $ioValue);
    }
    for ($i = 152; $i < 182; $i += 10) {
        $ioID = hexdec(substr($hexString, $i, 2));
        $ioValue = hexdec(substr($hexString, $i + 2, 8));
        $allElementsArray += array($ioID => $ioValue);
    }
    $encodedIoData = json_encode($allElementsArray);
    return $encodedIoData;
}

function getAllIoElements($hexString, $allElementsArray)
{
    for ($i = 74; $i < 106; $i += 4) {
        $ioIdNotDecoded = substr($hexString, $i, 2);
        $ioValue = substr($hexString, $i + 2, 2);
        $allElementsArray += array($ioIdNotDecoded => $ioValue);
    }
    for ($i = 108; $i < 150; $i += 6) {
        $ioIdNotDecoded = substr($hexString, $i, 2);
        $ioValue = substr($hexString, $i + 2, 4);
        $allElementsArray += array($ioIdNotDecoded => $ioValue);
    }
    for ($i = 152; $i < 182; $i += 10) {
        $ioIdNotDecoded = substr($hexString, $i, 2);
        $ioValue = substr($hexString, $i + 2, 8);
        $allElementsArray += array($ioIdNotDecoded => $ioValue);
    }
    $notDecodedIoData = json_encode($allElementsArray);
    return $notDecodedIoData;
}

function insertDataIntoDatabase($conn, $longitude, $latitude, $decodedIoElements) {
    $sql = "INSERT INTO data (longitude, latitude, iodata) VALUES (?, ?, ?)";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $param_longitude, $param_latitude, $param_encodedIoData);

        $param_longitude = $longitude;
        $param_latitude = $latitude;
        $param_encodedIoData = $decodedIoElements;

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}
