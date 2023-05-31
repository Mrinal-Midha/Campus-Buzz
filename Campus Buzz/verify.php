<?php
// Database connection
$servername = "localhost";
$username = "chat";
$dbPassword = "Buzz@321";
$dbname = "king";

$con = mysqli_connect($servername, $username, $dbPassword, $dbname);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Include authentication file
require("aunthenticate.php");

if (isset($_GET['email']) && isset($_GET['v_code'])) {
    $email = $_GET['email'];
    $v_code = $_GET['v_code'];

    $query = "SELECT * FROM userinformation WHERE email=? AND verification_code=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ss", $email, $v_code);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if ($row['is_verify'] == 0) {
            $update = "UPDATE userinformation SET is_verify='1' WHERE email=?";
            $stmt = mysqli_prepare($con, $update);
            mysqli_stmt_bind_param($stmt, "s", $row['email']);
            if (mysqli_stmt_execute($stmt)) {
                echo "
                    <script>
                    alert('Email verification successful');
                    window.location.href = 'index.html';
                    </script>
                    ";
            } else {
                echo "
                    <script>
                    window.location.href = 'index.html';
                    </script>
                    ";
            }
        } else {
            echo "
                <script>
                alert('Server down');
                window.location.href = 'index.html';
                </script>
                ";
        }
    } else {
        echo "
        <script>
        alert('Cannot run query');
        window.location.href = 'index.html';
        </script>
        ";
    }
}

// Close the database connection
mysqli_close($con);
?>
