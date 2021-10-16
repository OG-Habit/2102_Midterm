<?php
    include_once("db_con.php");

    $data = [];
    $message = "Error! Account not found!";
    $userType = "Null";
    $isValid = 1;
    $status = 0;

    $username = trim($_REQUEST["username"]);
    $password = trim($_REQUEST["password"]);
    $query = "SELECT name, email, user_id, user_type FROM users_detail WHERE username = ? AND password = ?";

    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result == false) {
        $message = "Query failed!";
        $isValid = 0;
    }

    if($isValid == 1 and $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $userType = $data["user_type"] == "Admin" ? "Admin" : "User";
        $message = $userType == "Admin" ? "Welcome Admin" : "Welcome User";
        $status = 1;
        $_SESSION["name"] = $data["name"];
        $_SESSION["email"] = $data["email"];
        $_SESSION["userType"] = $data["user_type"];
        $_SESSION["user_id"] = $data["user_id"];
    }

    $stmt->close();
    $obj = array(
        "message" => $message,
        "data" => $data,
        "userType" => $userType,
        "status" => $status
    );
    $obj = json_encode($obj, JSON_FORCE_OBJECT);
    echo $obj;