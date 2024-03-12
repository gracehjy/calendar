<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <h2>Sign Up Below:</h2>
    <form action="signup.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <br>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <br>
        <input type="submit" value="Sign Up">
    </form>
    <br>
    <p>Already have an account? Log in <a href="login.php">here</a>!

    <?php
        session_start();

        // include database connection script
        include ("database.php");

        // update database for users signing up
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $username = $_POST["username"];
            $password = $_POST["password"];

            # hash password!
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); 

            # check to see if user already exists
            $stmt = $mysqli->prepare("SELECT * FROM user_information WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                echo "Username already exists. Please <a href='signup.php'>try again</a> or <a href='login.php'> login here</a>.";
            }
            else{ 
                // insert the user into the database
                $stmt2 = $mysqli->prepare("INSERT INTO user_information (username, hashed_password) VALUES (?, ?)");
                $stmt2->bind_param("ss", $username, $hashed_password);

                if($stmt2->execute()){
                    echo "Registration successful! Click here to <a href='login.php'>login</a>.";
                }
                else{
                    echo $mysqli->error;
                }
                $stmt2->close();
            }
            $stmt->close();
        }
    ?>
</body>
</html>