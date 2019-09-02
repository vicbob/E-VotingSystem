<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login/Register</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin.css" rel="stylesheet">
    <link href="../Style/index.css" rel="stylesheet">
    <script src="../Script/index.js"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level plugin JavaScript-->
    <script src="../vendor/chart.js/Chart.min.js"></script>
    <script src="../vendor/datatables/jquery.dataTables.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin.min.js"></script>

    <!-- Demo scripts for this page-->
    <script src="../js/demo/datatables-demo.js"></script>
    <script src="../js/demo/chart-area-demo.js"></script>
</head>
</html>

<?php
require("db.php");
if (isset($_POST['logout'])) {
    session_unset();

// destroy the session
    session_destroy();
}
function process_form()
{
    global $conn;
    static $id = 'some_id';
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
    } elseif (count($_POST) == 2) {
        echo "<script src='../Script/index.js'></script>";
//    check if user exists
        $email = $_POST['email'];
        $password = $_POST['password'];
        $sql = "SELECT * FROM users WHERE email = '$email' AND password='$password'";
        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

        if (mysqli_num_rows($result) > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row["id"];
                $name = $row["firstname"];
                $_SESSION['id'] = $id;
                $_SESSION['firstname'] = $name;

            }
            echo "<body class=\"bg-dark\">
                    <div class=\"container\">
                        <div class=\"card card-login mx-auto mt-5\" id=\"size-div\">
                            <div class='card-body'>
                                <form method='post' action=" . htmlspecialchars($_SERVER['PHP_SELF']) . ">
                                    <div class=\"form-group\">
                                        <div class=\"form-label-group\">
                                            <input type='text' required autocomplete='off' autofocus=\"autofocus\" id=\"otp\" name='otp' placeholder='OTP' class=\"form-control\"
                                            />
                                            <label for=\"otp\">OTP</label>
                                            <br>
                                            <button type='submit' class='btn btn-primary btn-block'/>Enter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                     </div>
                  </body>";
            $otp = rand(111111, 999999);
            //send otp to email

            try {
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
                    session_write_close();
                } else {
                    echo "Not sent";
                    $error_message = "Email not exists!";
                }
                exit;
            }catch(\Exception $e){
                $_SESSION['message'] = "An error occured while sending OTP";
                $_SESSION['message_status'] = 'failure';
                session_write_close();
                echo '<script> window.location.replace("index.php")</script>';
            }
        } else {
            echo "<p>Please check your details and login again </p>";

        }
    } elseif (isset($_POST['otp'])) {
//    login here
        $otp = $_POST['otp'];
        if (!empty($_POST["otp"])) {
            $result = mysqli_query($conn, "SELECT * FROM otp_table WHERE otp='" . $_POST["otp"] . "' AND is_expired!=1 AND NOW() <= DATE_ADD(created_at, INTERVAL 24 HOUR)");
            $count = mysqli_num_rows($result);
            if (!empty($count)) {
                $result = mysqli_query($conn, "UPDATE otp_table SET is_expired = 1 WHERE otp = '" . $_POST["otp"] . "'");
                $success = 2;
//       redirect user to home page

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
}

process_form();
?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login/Register</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin.css" rel="stylesheet">
    <link href="../Style/index.css" rel="stylesheet">
    <script src="../Script/index.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body class="bg-dark">

<div class="container">
    <?php
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $status = $_SESSION['message_status'];
        if ($status == "success") {
            echo '<br><div class="alert alert-success alert-dismissible fade show" role="alert">'.
                $message.
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
        } else {
            echo '<br><div class="alert alert-danger alert-dismissible fade show" role="alert">'.
                $message.
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
        }
        unset($_SESSION['message']);
        unset($_SESSION['message_status']);
    }
    ?>
    <div class="card card-register mx-auto mt-5" id="size-div">
        <div class="card-header">
            <ul class="tab-group">
                <li class="tab active"><a href="#signup">Sign Up</a></li>
                <li class="tab"><a href="#login">Log In</a></li>
            </ul>
        </div>

        <div class="tab-content">
            <div class="card-body" id="signup">

                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-label-group">
                                    <input type="text" id="firstname" class="form-control" required autocomplete="off"
                                           name="firstname" placeholder="First Name" autofocus="autofocus"/>
                                    <label for="firstname">
                                        First Name
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-label-group">
                                    <input type="text" id="lastname" required autocomplete="off" name="lastname"
                                           class="form-control" autofocus="autofocus" placeholder="Last Name"/>
                                    <label for="lastname">
                                        Last Name
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="email" id="email" required autocomplete="off" name="email" class="form-control"
                                   placeholder="Email Address" autofocus="autofocus"/>
                            <label for="email">
                                Email Address
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label-group">
                            <input id="password" type="password" required autocomplete="off" name="password"
                                   class="form-control" placeholder="Password" autofocus="autofocus"/>
                            <label for="password">
                                Password
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block"/>
                    Sign Up</button>

                </form>
            </div>


            <div class="card-body" id="login">
                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">

                    <div class="form-group">
                        <div class="form-label-group">

                            <input class="form-control" id="email2" autofocus="autofocus" type="email" name="email"
                                   required placeholder="Email Address"/>
                            <label for="email2">
                                Email Address
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="password" class="form-control" id="password2" placeholder="Password"
                                   name="password" required
                                   autocomplete="off" autofocus="autofocus"/>
                            <label for="password2">
                                Password
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="remember-me">
                                Remember Password
                            </label>
                        </div>
                    </div>
                    <p class="forgot"><a href="#">Forgot Password?</a></p>

                    <button type="submit" class="btn btn-primary btn-block"/>
                    Log In</button>

                </form>


            </div>
        </div>
        <!-- tab-content -->
    </div>
</div>
<!-- /form -->
<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Page level plugin JavaScript-->
<script src="../vendor/chart.js/Chart.min.js"></script>
<script src="../vendor/datatables/jquery.dataTables.js"></script>
<script src="../vendor/datatables/dataTables.bootstrap4.js"></script>

<!-- Custom scripts for all pages-->
<script src="../js/sb-admin.min.js"></script>

<!-- Demo scripts for this page-->
<script src="../js/demo/datatables-demo.js"></script>
<script src="../js/demo/chart-area-demo.js"></script>

</body>

</html>