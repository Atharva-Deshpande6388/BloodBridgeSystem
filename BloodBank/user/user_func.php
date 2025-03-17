<?php
session_start();
include("../connect.php"); // Include the database connection

function user_details($user_id) {
    global $conn;
    $sql = "SELECT profile_image, fname, email, btype, contact, city, state, eligibility, reason, eligible_after FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($img, $name, $email, $btype, $contact, $city, $state, $eligibility, $reason, $eligible_after);

    if ($stmt->fetch()) {
        // Display user information
        echo "<div class='txt-container'>";
        if (!empty($img)) {
            echo '<img src="' . htmlspecialchars($img) . '" alt="Profile Picture" width="150"><br>';
        } else {
            echo '<img src="/default-profile.png" alt="Default Profile Picture" width="150"><br>';
        }
        echo "Name:         " . htmlspecialchars($name) . "<br>";
        echo "Email:        " . htmlspecialchars($email) . "<br>";
        echo "Blood Type:   " . htmlspecialchars($btype) . "<br>";
        echo "Contact Info: ". htmlspecialchars($contact) . "<br>";
        echo "City:         ". htmlspecialchars($city) . "<br>";
        echo "State:        ". htmlspecialchars($state) . "<br>";
        echo "Eligibility:  ". htmlspecialchars($eligibility) . "<br>";
        if ($eligibility === "Not Eligible")
        {
            if ($eligible_after == null)
            {
                echo "You can donate blood after: ". htmlspecialchars($eligible_after) . "<br>";
            }
            echo "Reason: ". htmlspecialchars($reason) . "<br>";
        }
    } else {
        return "No user found.";
    }
    echo "</div>";
    $stmt->close();
}

function find_organization($city, $state) {
    global $conn;
    $sql = "SELECT org_name, org_addr, org_phone FROM orgs WHERE org_city = ? AND org_state = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $city, $state);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($name, $address, $contact);

    echo "<div class='results-container'>";
    if ($stmt->num_rows > 0) {
        while ($stmt->fetch()) {
            echo "<div class='card'>";
            echo "<h2>" . htmlspecialchars($name) . "</h2>";
            echo "<p><strong>Contact:</strong> " . htmlspecialchars($contact) . "</p>";
            echo "<p><strong>Address:</strong> " . htmlspecialchars($address) . "</p>";
            echo "</div>";
            }
        } 
        else 
        {
            echo "<p>No organizations found in this location.</p>";
        }
        echo "</div>";
}


// function list_all_organizations() {
//     global $conn;
//     $sql = "SELECT profile_image, org_name, org_addr, org_phone FROM orgs";
//     $result = $conn->query($sql);

//     echo "<div class='results-container'>";
//     if ($result->num_rows > 0) {
//         while ($row = $result->fetch_assoc()) {
//             echo "<div class='card'>";
//             echo "<h2>" . htmlspecialchars($row['org_name']) . "</h2>";
//             echo "<p><strong>Contact:</strong> " . htmlspecialchars($row['org_phone']) . "</p>";
//             echo "<p><strong>Address:</strong> " . htmlspecialchars($row['org_addr']) . "</p>";
//             echo "</div>";
//         }
//     } else {
//         echo "<p>No organizations available.</p>";
//     }
//     echo "</div>";
// }

function list_all_organizations() {
    global $conn;
    $sql = "SELECT profile_image, org_name, org_addr, org_phone FROM orgs";
    $result = $conn->query($sql);

    echo "<div class='results-container'>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='card'>";
            if (!empty($row['profile_image'])) {
                echo "<img src='" . htmlspecialchars($row['profile_image']) . "' alt='Organization Image' class='org-image'>";
            }
            echo "<h2>" . htmlspecialchars($row['org_name']) . "</h2>";
            echo "<p><strong>Contact:</strong> " . htmlspecialchars($row['org_phone']) . "</p>";
            echo "<p><strong>Address:</strong> " . htmlspecialchars($row['org_addr']) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No organizations available.</p>";
    }
    echo "</div>";
}


if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    // echo display_user($user_id);
} else {
    $user_id = "You have already been logged out.";
}

// $conn->close(); // Close the connection at the end
?>