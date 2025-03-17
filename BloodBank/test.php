<!-- <?php
$result = $conn->query("SELECT * FROM users");
if ($result) {
    echo "Users table found!";
} else {
    echo "Error: " . $conn->error;
}
?> -->
<!-- <?php
session_start();
var_dump($_SESSION); // Add this line for debugging
include "../connect.php";

// ... (Your existing code) ...
// session_start();
// var_dump($_SESSION);
// ?> -->
// <?php
// if ($check_stmt->num_rows > 0) {
//     echo "User found. Proceeding with update...<br>";
// }?>
<?php
echo "<img src='" . htmlspecialchars($profile_image) . "' alt='Profile Image' class='profile-img'>";
?>