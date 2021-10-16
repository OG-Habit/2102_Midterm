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
    <title>Admin Page</title>
    <link rel="stylesheet" href="../src/css/style.css">
</head>
<body class="admin">
    <h1><?php echo $_SESSION["name"] ?></h1>

    <button id="logoutBtn" type="button">Logout</button>

    <hr>

    <div class="btn-cont">
        <button id="createEventBtn">Create Event</button>
        <button id="createUserBtn">Create User</button>
    </div>

    <section id="createEventSec" class="hide">
        <h2>Create Event</h2>
        <form enctype="multipart/form-data" method="POST" id="eventForm" class="event-form">
            <div class="event-form__input-container">
                <label for="eventFormName">Event Name</label>
                <input type="text" id="eventFormName" name="eventName" required>
        
                <label for="eventFormImg">Event Image</label>
                <input type="file" id="eventFormImg" name="eventImg" required value="Choose File">
            </div>

            <hr class="event-form__hr">

            <input class="event-form__submit" type="submit">
        </form>
    </section>

    <section id="createUserSec" class="hide">
        <h2>Create User</h2>
        <form method="POST" id="userForm" class="user-form">
            <div class="user-form__input-container">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>

                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            
                <label for="name">Name</label>
                <input type="text" name="name" id="name" required>
            
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>

            <hr  class="user-form__hr">

            <input  class="user-form__submit" type="submit" value="Register">
        </form>
    </section>
    
    <hr>

    <button id="displayBtn" type="button">Display Events</button>

    <section id="displayEventsSec" class="hide"> 
    </section>
        
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="../src/js/script.js"></script>
</body>
</html>