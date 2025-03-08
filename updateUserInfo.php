<?php
session_start();

include('bandConnect.php');

if (!isset($_SESSION['username'])) {
    die("Unauthorized");
}

$username = $_SESSION['username'];
$userQuery = "SELECT user_id FROM user WHERE username = '$username'";
$userResult = mysqli_query($conn, $userQuery);

if ($userResult && mysqli_num_rows($userResult) > 0) {
    $user = mysqli_fetch_assoc($userResult);
    $userId = $user['user_id'];
} else {
    die("User not found");
}

if (isset($_GET['field']) && isset($_GET['value'])) {
    $field = $_GET['field'];
    $value = $_GET['value'];

    if ($field === 'username') {
        $sql = "UPDATE user SET username = '$value' WHERE user_id = $userId";
        mysqli_query($conn, $sql);
        $_SESSION['username'] = $value; // Update session
    } elseif ($field === 'password') {
        $sql = "UPDATE user SET password = '$value' WHERE user_id = $userId";
        mysqli_query($conn, $sql);
    } elseif ($field === 'favorite_artist') {
        $sql = "UPDATE user SET artist_id = $value WHERE user_id = $userId";
        mysqli_query($conn, $sql);
    }
    echo "Success";
} else {
    die("Invalid request");
}
?>