<?php

    include_once "db_con.php";

    $isValid = 0;

    $bookingID = trim($_REQUEST["bookingID"]);
    $obj = [];

    $sql = <<<EOT
        SELECT events.event_id, event_name, event_image 
        FROM events 
        INNER JOIN bookings
        ON events.event_id = bookings.event_id
        WHERE booking_id = '$bookingID'
    EOT;

    $result = $con->query($sql);

    if($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $isValid = 1;
    }

    $obj = [
        "data" => $data,
        "valid" => $isValid
    ];
    $obj = json_encode($obj, JSON_FORCE_OBJECT);
    echo $obj;