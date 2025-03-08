<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Band Login</title>
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
        }

        input[type="text"],
        input[type="password"] {
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
        input[type="password"]:focus {
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
    </style>
</head>
<body>
    <h1>Band Login</h1>
    <form action="bandLoginSubmit.php" method="GET">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>
        <label for="pass">Password</label>
        <input type="password" name="pass" id="pass" required>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
