<?php
include_once 'calculations.php';
include_once 'templates/display_results.tpl.php';

if (isset($_POST['decoded'])) {
    echo "<table style='position:absolute; top:150px;'>
        <tr style='background-color:lime'>
            <th>Izveidošanas laiks</th>
            <th>GPS</th>
            <th>IO Dati</th>
        </tr>
        <tr>
            <td>" . date("Y-m-d H:i:s") . "</td>" .
            "<td>" . $longitude . ", " . $latitude . "</td>" .
            "<td>" . $decodedIoElements . "</td>" .
        "</tr>
    </table>";
}
if (isset($_POST['notDecoded'])) {
    echo "<table style='position:absolute; top:150px;'>
        <tr style='background-color:lime'>
            <th>Izveidošanas laiks</th>
            <th>GPS</th>
            <th>IO Dati</th>
        </tr>
        <tr>
            <td>" . date("Y-m-d H:i:s") . "</td>" .
            "<td>" . $hexLongitude . ", " . $hexLatitude . "</td>" .
            "<td>"  . $notDecodedIoElements . "</td>" .
        "</tr>
    </table>";
}
