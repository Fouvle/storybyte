<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// $server_name = 'localhost';
// $user_name = 'root';
// $password = '';
// $db_name = 'StoryByte';


$server_name = 'localhost';
$user_name = 'fouvle.nkrumah';
$password = 'marSAM//++1969';
$db_name = 'webtech_fall2024_fouvle_nkrumah';

$conn = mysqli_connect($server_name, $user_name, $password, $db_name);
if (mysqli_connect_errno()) { 
    die(''. mysqli_connect_error());
}
else {
    // echo 'Connection Successful';mar
}

?>