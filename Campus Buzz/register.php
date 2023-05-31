<?php
$email = $_POST['email'];
$password = $_POST['password'];

$servername = "localhost";
$username = "chat";
$dbPassword = "Buzz@321";
$dbname = "king";


$conn = new mysqli($servername, $username, $dbPassword, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($email,$v_code)
{
  require ("PHPMailer/PHPMailer.php");
  require ("PHPMailer/SMTP.php");
  require ("PHPMailer/Exception.php");

  $mail = new PHPMailer(true);

  try {
    //Server settings
    $mail->isSMTP();                                          
    $mail->Host       = 'smtp.gmail.com';                   
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = 'campusbuzz24@gmail.com';               
    $mail->Password   = 'tfavhewxflgfjdfc';                               //BMSITM@#$$#@
    $mail->SMTPSecure = 'ssl';      
    $mail->Port       = 465;                                  

    $mail->setFrom('campusbuzz24@gmail.com', 'Campus_Buzz');
    $mail->addAddress($email);  

    $mail->isHTML(true);        
    $mail->Subject = 'Email Verification from Campus Buzz';
    $mail->Body    = "Thanks for registeration!
    Click the link below to verify the email address
    <a href='http://localhost/verify.php?email=$email&v_code=$v_code'>verify</a>";

    $mail->send();
    return true;
  }
 catch (Exception $e) {
  return false;
    }
}


// Check if the email already exists in the database
$checkEmailQuery = "SELECT email FROM userinformation WHERE email = '$email'";
$checkEmailResult = $conn->query($checkEmailQuery);

if ($checkEmailResult->num_rows > 0) {
  $conn->close();
  echo "Error: This email address is already registered.";
  exit();
}
$v_code = bin2hex(random_bytes(16));
// Insert the user information into the database
$sql = "INSERT INTO userinformation (email, password, `verification_code`, `is_verify`) VALUES ('$email', '$password','$v_code','0')";

if (($conn->query($sql) === TRUE) && sendMail($_POST['email'],$v_code)){
  $conn->close();
  header("Location: index.html");
  exit();
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
