<?php 
session_start();
include "../connect.php";

function display_user($user_id) {
    global $conn;
    $sql = "SELECT fname FROM user2 WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($name);

    if ($stmt->fetch()) 
    {
        // Display user information
        echo "  Name: " . htmlspecialchars($name) . "<br>";
    } 
    else {
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
    <title>Document</title>
</head>
<body>
    <div>
    <h2>Welcome, <?php echo display_user($user_id); ?></h2>
    </div>
</body>
</html>