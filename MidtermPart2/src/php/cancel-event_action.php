<?php

    include_once "db_con.php";

    $message = "Event was not cancelled.";
    $isValid = 0;
    $obj = [];

    $bookingID = trim($_REQUEST["bookingID"]);

    $sql = "DELETE FROM bookings WHERE booking_id = '$bookingID'";

    if($con->query($sql)) {
        $message = "Event cancelled.";
        $isValid = 1;
    }

    $obj = [
        "message" => $message,
        "valid" => $isValid
    ];

    $obj = json_encode($obj, JSON_FORCE_OBJECT);
    echo $obj;