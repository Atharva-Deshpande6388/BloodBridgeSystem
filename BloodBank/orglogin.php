<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("connect.php");

    $org_name = $_POST["org_name"];
    $org_email = $_POST["org_email"];
    $passwd = $_POST["passwd"];

    // Fetch only the hashed password from the database
    $sql = "SELECT org_id, passwd FROM orgs WHERE org_name = ? AND org_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $org_name, $org_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_org_id, $db_hashed_passwd);
        $stmt->fetch();

        // Verify the entered password against the hashed password
        if (password_verify($passwd, $db_hashed_passwd)) {
            $_SESSION["org_id"] = $db_org_id;
            header("Location: organization/orgpage.php");
            exit();
        } else {
            echo "<script>alert('INVALID DETAILS');window.location.href='orglogin.php';</script>";
        }
    } else {
        echo "<script>alert('INVALID DETAILS');window.location.href='orglogin.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>

    <style>
        /* Centering the page */
body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: linear-gradient(135deg, #130f40, #5352ed);
    margin: 0;
    font-family: 'Poppins', sans-serif;
}

/* Login form container */
form {
    background: white;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.3);
    width: 350px;
    text-align: center;
    transition: all 0.3s ease-in-out;
}

/* Hover effect on form */
form:hover {
    transform: translateY(-5px);
    box-shadow: 0px 15px 25px rgba(0, 0, 0, 0.4);
}

/* Heading styles */
h2 {
    color: #333;
    margin-bottom: 15px;
    font-size: 24px;
}

/* Input fields */
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease-in-out;
}

/* Input focus effect */
input:focus {
    border-color: #5352ed;
    box-shadow: 0px 0px 10px rgba(83, 82, 237, 0.5);
    outline: none;
}

/* Submit button styling */
input[type="submit"] {
    background: #28a745;
    color: white;
    border: none;
    cursor: pointer;
    padding: 12px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
}

/* Submit button hover effect */
input[type="submit"]:hover {
    background: #218838;
    transform: scale(1.05);
}

/* Link styling */
a {
    text-decoration: none;
    color: #5352ed;
    font-weight: bold;
    transition: color 0.3s ease-in-out;
}

a:hover {
    text-decoration: underline;
    color: #3036b2;
}

/* Mobile responsiveness */
@media screen and (max-width: 400px) {
    form {
        width: 90%;
        padding: 25px;
    }

    h2 {
        font-size: 22px;
    }

    input {
        font-size: 14px;
    }
}

    </style>
</head>
<body>
    <form method="POST" action="orglogin.php">
    <div>
        <h2>Organization Login</h2>
        Organization Name: <input type="text" name="org_name" required><br>
        Email: <input type="text" name="org_email" required><br>
        Password: <input type="password" name="passwd" required><br>
        Not Registered? <a href="org_reg.php">register</a>
        <input class="button" type="submit" value="Sign In">
    </div>
</form>
</body>
</html>