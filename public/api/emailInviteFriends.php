<?php
header("Access-Control-Allow-Origin: *");
session_start();
require_once('email_config.php');
require_once('mysqlconnect.php');
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';
$host = $_SERVER['HTTP_HOST'];
$data= $_SESSION['tripData'][0];
$_POST = json_decode(file_get_contents('php://input'), true);
foreach($_POST['emails'] as $values) {
$values = stripslashes(htmlentities($values));
}
$emails = $_POST['emails'];
$trip_id = $data['trip_id'];
$trip_name = $data['trip_name'];
$name = $_SESSION['user_data'][0]['Name'];


use PHPMailer\PHPMailer\PHPMailer;
$url = "/acceptance-page?token=";



function token() {
 $output = '';
 $alphanum = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
 $alphanumLength = strlen($alphanum);
 for ($i = 0; $i < $alphanumLength; $i++) {
    $randomNum = rand(0,$alphanumLength);
$output .= $alphanum["$randomNum"];
 }
 
 return $output;

}

foreach ($emails as $value) {
$token = token();

$mail = new PHPMailer;          
$mail->SMTPDebug = 3;                                 // Enable verbose debug output. Change to 0 to disable debugging output.
$mail->isSMTP();                                     // Set mailer to use SMTP.
$mail->Host = 'smtp.gmail.com';                     // Specify main and backup SMTP servers.
$mail->SMTPAuth = true;                            // Enable SMTP authentication
$mail->Username = EMAIL_USER;                     // SMTP username
$mail->Password = EMAIL_PASS;                    // SMTP password
$mail->SMTPSecure = 'tls';                      // Enable TLS encryption, `ssl` also accepted, but TLS is a newer more-secure encryption
$mail->Port = 587;                             // TCP port to connect to
$options = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
$mail->smtpConnect($options);
$mail->From = 'concertbuddy.mailserver@gmail.com';     // sender's email address (shows in "From" field)
$mail->FromName = "Concert Buddy";         // sender's name (shows in "From" field)
 $mail->addAddress("$value");
// $mail->addAddress("tmpham1@uci.edu","Tien");
$mail->addReplyTo('example@gmail.com');    // Add a reply-to address
                                          // Add attachments
                                         // Optional name
$mail->isHTML(true);                    // Set email format to HTML

$mail->Subject = 'You have been invited to Concert Buddy!';
// $mail->Body    = "Hello <br>  
// You have been invited to $name's trip. This will hold all the information in the trip below. 
// Click the link provided below to sign up and join the trip. Welcome to concert buddy!<br><br><br><a href=\"".$query.$token."\">Accept Trip</a><br>"; 

$mail->Body    = "<body style='font-family: Arial, Helvetica, sans-serif;'>
<header style='color:white;background-color: #2A363B; height: 100px; text-align: center;'>
    <img src='https://lh3.googleusercontent.com/NtTu-MTmFnFEBn9SKaXqZgvz2V4l1JIs4fVT3lKQFJLQYVO3rppKLvqJvXNpebua4uB9utnqbBkiEFciz4tMG6Gqr7al89wmGqgYPAefKOkrEaRQ3JLfCPYgoIt3FqbwJDLwN7ptWg=w2400' style='height: 75px; margin-left: 10px; margin-top: 10px; float: left;'>
    <h2 style='color: #FF847C; display: inline-block; padding-top: 15px;'>CONCERT
        BUDDY</h2>
</header>
<div style='color: #2A363B; margin-top: 30px;'>
    <p>Hello!</p>
    <p>You have been invited by your friend, <b>".$name."</b> to go to <em>".$trip_name.".</em> Click the link below to accept or
        decline the invitation.</p>
    <div style='text-align: center;'>
   <p style='color:#FF847C'>Invitation Link:</p> 
   <a href=\"http://".$host.$url.$token."\" style='color:blue;'> http://".$host.$url.$token."</a>
    <p style='text-align: center'> Your invite will be deleted after you accept! </p>
    </div>
    <p>Enjoy the concert!</p>
    <p>-Your friends at Concert Buddy</p>
</div>
</body>";


$mail->AltBody = "Hello,
You have been invited to $name's trip. This will hold all the information in the trip below. 
Click the link provided below to sign up and join the trip. Welcome to concert buddy!".$host.$url.$token; 

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}

print(json_encode($token));
    $output = [
        'success'=> false,
    ];
    $query = "INSERT INTO `triptokens`(`trip_id`,`tokens`) VALUES ('$trip_id', '$token')";
    $result = mysqli_query($conn, $query); 
    if($result) {
        if(mysqli_affected_rows($conn) > 0) {
            $output['success'] = true;
            

        }
        
    }
    else {
        echo ("database error! ");
    }
}
?>
