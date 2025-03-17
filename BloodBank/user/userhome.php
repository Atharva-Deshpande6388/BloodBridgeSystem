<?php
session_start();
include("../connect.php"); // Include the database connection

function display_user($user_id) {
    global $conn;
    $sql = "SELECT fname, email, btype FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($name, $email, $btype);

    if ($stmt->fetch()) {
        // Display user information
        echo "  Name: " . htmlspecialchars($name) . "<br>";
                // Email: " . htmlspecialchars($email) . "<br>
                // Blood Type: " . htmlspecialchars($btype) . "<br>";
    } else {
        return "No user found.";
    }
    $stmt->close();
}

if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    // echo display_user($user_id);
} else {
    $user_id = "You have already been logged out.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url("bgpix.jpg");
            background-size: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .header {
            width: 100%;
            background:rgba(255, 255, 255, 0);
            color: White;
            padding: 20px 0;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .container {
            font-family: "Times New Roman", Times, serif;
            background: white;
            opacity: 90%;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 3px 3px 15px rgba(0, 0, 0, 0.29);
            text-align: center;
            margin-top: 20px;
        }
        h2 {
            color: #333;
            font-weight: bold;
        }
        a {
            font-family: "Times New Roman", Times, serif;
            display: inline-block;
            text-decoration: none;
            background:rgba(255, 119, 0, 0.78);
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            margin: 10px;
            transition: 0.3s;
            font-weight: bold;
        }
        a:hover {
            background:rgba(47, 0, 155, 0.69);
        }
        .h1{
            font-size: 80px;
        }
        .top-image {
            right: 80px;
            width: 400px;
        }
    </style>

    <script>
        window.onload = function() {
            if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                window.location.reload();
            }
        };
    </script>

</head>
<body>

<!-- Image at the top -->
<img src="cc.png" alt="top Image" class="middle-image top-image">

    <div class="header"><h1>Lets Donate Your Blood<h1></div>
    <div class="container">
        <h2>Welcome To Find Blood Donation Organization <?php echo display_user($user_id); ?></h2>
        <a href="user_profile.php">Profile</a>
        <a href="form.php">Form</a>
        <a href="../logout.php">Logout</a>
    </div>
</body>
</html>
