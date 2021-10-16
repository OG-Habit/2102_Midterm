<?php

    include_once "db_con.php";

    $message = "Event was not deleted.";
    $isValid = 0;
    $obj = [];

    $eventID = trim($_REQUEST["eventID"]);

    $query = "DELETE FROM events WHERE event_id = '$eventID'";
    
    if($con->query($query)) {
        $query = "DELETE FROM bookings WHERE event_id = '$eventID'";
        if($con->query($query)) {
            $isValid = 1;
            $message = "Event deleted.";
        }
    }

    

    

    $obj = [
        "message" => $message,
        "status" => $isValid
    ];
    $obj = json_encode($obj, JSON_FORCE_OBJECT);
    echo $obj;