<?php
$mysqli = new mysqli('54.68.72.28','cse503','cse503','cynic');
if($mysqli->connect_errno){
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}
?>
   