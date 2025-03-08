<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Band Signup</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #000;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 30px;
            color: #fff;
        }

        form {
            width: 100%;
            max-width: 400px;
        }

        label {
            display: block;
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #ccc;
            text-align: left;
        }

        input[type="text"],
        input[type="password"],
        input[type="name"],
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #555;
            border-radius: 5px;
            background: #222;
            color: #fff;
            font-size: 1rem;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="name"]:focus,
        select:focus {
            border-color: #96c7e8;
            outline: none;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background: #fff;
            color: #000;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s ease, color 0.3s ease;
        }

        input[type="submit"]:hover {
            background: #96c7e8;
            color: #fff;
        }

        select {
            cursor: pointer;
        }
    </style>
    </head>
    <body>
        <h1>Band Signup</h1>
        <form action="bandSignupSubmit.php" method="GET">
            <section id="emailPass">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>

                <label for="pass">Password:</label>
                <input type="password" name="pass" id="pass" required>

                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
            </section>

            <label for="userCountry">Select your country:</label>
            <select name="userCountry" id="userCountry" required>
                <option value="United States of America">United States of America</option>
                <option value="Canada">Canada</option>
                <option value="Mexico">Mexico</option>
                <option value="Korea">Korea</option>
            </select>

            <label for="favArtist">Select your favorite artist:</label>
            <select name="favArtist" id="favArtist" required>
                <?php
                include('bandConnect.php');
                $sql = "SELECT artist_name, artist_id FROM artist";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . htmlspecialchars($row['artist_id']) . "'>" . htmlspecialchars($row['artist_name']) . "</option>";
                    }
                }
                mysqli_close($conn);
                ?>
            </select>

            <input type="submit" value="Submit">
        </form>
    </body>
</html>
