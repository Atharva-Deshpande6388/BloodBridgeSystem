<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("connect.php");

    $uname = $_POST["uname"];
    $passwd = $_POST["passwd"];

    // Fetch only the hashed password from the database
    $sql = "SELECT user_id, uname, passwd FROM users WHERE uname = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_user_id, $db_name, $db_hashed_passwd);
        $stmt->fetch();

        // Verify the entered password against the hashed password
        if (password_verify($passwd, $db_hashed_passwd)) {
            $_SESSION["user_id"] = $db_user_id;
            header("Location: /bloodbank/user/userhome.php");
            exit();
        } else {
            echo "<script>alert('INVALID USERNAME or PASSWORD');window.location.href='/bloodbank/userlogin.php';</script>";
        }
    } else {
        echo "<script>alert('INVALID USERNAME or PASSWORD');window.location.href='/bloodbank/userlogin.php';</script>";
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
        /* Full-page styling */
body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: linear-gradient(to right, #6a11cb, #2575fc);
    margin: 0;
    font-family: 'Poppins', sans-serif;
    padding: 20px;
}

/* Form container */
form {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 350px;
    text-align: center;
    transition: transform 0.3s ease-in-out;
}

/* Form hover effect */
form:hover {
    transform: translateY(-5px);
}

/* Header */
h2 {
    margin-bottom: 20px;
    color: #333;
    font-size: 22px;
}

/* Input fields */
input {
    width: calc(100% - 20px);
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.3s ease-in-out;
}

/* Input focus effect */
input:focus {
    border-color: #6a11cb;
    box-shadow: 0px 0px 5px rgba(106, 17, 203, 0.5);
    outline: none;
}

/* Submit button */
button {
    background: #6a11cb;
    color: white;
    border: none;
    cursor: pointer;
    padding: 12px;
    font-size: 16px;
    font-weight: bold;
    width: 100%;
    border-radius: 6px;
    transition: all 0.3s ease-in-out;
}

/* Button hover */
button:hover {
    background: #4c0fae;
    transform: scale(1.05);
}

/* Links */
a {
    display: block;
    margin-top: 10px;
    text-decoration: none;
    color: #6a11cb;
    font-weight: bold;
    transition: color 0.3s ease-in-out;
}

a:hover {
    text-decoration: underline;
    color: #4c0fae;
}

/* Mobile responsiveness */
@media screen and (max-width: 450px) {
    form {
        padding: 25px;
        width: 90%;
    }

    h2 {
        font-size: 20px;
    }

    input {
        font-size: 14px;
    }
}

    </style>
</head>
<body>
    
    <form method="POST">
        <h2>User Login</h2>
        <input type="text" name="uname" required><br>
        <input type="password" name="passwd" required><br>
        Not registered? <a href="user_reg.php">Register</a><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>