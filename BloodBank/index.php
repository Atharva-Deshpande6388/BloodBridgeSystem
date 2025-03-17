<?php
// require "register.php";

// require "login.php";


session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url("Bloom.png");
            background-size: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }
        .container {
            margin-bottom: 150px;
            background: white;
            padding: 15px;
            background-color: rgba(255, 255, 255, 0.22);
            box-shadow: 0px 4px 6px rgba(253, 246, 246, 0.3);
            border-radius: 10px;
            text-align: center;
            width: 300px;
        }
        .logo {
            width: 300px;
            max-width: 700px;
            margin-bottom: 10px;
            clip-path: circle(50%);
        }
        .side-image {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 150px;
        }
        .left-image {
            left: 80px;
            width: 500px;
        }
        .right-image {
            right: 80px;
            width: 400px;
        }
        a {
            display: block;
            text-decoration: none;
            color: white;
            background: rgb(132, 0, 156);
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            transition: 0.3s;
        }
        a:hover {
            background: rgb(251, 24, 24);
        }
    </style>
</head>
<body>

<!-- Image at the top -->
<img src="icon.png" alt="Logo" class="logo" >

<!-- Side Images -->
<img src=".png" alt="Left Image" class="side-image left-image">
<img src=".png" alt="Right Image" class="side-image right-image">

<div class="container">
    <a href="orglogin.php">Organization Login</a>
    <a href="org_reg.php">New Organization Register</a>
    <a href="user_reg.php">New User Register</a>
    <a href="userlogin.php">User Login</a>
</div>

</body>
</html>
