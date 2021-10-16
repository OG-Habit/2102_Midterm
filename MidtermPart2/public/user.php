<?php
    session_start();
    if(!isset($_SESSION["email"])) {
        header("Location: login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="../src/css/style.css">
</head>
<body>
    <h1><?php echo $_SESSION["name"] ?></h1>

    <button id="logoutBtn" type="button">Logout</button>

    <hr>
    <br><br><br><br>
    <hr>

    <div>
        <button id="displayBtnUser">Display Events</button>
        <button id="showBookedEventsBtn">Booked Events</button>
    </div>

    <section id="displayEventsSec" class=""> 

        <h1 id="event"></h1>

    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="../src/js/script.js"></script>
</body>
</html>