<?php

    include_once "db_con.php";

    $message = "Event name not updated!";
    $isValid = 1;
    $obj = [];

    $eventName = trim($_REQUEST["eventName"]);
    $eventID = trim($_REQUEST["eventID"]);

    $query = "UPDATE events SET event_name = '$eventName' WHERE event_id = '$eventID'";
    $sql = "SELECT * FROM events WHERE event_name = '$eventName'";

    $result = $con->query($sql);

    if($result->num_rows > 0) {
        $isValid = 0;
        $message = "New name has duplicates!";
    }

    if($isValid and $con->query($query)) {
        $message = "Event name updated!";
    }

    $obj = [
        "message" => $message,
        "valid" => $isValid
    ];

    $obj = json_encode($obj, JSON_FORCE_OBJECT);
    echo $obj;