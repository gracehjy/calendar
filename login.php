<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php
        session_start();
        include ("database.php");

        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

        // if the logout tag was clicked, destroy the current session and return to the login page
        if (isset($_GET['logout'])) {
            session_unset();
            session_destroy();
            header("Location: login.php");
            exit();
        }

        // check database for the user
        if($_SERVER["REQUEST_METHOD"] == "POST"){

            // check to see if user  exists
            // use prepared queries to prevent SQL injection
            $username = $_POST["username"];
            $stmt = $mysqli->prepare("SELECT user_id, hashed_password FROM user_information WHERE username = ?");
            // bind the parameter
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows == 1){
                // bind results
                $stmt->bind_result($user_id, $hashed_password);
                $stmt->fetch();

                // verify password
                $password = $_POST["password"];
                if (password_verify($password, $hashed_password)){
                    $_SESSION["user_id"] = $user_id;
                    $_SESSION["username"] = $username;
                    $stmt->close();
                    header("Location: calendar.html");
                    exit();
                }
                else{
                    $stmt->close();
                    header("Location: unsuccessfulLogin.html");
                    exit();
                }
            }
            else{
                $stmt->close();
                header("Location: unsuccessfulLogin.html");
                exit();
            }
        }
    ?>
    <h2>Login Below:</h2>
    <form action="login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <br>
        <input type="submit" value="Login">
    </form>
    <br>
    <p>Don't have an account? Sign up <a href="signup.php">here</a>!
    <br>
    <p>Click <a href="landingpage.php">here</a> to return to the site.</p>
</body>
</html>