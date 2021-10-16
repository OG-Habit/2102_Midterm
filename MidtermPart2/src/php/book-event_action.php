<?php

    include_once "db_con.php";

    $message = "Event was not booked!";
    $isValid = 0;
    $obj = [];
    
    $eventID = trim($_REQUEST["eventID"]);
    $userID = $_SESSION["user_id"];

    $query = "INSERT INTO bookings(event_id, user_id) VALUES('$eventID', '$userID')";

    if($con->query($query)) {
        $message = "Event booked successfully.";
        $isValid = 1;
    }

    $obj = [
        "message" => $message,
        "valid" => $isValid
    ];
    $obj = json_encode($obj, JSON_FORCE_OBJECT);
    echo $obj;