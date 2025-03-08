<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Submission</title>
    </head>
    <body>
        <h1>Signup Submission</h1>
        <?php
        include('bandConnect.php');

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            // Retrieve and sanitize form data
            $username = htmlspecialchars($_GET['username']);
            $password = htmlspecialchars($_GET['pass']);
            $name = htmlspecialchars($_GET['name']);
            $country = htmlspecialchars($_GET['userCountry']);
            $favArtist = htmlspecialchars($_GET['favArtist']);

            if (!empty($username) && !empty($password) && !empty($name) && !empty($country) && $favArtist > 0) {
                $sql = "INSERT INTO user (username, password, name, country, artist_id)
                    VALUES ('$username', '$password', '$name', '$country', $favArtist)";

                if (mysqli_query($conn, $sql)) {
                    echo "<p class='success'>Signup successful! Your account has been created.</p>";
                    echo "<a href='bandLogin.php'>Go to Login</a>";
                } else {
                    echo "<p class='error'>Error: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
                }
            } else {
                echo "<p class='error'>All fields are required. Please fill out the form completely.</p>";
            }

            mysqli_close($conn);
        } else {
            echo "<p class='error'>Invalid request method. Please submit the form properly.</p>";
        }
        ?>
    </body>
</html>
