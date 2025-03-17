    <?php
    // session_start();
    include("../connect.php");
    include("user_func.php");
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                /* font-family: Arial, sans-serif; */
            }
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-color: #f4f4f4;
                /* background image control below */
                background-image: url('homebgimg.jpg');
                background-size: cover;
                background-repeat: no-repeat; 
                background-position: center;
            }
            .profile-card {
                background: rgba(174, 124, 124, 0.91);
                width: 300px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.55);
                text-align: center;
                padding: 20px;
            }
            .profile-card img {
                width: 100px;
                height: 100px;
                border-radius: 50%;
                border: 3px solidrgba(11, 29, 41, 0.81);
            }
            .profile-card h2 {
                margin: 10px 0;
                font-size: 1.5em;
            }
            .profile-card p {
                color: #777;
            }
            .profile-card .btn {
                display: inline-block;
                margin-top: 15px;
                padding: 10px 20px;
                background:rgba(146, 189, 217, 0.35);
                color: white;
                text-decoration: none;
                border-radius: 5px;
                transition: 0.3s;
            }
            .profile-card .btn:hover {
                background:rgb(7, 26, 38);
            }
            .txt-container{
                color:white;
                font-family: "Times New Roman", Times, serif;
                text-align: left;
            }
            .side-image {
            position: absolute;
            width: 150px;
            height: auto;
            top: 50%;
            transform: translateY(-50%);
        }
        .left-image {
            left: 10px;
            width: 500px;
        }
        .right-image {
            right: 10px;
            width: 500px;
        }
        </style>
    </head>
    <body>

    <!-- Side Images -->
<img src="dd.png" alt="Side Image" class="side-image left-image">
<img src="ee.png" alt="Side Image" class="side-image right-image">

        <div class="profile-card">
            <h2><?php echo user_details($user_id); ?></h2>
            <a href="form.php" class="btn">Form</a>
            <a href="orgfind.php" class="btn">Donate</a>
            <a href="userhome.php" class="btn">Home</a>
        </div>
    </body>

    </html> 