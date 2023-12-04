<?php
    require_once "functions/connect_db.php";
    session_start();
    if (isset($_SESSION["email"])) {
        $email = $_SESSION["email"];
        $conn = connect_db();
    
        //get user info
        $sql = "SELECT friend_id, profile_name, num_of_friends FROM friends WHERE friend_email='$email'";
        $result = $conn->query($sql);
        $account = $result->fetch_assoc();

        //unfriend
        if (isset($_POST["unfriend"])) {
            $friendId = $_POST["unfriend"];
            $userId = $account["friend_id"];
            $sql = "DELETE FROM myfriends WHERE(friend_id1 = $userId AND friend_id2 = $friendId) 
                    OR (friend_id1 = $friendId AND friend_id2 = $userId)";
            mysqli_query($conn, $sql);

            //update number of friends 
            $sql = "UPDATE friends SET num_of_friends=(SELECT COUNT(myfriends.friend_id1) 
                    FROM myfriends WHERE friends.friend_id = myfriends.friend_id1)";
            mysqli_query($conn, $sql);

            //re-get user info
            $sql = "SELECT friend_id, profile_name, num_of_friends FROM friends WHERE friend_email='$email'";
            $result = $conn->query($sql);
            $account = $result->fetch_assoc();
        };
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
        <?php 
            echo "<h1>", $account["profile_name"], "'s Friend List Page<br></h1>";
            echo "<h1>Total number of friends is: ", $account["num_of_friends"], "</h1>"; 
        ?>
    </div>
    <div class="main-container">
        <div class="form-container">
            <h1>FRIEND LIST</h1>
            <form action="friendlist.php" method="POST" class="forms"> 
                <table class="friendtable">
                <tbody>
                    <?php
                        //select friend connections
                        $sql = "SELECT * FROM friends f1
                                INNER JOIN myfriends mf ON f1.friend_id = mf.friend_id1
                                INNER JOIN friends f2 ON f2.friend_id = mf.friend_id2
                                WHERE f1.friend_email='$email'
                                ORDER BY f2.profile_name";

                        $result = mysqli_query($conn, $sql);

                        $personal_friends = mysqli_fetch_all($result, MYSQLI_ASSOC);

                        //display friends and unfriend button
                        foreach ($personal_friends as $friend) {
                            ?>
                                <tr>
                                    <td><?php echo $friend["profile_name"] ?></td>
                                    <td><button class="formbutton" type ="submit" name="unfriend" value="<?php echo $friend["friend_id"] ?>">Unfriend</button></td>
                                </tr>
                            <?php
                        }
                    ?>
                </tbody>
                </table>
            </form>
            <div class="button">
                <a href="friendadd.php">Add Friend</a>
            </div>
            <div class="button">
                <a href="logout.php">Log Out</a>
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
    <?php
        } else {
            header("Location: login.php");
        }
    ?>
</body>