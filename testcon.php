<?php
$mysqli_connection = new MySQLi('18.215.143.16', 'acllcmas_acllc01', 'mnG.t6WpIpUq', 'acllcmas_mhr');
if ($mysqli_connection->connect_error) {
   echo "Not connected, error: " . $mysqli_connection->connect_error;
}
else {
   echo "Connected.";
}
?>