<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: bandLogin.php");
    exit;
}

include('bandConnect.php'); 

if (isset($_GET['country'])) {
    $country = $_GET['country'] ?? '';
    $username = $_SESSION['username'];
    $userQuery = "SELECT artist_id FROM user WHERE username = '$username'";
    $userResult = mysqli_query($conn, $userQuery);
    $favoriteArtistId = 0;

    if ($userResult && mysqli_num_rows($userResult) > 0) {
        $favoriteArtistRow = mysqli_fetch_assoc($userResult);
        $favoriteArtistId = $favoriteArtistRow['artist_id'];
    }

    $sql = "SELECT artist_id, artist_name, artist_country FROM artist";
    if (!empty($country)) {
        $sql .= " WHERE artist_country = '$country'";
    }

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        echo "<table>
                <thead>
                    <tr>
                        <th>Artist Name</th>
                        <th>Country</th>
                        <th>Favorite</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = mysqli_fetch_assoc($result)) {
            $isFavorite = ($row['artist_id'] == $favoriteArtistId) ? "⭐ Yes" : "No";
            echo "<tr>
                    <td>" . htmlspecialchars($row['artist_name']) . "</td>
                    <td>" . htmlspecialchars($row['artist_country']) . "</td>
                    <td>" . $isFavorite . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No artists found.</p>";
    }
    exit; // Stop further execution for AJAX response
}

$countryDropdown = '';
$countryQuery = "SELECT DISTINCT artist_country FROM artist";
$countryResult = mysqli_query($conn, $countryQuery);
if ($countryResult && mysqli_num_rows($countryResult) > 0) {
    while ($row = mysqli_fetch_assoc($countryResult)) {
        $countryDropdown .= "<option value='" . htmlspecialchars($row['artist_country']) . "'>" . htmlspecialchars($row['artist_country']) . "</option>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Artists</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom, #FF6B6B, #FFA07A);
            color: #fff;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        h1 {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 20px;
            color: #FFF;
            text-transform: uppercase;
        }

        /* Filter Section */
        .filter-section {
            background: #FF3E6C;
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 20px;
            width: 80%;
            max-width: 600px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
        }

        label {
            font-size: 1.2rem;
            margin-right: 10px;
            font-weight: 600;
        }

        select {
            padding: 12px;
            border-radius: 25px;
            border: none;
            background: #FFD700;
            color: #333;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
        }

        select:hover {
            background: #FFA500;
            color: #FFF;
        }

        table {
            width: 90%;
            max-width: 700px;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 15px;
            overflow: hidden;
            background: #fff;
            color: #333;
        }

        thead {
            background: #FF3E6C;
            color: #fff;
            text-transform: uppercase;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background: #FFD1DC;
        }

        tbody tr:hover {
            background: #FF85A2;
            color: #fff;
            transition: 0.3s;
        }

        p {
            font-size: 1.2rem;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 10px;
        }
    </style>
    <script>
        function fetchArtists() {
            const country = document.getElementById("countryFilter").value;

            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("artistDisplay").innerHTML = xhr.responseText;
                }
            };
            xhr.open("GET", "?country=" + country, true);
            xhr.send();
        }

        window.onload = fetchArtists; // Fetch all artists on page load
    </script>
</head>
<body>
    <?php include('bandNavbar.html'); ?>
    <br>
    <br>
    <br>

    <h1>Artist Display</h1>

    <div class="filter-section">
        <label for="countryFilter">Filter by Country:</label>
        <select id="countryFilter" onchange="fetchArtists()">
            <option value="">All Countries</option>
            <?php echo $countryDropdown; ?>
        </select>
    </div>

    <div id="artistDisplay">
    </div>
    </body>
</html>
