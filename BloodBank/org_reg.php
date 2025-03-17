
<?php
include_once "connect.php";
include "clean_data.php";

$allowed_types = ["jpg", "jpeg", "png", "gif"]; // Allowed image formats
$max_file_size = 2 * 1024 * 1024; // 2MB file size limit

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $target_dir = "organization/uploads/"; // Define the upload directory
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
    if ($check === false) {
        echo "<script>alert('File is not an image.'); window.location.href = '/bloodbank/org_reg.php';</script>";
        exit();
    }
    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "<script>alert('Only JPG, JPEG, PNG, and GIF files are allowed.'); window.location.href = '/bloodbank/org_reg.php';</script>";
        exit();
    }
    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
        $profile_image_path = $target_file;
    } else {
        echo "<script>alert('Error uploading file.'); window.location.href = '/bloodbank/org_reg.php';</script>";
        exit();
    }



    // Clean user input
    $org_name = clean_data($_POST["org_name"]);
    $org_email = clean_data($_POST["org_email"]);
    $passwd = $_POST["passwd"];
    $hashed_password = password_hash($passwd, PASSWORD_BCRYPT);
    $licence = $_POST["licence"];
    $org_phone = clean_data($_POST["org_phone"]);
    $org_addr = clean_data($_POST["org_addr"]);
    $org_city = clean_data($_POST["org_city"]);
    $org_state = clean_data($_POST["org_state"]);

    // try {
        $stmt = $conn->prepare("INSERT INTO orgs (org_name, org_email, passwd, licence, profile_image, org_phone, org_addr, org_city, org_state, time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssssisss", $org_name, $org_email, $hashed_password, $licence, $profile_image_path, $org_phone, $org_addr, $org_city, $org_state);

        if ($stmt->execute()) {
            echo "<script>alert('Organization successfully registered.'); window.location.href = '/bloodbank/orglogin.php';</script>";
            exit();
        }
        $stmt->close();
    // } 
    // catch (mysqli_sql_exception $e) {
    //     if ($e->getCode() == 1062) {
    //         echo "<script>alert('Organization already exists.'); window.location.href='/bloodbank/org_reg.php';</script>";
    //     } else {
    //         throw $e;
    //     }
    // }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centered Form</title>
    <style>
    /* Ensure full height for html and body */
    /* Ensure full height and center alignment */
html, body {
    height: 100%;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(to right, #6a11cb, #2575fc); /* Gradient background */
    font-family: 'Poppins', sans-serif;
}

/* Form container */
form {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
    width: 380px;
    text-align: center;
    transition: transform 0.3s ease-in-out;
    overflow-y: auto;
    max-height: 95vh;
}

/* Form hover effect */
form:hover {
    transform: translateY(-5px);
}

/* Headers */
h2, h4 {
    color: #333;
    margin-bottom: 15px;
}

/* Input and Select Styling */
input, select {
    width: calc(100% - 20px);
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    transition: border 0.3s, box-shadow 0.3s;
}

/* Input focus effect */
input:focus, select:focus {
    border-color: #6a11cb;
    box-shadow: 0px 0px 5px rgba(106, 17, 203, 0.5);
    outline: none;
}

/* File Input Styling */
input[type="file"] {
    border: none;
    background: #f8f8f8;
    padding: 10px;
    cursor: pointer;
}

/* Submit Button */
input[type="submit"] {
    background: #6a11cb;
    color: white;
    border: none;
    cursor: pointer;
    padding: 12px;
    font-size: 16px;
    font-weight: bold;
    width: 100%;
    border-radius: 6px;
    transition: background 0.3s ease-in-out;
}

/* Submit Button Hover */
input[type="submit"]:hover {
    background: #4a0dcb;
}

/* Links */
a {
    text-decoration: none;
    color: #6a11cb;
    font-weight: bold;
    transition: color 0.3s;
}

a:hover {
    text-decoration: underline;
    color: #4a0dcb;
}

/* Responsive Design */
@media (max-width: 420px) {
    form {
        width: 90%;
    }
}

</style>

</head>
<body>

    <form action="org_reg.php" method="POST" enctype="multipart/form-data">

        <h2>Organization registration</h2>
        <div>
        <div>
        <input type="file" name="profile_image" accept="image/*" required>
        </div>

        <div>
            Name of Organization: <input type="text" name="org_name" required>
        </div>
        
        <div>
            Organization Email: <input type="email" name="org_email" required>
        </div>
        
        <div>
            Password: <input type="password" name="passwd" required>
        </div>
        
        <div>
            Licence no.: <input type="text" name="licence" required>
        </div>

        <div>
            Organization Contact: <input type="number" name="org_phone" maxlength="10" required>
        </div>
        
        <h4>Organization Location:</h4>
        <div>
            Address: <input type="text" name="org_addr" required>
        </div>
        
        <div>
            City: <input type="text" name="org_city" required>
        </div>

        <div>
        <label for="org_state">State:</label>
        <select id="org_states" name="org_state" required>
            <option value="" selected disabled>--select--</option>
            <option value="Andhra Pradesh">Andhra Pradesh</option>
            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
            <option value="Assam">Assam</option>
            <option value="Bihar">Bihar</option>
            <option value="Chhattisgarh">Chhattisgarh</option>
            <option value="Goa">Goa</option>
            <option value="Gujarat">Gujarat</option>
            <option value="Haryana">Haryana</option>
            <option value="Himachal Pradesh">Himachal Pradesh</option>
            <option value="Jharkhand">Jharkhand</option>
            <option value="Karnataka">Karnataka</option>
            <option value="Kerala">Kerala</option>
            <option value="Madhya Pradesh">Madhya Pradesh</option>
            <option value="Maharashtra">Maharashtra</option>
            <option value="Manipur">Manipur</option>
            <option value="Meghalaya">Meghalaya</option>
            <option value="Mizoram">Mizoram</option>
            <option value="Nagaland">Nagaland</option>
            <option value="Odisha">Odisha</option>
            <option value="Punjab">Punjab</option>
            <option value="Rajasthan">Rajasthan</option>
            <option value="Sikkim">Sikkim</option>
            <option value="Tamil Nadu">Tamil Nadu</option>
            <option value="Telangana">Telangana</option>
            <option value="Tripura">Tripura</option>
            <option value="Uttarakhand">Uttarakhand</option>
            <option value="Uttar Pradesh">Uttar Pradesh</option>
            <option value="West Bengal">West Bengal</option>
        </select>
        </div>
        Already registered? <a href="orglogin.php">Login.</a>
        <div>
            <input class="button" type="submit" name="Register" value="SignUp">
        </div>
    </div>


    </form>
</body>
</html>