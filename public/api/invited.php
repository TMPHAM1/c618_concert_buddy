<?php
header("Access-Control-Allow-Origin: *");
session_start();
$token  = "Gx1DjXxDTWZ0enhtlDVQ3YFl9XrbvXdPiqmIQts0ytn96Gob9wFVrmLURR"; //$_POST['token'];
require_once('mysqlconnect.php');
$output = [
    'success'=>false,
];
$query = "SELECT `trips`.`trip_name`,`trips`.`ID` AS `trip_id`, `trips`.`created_user_id`, `concerts`.`venue`, `concerts`.`artist`, `concerts`.`address`, `concerts`.`time`, 
`concerts`.`date`, `concerts`.`latitude`, `concerts`.`longitude`, `concerts`.`img`  
FROM `triptokens` 
JOIN `trips` 
ON `triptokens`.`trip_id` = `trips`.`ID`
JOIN `concerts`
ON `trips`.`concert_id` = `concerts`.`ID` WHERE  `tokens` = '$token'";

$result = mysqli_query($conn, $query);

if ($result) {
       $output['success'] = true;
       $row  = mysqli_fetch_assoc($result);
       $output['data'][] = $row;
}
else {
    $error = mysqli_error($conn);
    $output['error'] = "Database Error! + $error";
}
 $output = json_encode($output);
 print($output)

?> 