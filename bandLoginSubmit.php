<!DOCTYPE html>
<?php
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Check Results</title>
    </head>
    <body>
        <h1>Login Check</h1>

        <?php
        include('bandConnect.php');

        if (isset($_GET['username'])) {
            $username = $_GET['username'] ?? '';
            $password = $_GET['pass'] ?? '';


            $sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);

                $_SESSION['username'] = $user['username'];

                header("Location: bandLandingPage.php");
                exit();
            } else {
                echo "<p style='color: red;'>Invalid username or password. Please try again.</p>";
                echo "<a href='bandLogin.php'>Go Back to Login</a>";
            }

            mysqli_close($conn);
        } else {
            //header("Location: bandLogin.php");
            exit();
        }
        ?>
    </body>
</html>