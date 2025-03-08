<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: bandLogin.php");
    exit;
}

include('bandConnect.php'); // Database connection

// Fetch user details
$username = $_SESSION['username'];
$userQuery = "SELECT user_id, artist_id, password FROM user WHERE username = '$username'";
$userResult = mysqli_query($conn, $userQuery);

if ($userResult && mysqli_num_rows($userResult) > 0) {
    $user = mysqli_fetch_assoc($userResult);
    $userId = $user['user_id'];
    $favoriteArtistId = $user['artist_id'];
    $password = $user['password'];
} else {
    die("User not found.");
}

// Fetch artist list for the dropdown
$artistDropdown = '';
$artistQuery = "SELECT artist_id, artist_name FROM artist";
$artistResult = mysqli_query($conn, $artistQuery);
if ($artistResult && mysqli_num_rows($artistResult) > 0) {
    while ($row = mysqli_fetch_assoc($artistResult)) {
        $selected = ($row['artist_id'] == $favoriteArtistId) ? "selected" : "";
        $artistDropdown .= "<option value='" . $row['artist_id'] . "' $selected>" . htmlspecialchars($row['artist_name']) . "</option>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Band Dashboard</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(to bottom, #FF6B6B, #FFA07A);
            font-family: 'Poppins', sans-serif;
            text-align: center;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        h1 {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 25px;
        }

        .container {
            width: 90%;
            max-width: 500px;
        }

        .card {
            background: #FF3E6C;
            padding: 25px;
            border-radius: 20px;
            margin-bottom: 20px;
            box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .card p {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .btn {
            background: #FFD700;
            color: #333;
            font-weight: 600;
            cursor: pointer;
            padding: 12px 20px;
            border-radius: 25px;
            border: none;
            transition: all 0.3s ease-in-out;
            display: inline-block;
            margin-top: 10px;
        }

        .btn:hover {
            background: #FF3E6C;
            color: #fff;
            transform: scale(1.05);
        }

        select, input {
            padding: 12px;
            border-radius: 25px;
            border: none;
            font-size: 1rem;
            text-align: center;
            outline: none;
            width: 80%;
        }

        select {
            background: #fff;
            color: #333;
        }

        /* Hide modals by default */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            width: 80%;
            max-width: 400px;
            color: #333;
            text-align: center;
        }

        .modal input {
            width: 90%;
            margin: 10px 0;
        }

        .close-btn {
            background: #FF3E6C;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>

    <script>
        function openModal(field) {
            document.getElementById(field + "Modal").style.display = "flex";
        }

        function closeModal(field) {
            document.getElementById(field + "Modal").style.display = "none";
        }

        function updateUserInfo(field) {
            let value = document.getElementById(field + "Input").value;

            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById(field + "Display").innerText = value;
                    closeModal(field);
                    alert("Updated successfully!");
                }
            };
            xhr.open("GET", "updateUserInfo.php?field=" + field + "&value=" + encodeURIComponent(value), true);
            xhr.send();
        }

        function updateFavoriteArtist() {
            let artistId = document.getElementById("favoriteArtistDropdown").value;

            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert("Favorite artist updated successfully!");
                }
            };
            xhr.open("GET", "updateUserInfo.php?field=favorite_artist&value=" + artistId, true);
            xhr.send();
        }

        // Close modal if clicking outside content
        window.onclick = function(event) {
            let modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });
        }
    </script>
</head>
<body>
    <?php include('bandNavbar.html'); ?>

    <h1>Your Profile</h1>
    <div class="container">

        <div class="card">
            <p>Username: <span id="usernameDisplay"><?php echo htmlspecialchars($username); ?></span></p>
            <button class="btn" onclick="openModal('username')">Edit</button>
        </div>

        <div class="card">
            <p>Password: <span id="passwordDisplay">••••••</span></p>
            <button class="btn" onclick="openModal('password')">Change</button>
        </div>

        <div class="card">
            <p>Favorite Artist:</p>
            <select id="favoriteArtistDropdown" onchange="updateFavoriteArtist()">
                <?php echo $artistDropdown; ?>
            </select>
        </div>

    </div>

    <!-- Modals -->
    <div id="usernameModal" class="modal">
        <div class="modal-content">
            <h3>Edit Username</h3>
            <input type="text" id="usernameInput">
            <button class="btn" onclick="updateUserInfo('username')">Save</button>
            <button class="close-btn" onclick="closeModal('username')">Cancel</button>
        </div>
    </div>

    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <h3>Change Password</h3>
            <input type="password" id="passwordInput">
            <button class="btn" onclick="updateUserInfo('password')">Save</button>
            <button class="close-btn" onclick="closeModal('password')">Cancel</button>
        </div>
    </div>
    </body>
</html>
