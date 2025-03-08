<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: bandLogin.php");
    exit;
}

include('bandConnect.php');

$username = $_SESSION['username'];
$userQuery = $conn->prepare("SELECT user_id FROM user WHERE username = ?");
$userQuery->bind_param("s", $username);
$userQuery->execute();
$userResult = $userQuery->get_result();
$userId = $userResult->fetch_assoc()['user_id'] ?? null;

if (!$userId) exit;

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET['album_id'], $_GET['rating'])) {
        $albumId = intval($_GET['album_id']);
        $rating = intval($_GET['rating']);

        if ($rating >= 1 && $rating <= 10) {
            $conn->query("DELETE FROM rating WHERE user_id = $userId AND album_id = $albumId");
            $stmt = $conn->prepare("INSERT INTO rating (user_id, album_id, rating_score) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $userId, $albumId, $rating);
            $stmt->execute();
        }
        exit;
    }

    if (isset($_GET['new_album_name'], $_GET['artist_id'], $_GET['genre'])) {
        $albumName = $conn->real_escape_string($_GET['new_album_name']);
        $artistId = intval($_GET['artist_id']);
        $genre = $conn->real_escape_string($_GET['genre']);

        $stmt = $conn->prepare("INSERT INTO album (album_name, artist_id, genre) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $albumName, $artistId, $genre);
        $stmt->execute();
        exit;
    }

    if (isset($_GET['genre']) || isset($_GET['artist'])) {
        $genre = $_GET['genre'] ?? '';
        $artist = intval($_GET['artist'] ?? 0);

        $conditions = [];
        if ($genre) $conditions[] = "genre = '$genre'";
        if ($artist > 0) $conditions[] = "artist_id = $artist";

        $sql = "SELECT album_id, album_name, genre FROM album" . (count($conditions) ? " WHERE " . implode(" AND ", $conditions) : "");
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<div class='table-container'><table>
                    <tr><th>Album Name</th><th>Genre</th><th>Your Rating</th><th>Avg. Rating</th></tr>";
            while ($row = $result->fetch_assoc()) {
                $albumId = $row['album_id'];

                $userRatingQuery = $conn->query("SELECT rating_score FROM rating WHERE user_id = $userId AND album_id = $albumId");
                $userRating = $userRatingQuery->num_rows > 0 ? $userRatingQuery->fetch_assoc()['rating_score'] : '';

                $avgRatingQuery = $conn->query("SELECT AVG(rating_score) AS avg_rating FROM rating WHERE album_id = $albumId");
                $avgRating = $avgRatingQuery->num_rows > 0 ? round($avgRatingQuery->fetch_assoc()['avg_rating'], 2) : 'No rating';

                echo "<tr>
                        <td>" . htmlspecialchars($row['album_name']) . "</td>
                        <td>" . htmlspecialchars($row['genre']) . "</td>
                        <td><input type='number' min='1' max='10' value='$userRating' onblur=\"submitRating($albumId, this.value)\"></td>
                        <td>" . ($avgRating === 'No rating' ? $avgRating : "$avgRating / 10") . "</td>
                      </tr>";
            }
            echo "</table></div>";
        } else {
            echo "<p>No albums found.</p>";
        }
        exit;
    }
}

$artistDropdown = '';
$artistResult = $conn->query("SELECT artist_id, artist_name FROM artist");
while ($row = $artistResult->fetch_assoc()) {
    $artistDropdown .= "<option value='{$row['artist_id']}'>" . htmlspecialchars($row['artist_name']) . "</option>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Album Display</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom, #FF5F6D, #FFC371);
            color: #333;
            padding: 20px;
            text-align: center;
        }
        h1 {
            color: #fff;
            font-size: 2.2rem;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .table-container {
            max-width: 750px;
            margin: 20px auto;
            background: #fff;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 1rem;
            border-radius: 10px;
            overflow: hidden;
        }
        th {
            background: #FF6F91;
            color: #fff;
            padding: 12px;
            text-align: center;
            font-weight: bold;
            border: 2px solid #FF6F91;
        }
        td {
            padding: 12px;
            text-align: center;
            border: 1px solid #FF6F91;
        }
        tr:nth-child(even) {
            background: #FFE3E8;
        }
        tr:nth-child(odd) {
            background: #FFF;
        }
        tr:hover {
            background: #FFD1DC;
        }
        select, button {
            padding: 10px;
            margin: 5px;
            border-radius: 20px;
            border: 1px solid #FF6F91;
            background: #fff;
            color: #333;
            font-size: 1rem;
            cursor: pointer;
        }
        button:hover {
            background: #FF6F91;
            color: #fff;
        }
    </style>
    <script>
        function fetchAlbums() {
            const genre = document.getElementById("genreFilter").value;
            const artist = document.getElementById("artistFilter").value;

            fetch(`?genre=${genre}&artist=${artist}`)
                .then(response => response.text())
                .then(data => document.getElementById("albumDisplay").innerHTML = data);
        }

        function submitRating(albumId, rating) {
            fetch(`?album_id=${albumId}&rating=${rating}`).then(() => fetchAlbums());
        }

        function addAlbum() {
            const albumName = document.getElementById("newAlbumName").value;
            const artistId = document.getElementById("artistDropdown").value;
            const genre = document.getElementById("genreDropdown").value;

            fetch(`?new_album_name=${albumName}&artist_id=${artistId}&genre=${genre}`)
                .then(() => { alert("Album added successfully!"); fetchAlbums(); });
        }

        window.onload = fetchAlbums;
    </script>
</head>
<body>
    <?php include('bandNavbar.html'); ?>
    <br>
    <br>
    <br>
    <h1>Album Display</h1>

    <label>Genre:</label>
    <select id="genreFilter" onchange="fetchAlbums()">
        <option value="">All</option>
        <option value="Rock">Rock</option>
        <option value="Pop">Pop</option>
        <option value="Rap">Rap</option>
    </select>

    <label>Artist:</label>
    <select id="artistFilter" onchange="fetchAlbums()">
        <option value="">All</option>
        <?php echo $artistDropdown; ?>
    </select>

    <div id="albumDisplay"></div>

    <h2 style="color: white;">Add Album</h2>
    <input type="text" id="newAlbumName" placeholder="Album Name">
    <select id="artistDropdown"><?php echo $artistDropdown; ?></select>
    <select id="genreDropdown">
        <option value="Rock">Rock</option>
        <option value="Pop">Pop</option>
        <option value="Rap">Rap</option>
    </select>
    <button onclick="addAlbum()">Add</button>
</body>
</html>
