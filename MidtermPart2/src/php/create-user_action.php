<?php

    include_once("db_con.php");

    $isValid;
    $message = "";
    $obj = [];

    $username = trim($_REQUEST["username"]);
    $password = trim($_REQUEST["password"]);
    $email = trim($_REQUEST["email"]);
    $name = trim($_REQUEST["name"]);    

    $isValid = filter_var($email, FILTER_VALIDATE_EMAIL) ? 1 : 0;

    if($isValid) {
        $query = "SELECT email FROM users_detail WHERE email = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0) {
            $message = "Email already used!";
            $isValid = 0;
        }
        $stmt->close();
    }

    if($isValid) {
        $query = "INSERT INTO users_detail(username, password, name, email, user_type) VALUES(?, ?, ?, ?, 'User')";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssss", $username, $password, $name, $email);
        $stmt->execute();
        if(!$stmt->affected_rows) {
            $message = "Account was not inserted!";
            $isValid = 0;
        }
        $message = "Account successfully created!";
        $stmt->close();
    }

    $obj = [
        "message" => $message,
        "status" => $isValid
    ];
    $obj = json_encode($obj, JSON_FORCE_OBJECT);
    echo $obj;