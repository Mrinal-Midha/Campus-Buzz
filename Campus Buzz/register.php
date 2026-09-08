<?php
require_once __DIR__ . '/config.php';

$email = $_POST['email'];
$password = $_POST['password'];

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

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
    $mail->Username   = SMTP_USERNAME;
    $mail->Password   = SMTP_PASSWORD;
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

    $mail->setFrom(SMTP_USERNAME, 'Campus_Buzz');
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
$checkEmailQuery = "SELECT email FROM userinformation WHERE email = ?";
$checkEmailStmt = $conn->prepare($checkEmailQuery);
$checkEmailStmt->bind_param("s", $email);
$checkEmailStmt->execute();
$checkEmailResult = $checkEmailStmt->get_result();

if ($checkEmailResult->num_rows > 0) {
  $conn->close();
  echo "Error: This email address is already registered.";
  exit();
}
$v_code = bin2hex(random_bytes(16));
// Insert the user information into the database
$sql = "INSERT INTO userinformation (email, password, `verification_code`, `is_verify`) VALUES (?, ?, ?, '0')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $email, $password, $v_code);

if (($stmt->execute()) && sendMail($_POST['email'],$v_code)){
  $conn->close();
  header("Location: index.html");
  exit();
} else {
  echo "Error: " . $conn->error;
}

$conn->close();
?>
