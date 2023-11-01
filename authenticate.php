<?php
session_start();

$DATABASE_HOST = 'localhost:3307';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Check if the "Remember Me" option is selected
$remember = isset($_POST['remember']) ? 1 : 0;

if (!isset($_POST['username'], $_POST['password'])) {
    exit('Please fill both the username and password fields!');
}

if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();

        if (password_verify($_POST['password'], $password)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;

            if ($remember) {
                // Generate a random token and store it in the database
                $token = bin2hex(random_bytes(16));
                $user_id = $id; // Use the user's ID

                // Store the token in the database
                $query = "INSERT INTO remember_tokens (user_id, token) VALUES (?, ?)";
                if ($stmt = $con->prepare($query)) {
                    $stmt->bind_param('is', $user_id, $token);
                    $stmt->execute();
                    $stmt->close();
                }

                // Set a cookie with the token
                $cookie_name = 'remember_token';
                $cookie_value = $token;
                $cookie_expiration = time() + 30 * 24 * 60 * 60; // 30 days
                setcookie($cookie_name, $cookie_value, $cookie_expiration, "/");
            } else {
                // Remove the remember_token cookie if it exists
                if (isset($_COOKIE['remember_token'])) {
                    setcookie('remember_token', '', time() - 3600, "/");
                }
            }

            header('Location: home.php');
        } else {
            echo 'Incorrect username and/or password!';
        }
    } else {
        echo 'Incorrect username and/or password!';
    }

    $stmt->close();
}
?>
