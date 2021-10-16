<?php

    include_once "db_con.php";

    $data = [];
    $userType = $_SESSION["userType"];
    $userID = $_SESSION["user_id"];
    $type = trim($_REQUEST["type"]);
    $nonbookArr = json_decode($_REQUEST["nonbookArr"]);
    $bookArr = json_decode($_REQUEST["bookArr"]);
    $adminArr = json_decode($_REQUEST["adminArr"]);

    array_push($data, $userType);

    if($userType == "User") {
        $queryNonBook = <<<EOT
            SELECT events.*
            FROM events 
            LEFT JOIN bookings
            ON events.event_id = bookings.event_id AND '$userID' = bookings.user_id
            WHERE bookings.event_id IS NULL;
        EOT;
        $queryBook = <<<EOT
            SELECT event_name, event_image, booking_id
            FROM events
            INNER JOIN bookings
            ON events.event_id = bookings.event_id 
            AND bookings.user_id = '$userID'
        EOT;
        $query = $type == "nonbook" ? $queryNonBook : $queryBook;
    } 

    if($userType == "Admin") {
        $query = "SELECT event_id, event_name, event_image FROM events";
    }

    $stmt = $con->query($query);

    if($stmt) {
        if($userType == "User") {
            if($type == "nonbook") {
                while($result = $stmt->fetch_assoc()) {
                    if(!in_array($result["event_id"], $nonbookArr))
                    array_push($data, $result);
                }
            } else {
                while($result = $stmt->fetch_assoc()) {
                    if(!in_array($result["booking_id"], $bookArr))
                    array_push($data, $result);
                }
            }
        } else {
            if($stmt) {
                while($result = $stmt->fetch_assoc()) {
                    if(!in_array($result["event_id"], $adminArr))
                    array_push($data, $result);
                }
            }
        }
    }
    
    $stmt->close();

    $data = json_encode($data);
    echo $data;