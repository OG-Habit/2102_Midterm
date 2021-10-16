<?php
    session_start();
    if(isset($_SESSION["email"])) {
        $location = $_SESSION["userType"] == "User" ? "user.php" : "admin.php";
        header("Location: $location");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../src/css/style.css">
</head>
<body class="login">
    <section class="login-container">
        <h1 class="login__h1">Login Page</h1>
    
        <form method="GET" class="login-form" id="loginForm">
            <div>
                <label class="login-form__label" for="loginForm__username">Username</label>
                <input type="text" name="username" id="loginForm__username" required>
            </div>
            <div>
                <label class="login-form__label" for="loginForm__password">Password</label>
                <input type="password" name="password"  id="loginForm__password" required>
            </div>
    
            <input id="loginBtn" type="submit" >
        </form>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="../src/js/script.js"></script>    
</body>
</html>