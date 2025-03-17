<?php
    include("../connect.php");
    include("org_func.php");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST["search"])) {
            $btype = $_POST["btype"];
            $city = $_POST["city"];
            $state = $_POST["state"];
            donors($btype, $city, $state);
        }
        elseif (isset($_POST["list_all"])) {
            allDonors() ;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Organization</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px;
        }
        .results-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }
        .card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            width: 300px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        label, input, select, button {
            margin: 5px 0;
        }
    </style>

</head>
<body>
<a href="orgpage.php">home</a>
<form method="POST">
<div>
    Blood Type:
    <select id="btype" name="btype">
    <option value="A+">A+</option>
        <option value="A-">A-</option>
        <option value="B+">B+</option>
        <option value="B-">B-</option>
        <option value="AB+">AB+</option>
        <option value="AB-">AB-</option>
        <option value="O+">O+</option>
        <option value="O-">O-</option>
        <option value="Golden Blood">Golden Blood</option>
</select>
</div>
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

<button type="submit" name="search">Find Donors</button>

<form method="POST">
    <button type="submit" name="list_all">List all donors</button>
</form>

</form>
</body>
</html>