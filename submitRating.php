<?php
session_start();
include('bandConnect.php'); 

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    die("Unauthorized");
}

// Get the user ID
$username = $_SESSION['username'];
$userQuery = "SELECT user_id FROM user WHERE username = '$username'";
$userResult = mysqli_query($conn, $userQuery);

if ($userResult && mysqli_num_rows($userResult) > 0) {
    $userId = mysqli_fetch_assoc($userResult)['user_id'];
} else {
    die("User not found");
}

// Insert or update the rating
if (isset($_GET['album_id']) && isset($_GET['rating'])) {
    $albumId = intval($_GET['album_id']);
    $rating = intval($_GET['rating']);

    $checkQuery = "SELECT * FROM rating WHERE user_id = $userId AND album_id = $albumId";
    $checkResult = mysqli_query($conn, $checkQuery);

    if ($checkResult && mysqli_num_rows($checkResult) > 0) {
        $updateQuery = "UPDATE rating SET rating_score = $rating WHERE user_id = $userId AND album_id = $albumId";
        mysqli_query($conn, $updateQuery);
    } else {
        $insertQuery = "INSERT INTO rating (user_id, album_id, rating_score) VALUES ($userId, $albumId, $rating)";
        mysqli_query($conn, $insertQuery);
    }
    echo "Success";
}
?>