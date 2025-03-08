<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #000;
            color: #fff;
            line-height: 1.6;
        }

        header {
            text-align: center;
            padding: 50px 20px;
            background-color: #000;
        }

        header h1 {
            font-size: 3rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff;
        }

        header p {
            font-size: 1.2rem;
            font-weight: 300;
            margin-bottom: 20px;
            color: #ccc;
        }

        nav {
            text-align: center;
            margin-top: 20px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: 500;
            margin: 0 15px;
            padding: 10px 20px;
            border: 2px solid #fff;
            border-radius: 30px;
            transition: all 0.3s ease;
        }

        nav a:hover {
            background-color: #fff;
            color: #000;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <h1>Welcome to BAND PROJECT</h1>
        <p>This is Daniella Litman's band project.</p>
        <nav>
            <a href="bandLogin.php">Login</a>
            <a href="bandSignup.php">Sign Up</a>
        </nav>
    </header>
</body>
</html>
