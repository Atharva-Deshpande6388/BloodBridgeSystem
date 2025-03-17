
<?php
session_start();
include("../connect.php"); // Include the database connection

function org_details($org_id) 
{
    global $conn;
    if (isset($_SESSION['org_id'])) {
        $org_id = $_SESSION['org_id'];
    } else {
        return "Organization ID not found in session.";
    }
    $sql = "SELECT profile_image, org_name, org_email, org_phone, org_addr, org_city, org_state FROM orgs WHERE org_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $org_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($img, $name, $email, $contact, $address, $city, $state);

    echo "<div class='txt-container'>";
    if ($stmt->fetch()) {
        echo "<div class='card'>";
        if (!empty($img)) {
            echo '<img src="/default-profile.png" alt="Default Profile Picture" width="150"><br>';
        } 
        else {
            echo '<img src="' . htmlspecialchars($img) . '" alt="Profile Picture" width="150"><br>';
        }
        echo    "<p> Name                   : </strong>" . htmlspecialchars($name) . "</p>";
        echo    "<p><strong>Email           : </strong>" . htmlspecialchars($email) . "</p>";
        echo    "<p><strong>Contact Info    : </strong>" . htmlspecialchars($contact) . "</p>";
        echo    "<p><strong>Address         : </strong>" . htmlspecialchars($address) . "</p>";
        echo    "<p><strong>City            : </strong>" . htmlspecialchars($city) . "</p>";
        echo    "<p><strong>State           : </strong>" . htmlspecialchars($state) . "</p>";
        echo    "</div>";
    
    } else {
        echo "<p>No organization found.</p>";
    }
    echo "</div>";
    $stmt->close(); 
}

function donors($btype, $city, $state){
    global $conn;
    $sql = "SELECT fname, email, contact, btype, city, eligibility, state FROM users WHERE btype = ? AND city = ? AND state = ?";
    $stmt = $conn->prepare($sql);
    if($stmt === false){
        die("Error in Preparing statement " . $conn->error);
    }
    $stmt -> bind_param("sss", $btype, $city, $state);
    $stmt-> execute();
    $stmt -> store_result();
    
        // Debug: Print the number of rows found
        $num_rows = $stmt->num_rows;
    
        if ($num_rows > 0) {
            $stmt->bind_result($name, $email, $contact, $btype, $city, $eligibility, $state);
            $found = false;
    
            echo "<div class='results-container'>";
            while ($stmt->fetch()) {
                if ($eligibility === "Eligible") {
                    echo "<div class='card'>";
                    echo "<h2>Name: " . htmlspecialchars($name) . "</h2>";
                    echo "<p><strong>Email: </strong>" . htmlspecialchars($email) . "</p>";
                    echo "<p><strong>Conact: </strong>" . htmlspecialchars($contact) . "</p>";
                    echo "<p><strong>Blod Type: </strong>" . htmlspecialchars($btype) . "</p>";
                    echo "<p><strong>Cit: </strong>" . htmlspecialchars($city) . "</p>";
                    echo "<p><strong>Stae: </strong>" . htmlspecialchars($state) . "</p>";
                    echo "</div>";
                    $found = true;
                }
            }
            if (!$found) {
                echo "<p>No eligible donors found.</p>"; //More descriptive message.
            }
        } else {
            echo "<p>No donors found with the specified criteria.</p>"; //More descriptive message.
        }
        echo "</div>";
        $stmt->close();
    }



    function allDonors() {
        global $conn;
        $sql = "SELECT fname, email, contact, btype, city, state FROM users WHERE eligibility = 'Eligible'";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error in preparing statement: " . $conn->error);
        }
        $stmt->execute();
        $stmt->store_result();
    
        // Debug: Print the number of rows found
        $num_rows = $stmt->num_rows;
    
        if ($num_rows > 0) {
            $stmt->bind_result($name, $email, $contact, $btype, $city, $state);
            
            echo "<div class='results-container'>";
            while ($stmt->fetch()) {
                echo "<div class='card'>";
                echo "<h2>Name: " . htmlspecialchars($name) . "</h2>";
                echo "<p><strong>Email: </strong>" . htmlspecialchars($email) . "</p>";
                echo "<p><strong>Contact: </strong>" . htmlspecialchars($contact) . "</p>";
                echo "<p><strong>Blood Type: </strong>" . htmlspecialchars($btype) . "</p>";
                echo "<p><strong>City: </strong>" . htmlspecialchars($city) . "</p>";
                echo "<p><strong>State: </strong>" . htmlspecialchars($state) . "</p>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>No eligible donors found.</p>";
        }
    
        $stmt->close();
    }

?>