<?php
include("../connect.php");
include("user_func.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["search"])) {
        $city = $_POST["city"];
        $state = $_POST["state"];
        find_organization($city, $state);
    } elseif (isset($_POST["list_all"])) {
        list_all_organizations();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Organization</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #f4f4f9;
            margin: 20px;
        }
        a {
            text-decoration: none;
            background: #007bff;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }
        a:hover {
            background: #0056b3;
        }
        .results-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin: 10px 0;
            width: 320px;
            box-shadow: 3px 3px 15px rgba(0, 0, 0, 0.1);
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 3px 3px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 300px;
        }
        label, input, select, button {
            margin: 8px 0;
            width: 100%;
        }
        input, select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            background: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<a href="userhome.php">Home</a>
<form method="POST">
    <label for="city">City:</label>
    <input type="text" id="city" name="city">

    <div>
    State:
        <select id="state" name="state">
            <option value="" selected disabled>--select-- </option>
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

    <button type="submit" name="search">Find Organizations</button>
</form>
    
<form method="POST">
    <button type="submit" name="list_all">List All Organizations</button>
</form>

</body>
</html>
