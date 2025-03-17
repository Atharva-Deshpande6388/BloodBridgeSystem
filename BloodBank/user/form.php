<?php
session_start();
include "../connect.php";




function calculateAge($dob) {
    $dobDate = new DateTime($dob);
    $today = new DateTime();
    $age = $dobDate->diff($today)->y;
    return $age;
}
function calculateEligibilityDate($recentProcedureDate) {
    if (!empty($recentProcedureDate)) {
        $procedureDate = new DateTime($recentProcedureDate);
        $eligibleDate = $procedureDate->modify('+3 months'); // Add 3 months
        return $eligibleDate->format('Y-m-d');
    }
    return null;
}
function calculateNextDonationDate($lastDonationDate) {
    if (!empty($lastDonationDate)) {
        $donationDate = new DateTime($lastDonationDate);
        $nextDonationDate = $donationDate->modify('+4 months'); // Add 4 months
        return $nextDonationDate->format('Y-m-d');
    }
    return null;
}

function calculateSurgeryEligibility($surgery, $surgeryDate) {
    if (!empty($surgeryDate) && !empty($surgery)) {
        $surgeryArray = explode(",", $surgery); // Convert string to array
        $date = new DateTime($surgeryDate);
        
        if (in_array("major", $surgeryArray)) {
            $date->modify('+12 months');
        } elseif (in_array("minor", $surgeryArray)) {
            $date->modify('+6 months');
        } elseif (in_array("B_transfusion", $surgeryArray)) {
            $date->modify('+3 months');
        } else {
            return null;
        }
        return $date->format('Y-m-d');
    }
    return null;
}

if (!isset($_SESSION['user_id'])) {
    echo "Session user ID is not set. Please log in again.";
    header("Location: ../login.php");
    exit();
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $check_sql = "SELECT form_filled FROM users WHERE user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $check_stmt->store_result();
    $check_stmt->bind_result($form_filled);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($form_filled == 1) {
        echo "<script>alert('You have already filled this form.'); window.location.href = '/bloodbank/user/userhome.php';</script>";
        $conn->close();
        exit();
    }


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $target_dir = "uploads/"; // Define the upload directory
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
    if ($check === false) {
        echo "<script>alert('File is not an image.'); window.location.href = '/bloodbank/user/userhome.php';</script>";
        exit();
    }
    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "<script>alert('Only JPG, JPEG, PNG, and GIF files are allowed.'); window.location.href = '/bloodbank/user/userhome.php';</script>";
        exit();
    }
    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
        $profile_image_path = $target_file;
    } else {
        echo "<script>alert('Error uploading file.'); window.location.href = '/bloodbank/user/userhome.php';</script>";
        exit();
    }




    $fname = $_POST['fname'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $age = calculateAge($dob);
    $gender = $_POST['gender'];
    $btype = $_POST['btype'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $occupation = $_POST['occupation'];
    $weight = $_POST['weight'];
    $lastDonation = $_POST['LastDonation'];
    $lastDonationDate = $_POST['LastDonationDate'];
    $recentProcedures = isset($_POST['r_procedures']) ? implode(",", $_POST['r_procedures']) : '';
    $recentProceduresDate = $_POST['recentProcedures'];
    // $diseases = isset($_POST['diseases']) ? implode(",", $_POST['diseases']) : '';
    if (isset($_POST['diseases'])) {
        if (in_array("None", $_POST['diseases'])) {
            $diseases = "None"; // Ignore other selections
        } else {
            $diseases = implode(",", $_POST['diseases']);
        }
    } else {
        $diseases = "";
    }
    $surgery = isset($_POST['surgery']) ? implode(",", $_POST['surgery']) : '';
    $surgeryDate = $_POST['SurgeryDate'];

    $eligibleDate = calculateEligibilityDate($recentProceduresDate);
    $nextDonationDate = calculateNextDonationDate($lastDonationDate);
    $surgeryEligibilityDate = calculateSurgeryEligibility($surgery, $surgeryDate);
    $today = new DateTime();

    $eligibility = "Eligible";
    $reason = "";
    $eligibleAfter = null;

    if ($age < 18 || $age > 65) {
        $eligibility = "Not Eligible";
        $reason .= "Age restriction. ";
    }
    if ($weight < 50) {
        $eligibility = "Not Eligible";
        $reason .= "Underweight. ";
    }
    if (!empty($diseases) && $diseases !=="None") 
    {
        $eligibility = "Not Eligible";
        $reason .= "Medical conditions: $diseases. ";
    }
    if (!empty($eligibleDate) && new DateTime($eligibleDate) > $today) {
        $eligibility = "Not Eligible";
        $reason .= "Recent procedure: $recentProcedures. ";
        $eligibleAfter = $eligibleDate;
    }
    if (!empty($nextDonationDate) && new DateTime($nextDonationDate) > $today) {
        $eligibility = "Not Eligible";
        $reason .= "Recent blood donation. ";
        $eligibleAfter = $nextDonationDate;
    }
    if (!empty($surgeryEligibilityDate) && $surgery !== "None" && new DateTime($surgeryEligibilityDate) > $today) {
        $eligibility = "Not Eligible";
        $reason .= "Surgery recovery. ";
        $eligibleAfter = $surgeryEligibilityDate;
    }

    $check_sql = "SELECT user_id, form_filled FROM users WHERE user_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $user_id);
        $check_stmt->execute();
        $check_stmt->store_result();
        $check_stmt->bind_result($db_user_id, $form_filled);
        $check_stmt->fetch();

        if ($check_stmt->num_rows > 0) {
            if ($form_filled == 1) {
                echo "Profile has already been updated once. Further updates are not allowed.";
                $conn->close();
                exit();
            } 
            else {
                // Check if the email already exists for another user
                $check_email_sql = "SELECT user_id FROM users WHERE email = ? AND user_id != ?";
                $check_email_stmt = $conn->prepare($check_email_sql);
                $check_email_stmt->bind_param("si", $email, $user_id);
                $check_email_stmt->execute();
                $check_email_stmt->store_result();
        
                if ($check_email_stmt->num_rows > 0) {
                    echo "<script>alert('Email already exists. Please use a different email.'); window.location.href = '/bloodbank/user/form.php';</script>";
                    $check_email_stmt->close();
                    $conn->close();
                    exit();
                }
                $check_email_stmt->close();

                //check contact statement
                $check_contact_sql = "SELECT user_id FROM users WHERE contact = ? AND user_id != ?";
                $check_contact_stmt = $conn->prepare($check_contact_sql);
                $check_contact_stmt->bind_param("si", $contact, $user_id);
                $check_contact_stmt->execute();
                $check_contact_stmt->store_result();
        
                if ($check_contact_stmt->num_rows > 0) {
                    echo "<script>alert('Contact number already exists. Please use a different contact number.'); window.location.href = '/bloodbank/user/form.php';</script>";
                    $check_contact_stmt->close();
                    $conn->close();
                    exit();
                }
                $check_contact_stmt->close();

            }

                $sql = "UPDATE users SET fname = ?, email = ?, dob = ?, age = ?, gender = ?, btype = ?, contact = ?, address = ?, city = ?, state = ?, occupation = ?, 
                            weight = ?, last_donation_date = ?, next_donation_date = ?, recent_procedures = ?, recent_procedures_date = ?, eligibility_date = ?, 
                            diseases = ?, surgery = ?, surgery_date = ?, surgery_eligibility_date = ?, eligibility = ?, reason = ?, eligible_after = ?, profile_image = ?, time = NOW(), form_filled = 1
                            WHERE user_id = ?";
                $stmt = $conn->prepare($sql);

                $stmt->bind_param("sssisssssssisssssssssssssi", $fname, $email, $dob, $age, $gender, $btype, $contact, $address, $city, $state,
    $occupation, $weight, $lastDonationDate, $nextDonationDate, $recentProcedures, $recentProceduresDate,
    $eligibleDate, $diseases, $surgery, $surgeryDate, $surgeryEligibilityDate, $eligibility, $reason, $eligibleAfter, $profile_image_path, $user_id);

                if ($stmt->execute()) {
                    echo "<script>alert('Profile Update Successful!'); window.location.href = '/bloodbank/user/userhome.php';</script>";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
        } else {
            echo "User not found.";
        }

        $check_stmt->close();
        $conn->close();
        exit();
    }
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
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
            overflow-y: auto;
        }
        form {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 320px;
            text-align: center;
            overflow-y: auto;
            max-height: 90vh;
        }
        input, select {
            width: calc(100% - 16px);
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: block;
        }
        h2, h5 {
            margin-bottom: 15px;
        }
        input[type="submit"] {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px;
            font-size: 16px;
            width: 100%;
            margin-top: 15px;
        }
        input[type="submit"]:hover {
            background: #218838;
        }
        .checkbox-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            gap: 10px;
            text-align: left;
        }
        .checkbox-container label {
            display: flex;
            align-items: center;
            white-space: nowrap;
        }
        .checkbox-container input {
            margin-right: 8px;
        }
        .center{
            padding: 5px;
            margin-left:500px;
            margin-right:500px;
        }
    </style>

</head>
<body>
    <div class="center">
<form action="form.php" method="POST" enctype="multipart/form-data">
    
    
    <h3 style="text-align:center">User Registration Form</h3>
    
    
    <div>
        <input type="file" name="profile_image" accept="image/*" required>
    </div>

    <div>
        Full Name: <input type="text" name="fname" required>
    </div>

    <div>
        Email: <input type="email" name="email" required>
    </div>

    <div>
        Date of Birth: <input type="date" name="dob" required>
    </div>

    <div>
    <label for="gender">Gender</label>
    <select name="gender" id="gender" required>
        <option value="" selected disabled>--select--</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Transgender">Transgender</option>
    </select>
    </div>

    <div>
    <label for="btype">Blood Type :</label>
    <select name="btype" id="btype" required>
        <option value="" selected disabled>--select--</option>
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

    <div>
        Contact: <input type="text" name="contact" minlength="10" required>
    </div>

    <div>
        Address: <input type="text" name="address" required>
    </div>

    <div>
        City: <input type="text" name="city" required>
    </div>

    <div>
    State:
        <select id="state" name="state" required>
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
    
    <div>
        Occupation: <input type="text" name="occupation">
    </div>

    <div>
        Weight: <input type="number" name="weight">
    </div>

    <div>
    <p>Have you donated blood previously?</p>
    <select name="LastDonation">
        <option value="" selected disabled>--select--</option>
        <option value="yes">Yes</option>
        <option value="no">No</option>
    </select>   
    </div>

    <div>
    <p>If (yes), when?</p>
    <input type="date" name="LastDonationDate">
    </div>

    <div class="checkbox-container">
    <p>Recent Procedures (last 6 months):</p>
            <label><input type="checkbox" name="r_procedures[]" value="tattoo"> Tattoo</label>
            <label><input type="checkbox" name="r_procedures[]" value="ear_piercing"> Ear Piercing</label>
            <label><input type="checkbox" name="r_procedures[]" value="dental_extraction"> Dental Extraction</label>
            <label><input type="checkbox" name="r_procedures[]" value="None"> None</label>
            <label></div></label>
            <div>
    <label for="recentProcedures">Date of Recent Procedure:</label>
            <input type="date" id="recentProcedures" name="recentProcedures">
        </div>


        <div class="checkbox-container">
            <p>Do you suffer from or have suffered any of the following diseases?</p>
            <label><input type="checkbox" name="diseases[]" value="Heart_Disease"> Heart Disease</label>
            <label><input type="checkbox" name="diseases[]" value="Cancer"> Cancer/Malignant Diseases</label>
            <label><input type="checkbox" name="diseases[]" value="Diabetes"> Diabetes</label>
            <label><input type="checkbox" name="diseases[]" value="Hepatitis"> Hepatitis B/C</label>
            <label><input type="checkbox" name="diseases[]" value="STD"> Sexually Transmitted Diseases</label>
            <label><input type="checkbox" name="diseases[]" value="Typhoid"> Typhoid (treated less than a year ago)</label>
            <label><input type="checkbox" name="diseases[]" value="Lung Disease"> Lung Disease</label>
            <label><input type="checkbox" name="diseases[]" value="Tubercolosis"> Tuberculosis</label>
            <label><input type="checkbox" name="diseases[]" value="Allergy"> Allergic disease</label>
            <label><input type="checkbox" name="diseases[]" value="Kidney"> Kidney Disease</label>
            <label><input type="checkbox" name="diseases[]" value="Epilepsy"> Epilepsy (Charay Rog)</label>
            <label><input type="checkbox" name="diseases[]" value="Abnormal_Bleeding"> Abnormal Bleeding Tendency</label>
            <label><input type="checkbox" name="diseases[]" value="Jaundice"> Jaundice (treated less than a year ago)</label>
            <label><input type="checkbox" name="diseases[]" value="Malaria"> Malaria (treated less than 6 months ago)</label>
            <label><input type="checkbox" name="diseases[]" value="Fainting"> Fainting Spells</label>
            <label><input type="checkbox" name="diseases[]" value="HIV"> HIV/AIDS</label>
            <label><input type="checkbox" name="diseases[]" value="Liver"> Chronic Liver Diseases</label>
            <label><input type="checkbox" name="diseases[]" value="Auto immune"> Autoimmune Diseases</label>
            <label><input type="checkbox" name="diseases[]" value="Thyroid"> Thyroid Disorder</label>
            <label><input type="checkbox" name="diseases[]" value="BP"> High Blood Pressure</label>
            <label><input type="checkbox" name="diseases[]" value="SCD"> Sickle Cell Disease</label>
            <label><input type="checkbox" name="diseases[]" value="None"> None</label>
    </div>

    <div class="checkbox-container">
    <p>Surgery History (last 6 months):</p>
            <label><input type="checkbox" name="surgery[]" value="major"> Major Surgery</label>
            <label><input type="checkbox" name="surgery[]" value="minor"> Minor Surgery</label>
            <label><input type="checkbox" name="surgery[]" value="B_transfusion"> Blood transfusion</label>
            <label><input type="checkbox" name="surgery[]" value="none"> None</label>
    </div>
    <div>
    <label for="SurgeryDate">Date of Recent Procedure:</label>
            <input type="date" id="SurgeryDate" name="SurgeryDate">
        </div>

    <div>
            <input type="submit" name="Submit" value="Submit Form">
        </div>

        </div>
    </form>
    </div>
</body>
</html>