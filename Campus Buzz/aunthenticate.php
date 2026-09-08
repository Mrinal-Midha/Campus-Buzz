<?php
require_once __DIR__ . '/config.php';

if (isset($_POST['email']) && isset($_POST['password'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT * FROM userinformation WHERE email=? AND password=?";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows >= 1) {
      $row = $result->fetch_assoc();
      if ($row['is_verify'] == 1) {
        // Email is verified, proceed with login
        header("Location: login.php");
        exit();
      } else {
        // Email is not verified
        echo '<script>alert("Email is not verified. Please verify your email before logging in."); window.location.href = "index.html";</script>';
        exit();
      }
    } else {
      // Invalid email or password
      echo '<script>alert("Invalid email or password."); window.location.href = "index.html";</script>';
      exit();
    }

    $stmt->close();
  } else {
    echo "Error: " . $conn->error;
  }

  $conn->close();
}
?>
