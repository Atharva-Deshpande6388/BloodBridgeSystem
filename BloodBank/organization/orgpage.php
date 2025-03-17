<?php
session_start();
include_once "../connect.php";
if(!isset($_SESSION['org_id']))
{
    header('Location: orglogin.php');
    exit();
}
else
{
$org_id = $_SESSION['org_id'];
}

include("../connect.php"); // Include the database connection

function display_user($org_id) {
    global $conn;
    $sql = "SELECT org_name FROM orgs WHERE org_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $org_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($name);

    if ($stmt->fetch()) {
        // Display user information
        echo "  user, " . htmlspecialchars($name) . "<br>";
    } else {
        return "No user found.";
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Page</title>
</head>
<body>
    <h1>Organization Page</h1>
    <h2>Welcome <?php echo display_user($org_id); ?></h2>
    <a href="org_profile.php">Profile</a><br>
    <a href="../logout.php">Logout</a>
</body>
</html>