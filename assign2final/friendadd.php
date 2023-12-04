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

        //addfriend
        if (isset($_POST["addfriend"])) {
            $friendId = $_POST["addfriend"];
            $userId = $account["friend_id"];
            $sql = "INSERT INTO myfriends (friend_id1, friend_id2) VALUES ($userId, $friendId), ($friendId, $userId)";
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
            <h1>ADD FRIENDS</h1>
            <form action="friendadd.php" method="POST" class="forms"> 
                <table class="friendtable">
                <tbody>
                    <?php
                        //select friend connections
                        $userId = $account["friend_id"];
                        $sql = "SELECT * FROM friends WHERE friend_id NOT IN 
                                (SELECT friend_id2 FROM myfriends WHERE friend_id1 = $userId) 
                                AND friend_id != $userId"; 

                        $result = mysqli_query($conn, $sql);

                        $not_friends = mysqli_fetch_all($result, MYSQLI_ASSOC);

                        //pagination 
                        $resultPerPage = 5;
                        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
                        $offset = ($currentPage - 1) * $resultPerPage;
                        $totalPages = ceil(count($not_friends) / $resultPerPage);

                        $paginatedFriends = array_slice($not_friends, $offset, $resultPerPage);

                        //display friends and unfriend button
                        foreach ($paginatedFriends as $friend) {
                            ?>
                                <tr>
                                    <td><?php echo $friend["profile_name"] ?></td>
                                    <td>
                                        <?php 
                                            $strangerId = $friend["friend_id"];
                                            $sql = "SELECT * FROM myfriends WHERE friend_id1 = $userId AND 
                                            friend_id2 IN (SELECT friend_id2 FROM myfriends mf WHERE friend_id1 = $strangerId)";
                                            $result = mysqli_query($conn, $sql);
                                            $mutual = mysqli_fetch_all($result);
                                            $mutualCount = count($mutual);
                                            echo $mutualCount, " mutual friends.";
                                        ?>
                                    </td>
                                    <td><button class="formbutton" type ="submit" name="addfriend" value="<?php echo $friend["friend_id"] ?>">Add Friend</button></td>
                                </tr>
                            <?php
                        }

                        // Previous and Next buttons
                        if ($currentPage  > 1) {
                            ?>
                            <div class="button-prev"><a href="friendadd.php?page=<?php echo ($currentPage - 1); ?>">Previous</a></div>
                            <?php
                        }

                        if ($currentPage < $totalPages) {
                            ?>
                            <div class="button-next"><a href="friendadd.php?page=<?php echo ($currentPage + 1); ?>">Next</a></div>
                            <?php
                        }
                    ?>
                </tbody>
                </table>
            </form>
            <div class="button">
                <a href="friendlist.php">Friend List</a>
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