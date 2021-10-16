<?php
    session_start();
    
    $con = new mysqli("localhost", "root", "", "event_booking");

    if($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }