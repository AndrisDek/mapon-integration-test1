<?php

if (!file_exists("logs") && is_writable("logs")) {
    mkdir("logs");
}
// Received package log
file_put_contents("logs/package_".date("j.n.Y").".log", $fileContent . "\n", FILE_APPEND);

// Decoded log
$log = "Longitude:" . $longitude . "\nLatitude:" . $latitude . "\nIO Elements:" . $decodedIoElements . "\n";
file_put_contents("logs/decoded_log_".date("j.n.Y").".log", $log, FILE_APPEND);
