<?php session_start();?>
<!DOCTYPE html>
<html>
<header>
    <link href="../Style/index.css" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="../Script/index.js"></script>

</header>
</html>

<?php
require("db.php");

$id = '';
echo "<script src='../Script/index.js'></script>";
echo '<link href="../Style/index.css" rel="stylesheet" type="text/css">';

if (count($_POST) == 4) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "INSERT INTO users (firstname, lastname, email,password)
    VALUES ('$firstname','$lastname','$email','$password')";
    // use exec() because no results are returned

    if ($conn->query($sql) === TRUE) {

        echo "<div class='field-wrap'>
        <p>Registration successful</p>
        <p>Login now</p>
    </div>";
        echo "<script>
            setTimeout(function(){
                window.location.replace('index.php');
            },2000);
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    exit;
}

if (count($_POST) == 2) {
    echo "<script src='../Script/index.js'></script>";
//    check if user exists
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE email = '$email' AND password='$password'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row["id"];
        }
        echo "<div class='form'>
<form method='post' action=" . htmlspecialchars($_SERVER['PHP_SELF']) . ">
    <div class='field-wrap'>

        <input type='number' required autocomplete='off' name='otp' placeholder='OTP'/>
    </div>
    <br>
    <button type='submit' class='button'/>Enter</button>
</form>
</div>";
        $otp = rand(111111, 999999);
        //send otp to email

        require_once("mail_function.php");
        $mail_status = sendOTP($_POST["email"], $otp);

        if ($mail_status == 1) {
            $date = new DateTime();
            $date = $date->format('Y-m-d-H-i-s');
            echo $date;
//          $result = mysqli_query($conn, "INSERT INTO otp_expiry(otp,is_expired,create_at) VALUES ('$otp', 0, '" . date("Y-m-d H:i:s") . "')");
            $sql = "INSERT INTO otp_table(otp,is_expired,created_at) VALUES ($otp, 0, '$date')";
            $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
            echo "got here", $result;
            $current_id = mysqli_insert_id($conn);
            if (!empty($current_id)) {
                $success = 1;
            }
        } else {
            echo "Not sent";
            $error_message = "Email not exists!";
        }
    }
    exit;
} else {
    echo "<p>Please check your details and login again </p>";

}
if (count($_POST) == 1) {
//    login here
    $otp = $_POST['otp'];
    if (!empty($_POST["otp"])) {
        $result = mysqli_query($conn, "SELECT * FROM otp_table WHERE otp='" . $_POST["otp"] . "' AND is_expired!=1 AND NOW() <= DATE_ADD(created_at, INTERVAL 24 HOUR)");
        $count = mysqli_num_rows($result);
        if (!empty($count)) {
            $result = mysqli_query($conn, "UPDATE otp_table SET is_expired = 1 WHERE otp = '" . $_POST["otp"] . "'");
            $success = 2;
//       redirect user to home page
            $_SESSION['id'] = $id;

            echo "<script>
            window.location.replace('home.php');
</script>";
            exit();
        } else {
            $success = 1;
            $error_message = "Invalid OTP!";
            echo "<p>Invalid OTP</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<header>
    <link href="../Style/index.css" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</header>
<body>

<div class="form">

    <ul class="tab-group">
        <li class="tab active"><a href="#signup">Sign Up</a></li>
        <li class="tab"><a href="#login">Log In</a></li>
    </ul>

    <div class="tab-content">
        <div id="signup">
            <h1>Sign Up for Free</h1>

            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">

                <div class="top-row">
                    <div class="field-wrap">
                        <label>
                            First Name<span class="req">*</span>
                        </label>
                        <input type="text" required autocomplete="off" name="firstname"/>
                    </div>

                    <div class="field-wrap">
                        <label>
                            Last Name<span class="req">*</span>
                        </label>
                        <input type="text" required autocomplete="off" name="lastname"/>
                    </div>
                </div>

                <div class="field-wrap">
                    <label>
                        Email Address<span class="req">*</span>
                    </label>
                    <input type="email" required autocomplete="off" name="email"/>
                </div>

                <div class="field-wrap">
                    <label>
                        Set A Password<span class="req">*</span>
                    </label>
                    <input type="password" required autocomplete="off" name="password"/>
                </div>

                <button type="submit" class="button button-block"/>
                Sign Up</button>

            </form>

        </div>

        <div id="login">
            <h1>Welcome Back!</h1>

            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">

                <div class="field-wrap">
                    <label>
                        Email Address<span class="req">*</span>
                    </label>
                    <input type="email" name="email" required autocomplete="off"/>
                </div>

                <div class="field-wrap">
                    <label>
                        Password<span class="req">*</span>
                    </label>
                    <input type="password" name="password" required autocomplete="off"/>
                </div>

                <p class="forgot"><a href="#">Forgot Password?</a></p>

                <button class="button button-block"/>
                Log In</button>

            </form>

        </div>

    </div>
    <!-- tab-content -->

</div>
<!-- /form -->
<script src="../Script/index.js"></script>


</body>

</html>