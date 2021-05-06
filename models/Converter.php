<?php

class Converter
{
    private $conn;
    private $db_table = "data";
    public $latitude;
    public $longitude;
    public $ioData = array();
    public $ioDataNotDecoded = array();
    public $apiData;
    public $hexString;
    public $hexLatitude;
    public $hexLongitude;
    public $decimalLatitude;
    public $decimalLongitude;

    public function __construct($db, $package)
    {
        $this->conn = $db;
        $this->apiDataString = file_get_contents($package);
        $this->apiDataJSON = json_decode($this->apiDataString);
        $this->hexString = $this->apiDataJSON->data[0];
    }

    public function getGpsCoordinates()
    {
        $this->hexLatitude = substr($this->hexString, 38, 8);
        $this->hexLongitude = substr($this->hexString, 46, 8);
    }

    public function hexToDecimalLongitude()
    {
        $dot = '.';
        $decimal = hexdec($this->hexLongitude);
        $this->decimalLongitude = substr_replace($decimal, $dot, 2, 0);
        return $this->decimalLongitude;
    }

    public function hexToDecimalLatitude()
    {
        $dot = '.';

        $decimal = hexdec($this->hexLatitude);
        $this->decimalLatitude = substr_replace($decimal, $dot, 2, 0);
        return $this->decimalLatitude;
    }
    public function decodeAllIoElements()
    {
        $allElementsArray = array();
        for ($i = 74; $i < 106; $i += 4) {
            $ioID = hexdec(substr($this->hexString, $i, 2));
            $ioValue = hexdec(substr($this->hexString, $i + 2, 2));
            $allElementsArray += array($ioID => $ioValue);
        }
        for ($i = 108; $i < 150; $i += 6) {
            $ioID = hexdec(substr($this->hexString, $i, 2));
            $ioValue = hexdec(substr($this->hexString, $i + 2, 4));
            $allElementsArray += array($ioID => $ioValue);
        }
        for ($i = 152; $i < 182; $i += 10) {
            $ioID = hexdec(substr($this->hexString, $i, 2));
            $ioValue = hexdec(substr($this->hexString, $i + 2, 8));
            $allElementsArray += array($ioID => $ioValue);
        }
        $this->ioData = json_encode($allElementsArray);
        return $this->ioData;
    }

    public function getAllIoElements()
    {
        $allElementsArray = array();
        for ($i = 74; $i < 106; $i += 4) {
            $ioIdNotDecoded = substr($this->hexString, $i, 2);
            $ioValue = substr($this->hexString, $i + 2, 2);
            $allElementsArray += array($ioIdNotDecoded => $ioValue);
        }
        for ($i = 108; $i < 150; $i += 6) {
            $ioIdNotDecoded = substr($this->hexString, $i, 2);
            $ioValue = substr($this->hexString, $i + 2, 4);
            $allElementsArray += array($ioIdNotDecoded => $ioValue);
        }
        for ($i = 152; $i < 182; $i += 10) {
            $ioIdNotDecoded = substr($this->hexString, $i, 2);
            $ioValue = substr($this->hexString, $i + 2, 8);
            $allElementsArray += array($ioIdNotDecoded => $ioValue);
        }
        $this->ioDataNotDecoded = json_encode($allElementsArray);
        return $this->ioDataNotDecoded;
    }

    public function insert()
    {
        $sql = "INSERT INTO " . $this->db_table . "(longitude, latitude, iodata) VALUES (?,?,?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(1, $this->decimalLatitude);
        $stmt->bindParam(2, $this->decimalLongitude);
        $stmt->bindParam(3, $this->ioData);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function createLogs()
    {
        if (!file_exists("logs")) {
            mkdir("logs");
        }
        // Received package log
        file_put_contents("logs/package_" . date("j.n.Y") . ".log", $this->apiDataString . "\n", FILE_APPEND);

        // Decoded log
        $log = "Longitude:" . $this->decimalLongitude . "\nLatitude:" . $this->decimalLatitude . "\nIO Elements:" . $this->ioData . "\n";
        file_put_contents("logs/decoded_log_" . date("j.n.Y") . ".log", $log, FILE_APPEND);
    }
}
