<?php
    // include database connection script
    include("database.php");

    header("Content-Type: application/json");

    $json_str = file_get_contents('php://input');
    $json_obj = json_decode($json_str, true);

    $username = $json_obj["username"];
    $password = $json_obj["password"];

    // hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // check if username already exists
    $stmt = $mysqli->prepare("SELECT * FROM user_information WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(array(
            "success" => false,
            "message" => "Username already exists"
        ));
    } else {
        // insert the user into the database
        $stmt2 = $mysqli->prepare("INSERT INTO user_information (username, hashed_password) VALUES (?, ?)");
        $stmt2->bind_param("ss", $username, $hashed_password);

        if ($stmt2->execute()) {
            echo json_encode(array(
                "success" => true,
                "message" => "Registration successful"
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => "Error: Unable to register user"
            ));
        }
        $stmt2->close();
    }
    $stmt->close();
?>
