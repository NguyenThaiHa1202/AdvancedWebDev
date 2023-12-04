<?php
    require_once "functions/connect_db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="My Friend System" />
    <meta name="keywords" content="PHP" />
    <meta name="author" content="Nguyen Thai Ha" />
    <meta name="studentid" content = "103430053"/>
    <link rel="stylesheet" href="./style/style.css">
    <title>Assignment 2 - My Friend System</title>
</head>
<body>
<div class="topbar">
        <h1>MY FRIEND SYSTEM</h1>
        <h1>Home Page</h1>
    </div>
    <div class="main-container">
        <div class="clicker-container">
            <div class="clicker"><a href="signup.php" class="fill-div">SIGN UP</a></div>
            <div class="clicker"><a href="login.php" class="fill-div">LOG IN</a></div>
            <div class="clicker"><a href="about.php" class="fill-div">ABOUT</a></div>
        </div>
        <div>
            <div class="update-title">STATUS REPORT</div>
            <div class="update-container">
                <?php   
                    $conn = connect_db();

                    //create friends table if not exist
                    $sql = "CREATE TABLE IF NOT EXISTS friends (
                        friend_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        friend_email VARCHAR(50) NOT NULL UNIQUE,
                        password VARCHAR(20) NOT NULL,
                        profile_name VARCHAR(30) NOT NULL,
                        date_started DATE NOT NULL,
                        num_of_friends INT(10) UNSIGNED
                    )";
                    
                    //report on creation success
                    if (mysqli_query($conn, $sql)) {
                        echo "<p>Table 'friends' created successfully.</p><br>";
                    } else {
                        echo "<p>Error creating table: " . mysqli_error($conn), "</p>";
                    }

                    //create myfriends table if not exist 
                    $sql = "CREATE TABLE IF NOT EXISTS myfriends (
                        friend_id1 INT(10) NOT NULL,
                        friend_id2 INT(10) NOT NULL,
                        UNIQUE(friend_id1, friend_id2)
                    )";
                    
                    //report on creation success
                    if (mysqli_query($conn, $sql)) {
                        echo "<p>Table 'myfriends' created successfully.</p><br>";
                    } else {
                        echo "<p>Error creating table: " . mysqli_error($conn), "</p>";
                    }

                    //insert data into friends table
                    $sql = "INSERT IGNORE INTO friends (friend_email, password, profile_name, date_started, num_of_friends)
                    VALUES
                    ('user1@gmail.com', 'password1', 'John Doe', '2021-01-01', 0),
                    ('user2@gmail.com', 'password2', 'Jane Smith', '2021-02-03', 0),
                    ('user3@gmail.com', 'password3', 'Michael Davis', '2021-02-03', 0),
                    ('user4@gmail.com', 'password4', 'Sarah Connor', '2021-02-03', 0),
                    ('user5@gmail.com', 'password5', 'Chris Lewis', '2021-02-03', 0),
                    ('user6@gmail.com', 'password6', 'David Martinez', '2021-02-03', 0),
                    ('user7@gmail.com', 'password7', 'Marty McFly', '2021-02-03', 0),
                    ('user8@gmail.com', 'password8', 'Luke Skywalker', '2021-02-03', 0),
                    ('user9@gmail.com', 'password9', 'Liu Kang', '2021-02-03', 0),
                    ('userx@gmail.com', 'passwordx', 'Muskratoid', '2021-02-03', 0)
                    ";

                    //report on creation success
                    if (mysqli_query($conn, $sql)) {
                        echo "<p>Sample records inserted into 'friends' table successfully.</p><br>";
                    } else {
                        echo "<p>Error inserting sample records: " . mysqli_error($conn), "<p>";
                    }
                    
                    //insert data into myfriends table
                    $sql = "INSERT IGNORE INTO myfriends (friend_id1, friend_id2)
                            VALUES
                            (1, 2),(3, 4),(5, 6),(7, 8),
                            (9, 10),(2, 3),(4, 5),(6, 7),
                            (8, 9),(10, 1),(2, 1),(4, 3),
                            (6, 5),(8, 7),(10, 9),(3, 2),
                            (5, 4),(7, 6),(9, 8),(1, 10)
                            ";

                    //report on creation success
                    if (mysqli_query($conn, $sql)) {
                        echo "<p>Sample records inserted into 'myfriends' table successfully.</p><br>";
                    } else {
                        echo "<p>Error inserting sample records: " . mysqli_error($conn), "</p>";
                    }

                    //update friend numbers
                    $sql = "UPDATE friends SET num_of_friends=(SELECT COUNT(myfriends.friend_id1) 
                            FROM myfriends WHERE friends.friend_id = myfriends.friend_id1)";
                    mysqli_query($conn, $sql);
                ?> 
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="in-footer">
            <div><p>Name: Nguyen Thai Ha</p></div>
            <div><p>SID: 103430053</p></div>
            <div><p>Email: proxyaddress1202@gmail.com</p></div>
        </div>
        <p>Statement: “I declare that this assignment is my individual work. I have not worked collaboratively nor
        have I copied from any other student&#39s work or from any other source.”</p>
    </div>   
</body>