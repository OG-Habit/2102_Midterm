<?php
    include_once "db_con.php";

    $message = "Event was not uploaded.";
    $isValid = 0;
    $obj = [];

    $allowedImgExt = ["png", "jpg", "jpeg", "tif", "tiff", "bmp", "gif", "eps", "webp"];

    $eventName = trim($_REQUEST["eventName"]);
    $eventImg = $_FILES["eventImg"];

    $imgName = $eventImg["name"];
    $imgNewName = "";
    $imgTmpName = $eventImg["tmp_name"];
    $imgType = $eventImg["type"];

    $imgNameAndExt = explode(".", $imgName);
    $imgExt = strtolower(end($imgNameAndExt));

    if(in_array($imgExt, $allowedImgExt) and $eventImg["error"] == 0) {
        $imgNewName = "../src/img/".$eventName.".".$imgExt;
        $imgDest = "../img/".$eventName.".".$imgExt;
        move_uploaded_file($imgTmpName, $imgDest);
        $isValid = 1;
    } else {
        $message = "Invalid file format!";
    }

    if($isValid) {
        $query = "SELECT event_name FROM events WHERE event_name = '$eventName'";
        $stmt = $con->query($query);
        if($stmt->num_rows > 0) {
            $isValid = 0;
            $message = "Duplicate event names!";
        }
        $stmt->close();
    }

    if($isValid) {
        $query = "INSERT INTO events(event_name, event_image) VALUES(?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ss", $eventName, $imgNewName);
        $stmt->execute();
        if(!$stmt->affected_rows) {
            $isValid = 0;
            $message = "Event image was not inserted into db!";
        }
        $message = "Event successfully uploaded.";
        $stmt->close();
    }

    $obj = [
        "message" => $message,
        "status" => $isValid
    ];
    $obj = json_encode($obj, JSON_FORCE_OBJECT);
    echo $obj;