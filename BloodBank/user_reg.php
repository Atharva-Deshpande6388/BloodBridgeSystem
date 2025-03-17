<?php
session_start();
include "connect.php";

function calculateAge($dob) {
    $dobDate = new DateTime($dob);
    $today = new DateTime();
    $age = $dobDate->diff($today)->y;
    return $age;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {



    $fname = $_POST['fname'];
    $uname = $_POST['uname'];
    $passwd = $_POST['passwd'];
    $hashed_password = password_hash($passwd, PASSWORD_BCRYPT);
    $dob = $_POST['dob'];
    $age = calculateAge($dob);


    $check_sql = "SELECT uname FROM users WHERE uname = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $uname);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "<script>alert('USERNAME ALREADY EXISTS'); window.location.href='/bloodbank/user_reg.php';</script>";
    }
    else{

    $sql = "INSERT INTO users (fname, uname, passwd, dob, age, time) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $fname, $uname, $hashed_password, $dob, $age);

    
    if ($stmt->execute()) {
        echo "<script>alert('User successfully registered.'); window.location.href = '/bloodbank/userlogin.php';</script>";

    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    }


    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Form</title>
    <style>
<style>
body {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #ff416c, #ff4b2b);
    font-family: 'Poppins', sans-serif;
    padding: 20px;
}

form {
    background: white;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 400px;
    text-align: center;
    transition: all 0.3s ease-in-out;
}

form:hover {
    transform: translateY(-5px);
    box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.3);
}

h3 {
    margin-bottom: 20px;
    color: #333;
    font-size: 24px;
}

input, select {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 2px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease-in-out;
}

input:focus {
    border-color: #ff416c;
    box-shadow: 0px 0px 10px rgba(255, 65, 108, 0.5);
    outline: none;
}

input[type="submit"] {
    background: #ff416c;
    color: white;
    border: none;
    cursor: pointer;
    padding: 14px;
    font-size: 16px;
    font-weight: bold;
    width: 100%;
    border-radius: 6px;
    transition: all 0.3s ease-in-out;
}

input[type="submit"]:hover {
    background: #ff1b57;
    transform: scale(1.05);
}

@media screen and (max-width: 450px) {
    form {
        padding: 25px;
        width: 90%;
    }

    h3 {
        font-size: 20px;
    }

    input, select {
        font-size: 14px;
    }
}
.center-form{
    /* background-color: pink; */
    margin-left:500px;
    margin-right:450px;
    margin-top:90px;
}

</style>

</head>
<body>
<div class="center-form">
<form action="user_reg.php" method="POST">

<h3 style="text-align:center">User Registration Form</h3>

    <div>
        Full Name: <input type="text" name="fname" required>
        username: <input type="text" name="uname" required>
        Password: <input type="password" name="passwd" minlength="4" maxlength="8" required>
        Date of Birth: <input type="date" name="dob" required>
        Already registered? <a href="userlogin.php">Login</a>

        <input type="submit" name="Submit" value="Submit Form">
        
    </div>
    </div>
</form>
</body>
</html>