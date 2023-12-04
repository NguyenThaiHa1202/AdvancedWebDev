<?php
    require_once "functions/connect_db.php";
    $err = "";
    $email = "";
    $name = "";
    function check_param(){
        global $err;
        $email = $_POST["email"] ? $_POST["email"] : null;
        $name = $_POST["name"] ? $_POST["name"] : null;
        $pwrd = $_POST["password"] ? $_POST["password"] : null;
        $pwrd_chk = $_POST["confirm_password"] ? $_POST["confirm_password"] : null;

        if (!check_blank($email, "Email")) return;
        if (!check_blank($name, "Name")) return;
        if (!check_blank($pwrd, "Password")) return;
        if (!check_blank($pwrd_chk, "Check password")) return;

        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $err = "Name can only contain letters and whitespace.";
            return;
        }

        if (!preg_match("/^[a-zA-Z0-9]*$/", $pwrd)) {
            $err = "Password can only contain letters and numbers.";
            return;
        }

        if ($pwrd !== $pwrd_chk) {
            $err = "Password does not match.";
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $err = "Invalid Email Format.";
            return;
        }

        $conn = connect_db();

        $email = mysqli_real_escape_string($conn, $email);
        $sql = "SELECT * FROM friends WHERE friend_email='$email'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $err = "Email already exists";
            mysqli_close($conn);
            return;
        }

        $email = mysqli_real_escape_string($conn, $email);
        $name = mysqli_real_escape_string($conn, $name);
        $pwrd = mysqli_real_escape_string($conn, $pwrd);
        $sql = "INSERT INTO friends (friend_email, profile_name, password, num_of_friends, date_started) VALUES ('$email', '$name', '$pwrd', 0, NOW())";

        if (mysqli_query($conn, $sql)) {
            session_start();
            $_SESSION["email"] = $email;
            echo "<div class='alert alert-success'>Account created successfully!</div>";
            mysqli_close($conn);
            header("Location: friendadd.php");
            die();
        } 
    } 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        check_param();
    }

    function check_blank($value, $param_name){
        global $err;
        if ($value == null) {
            $err = $param_name." cannot be blank";
            return false;
        }
        return true;
    }
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
        <h1>Sign Up Page</h1>
    </div>
    <div class="main-container">
        <div class="form-container">
            <h1>SIGN UP FORM</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="forms">
                <?php if($err !== ""): ?>
                    <span class="error"><?=$err?></span>
                <?php endif; ?>

                <br>
                <label for="email">Email:</label><br>
                <input type="text" name="email" value="<?php echo $email; ?>"><br>

                <label for="name">Profile Name:</label><br>
                <input type="text" name="name" value="<?php echo $name; ?>"><br>

                <label for="password">Password:</label><br>
                <input type="password" name="password"><br>

                <label for="confirm_password">Confirm Password:</label><br>
                <input type="password" name="confirm_password"><br>

                <input type="submit" value="Register">
                <input type="reset" value="Clear">

                <div class="button">
                    <a href="index.php">Home</a>
                </div>
            </form>
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