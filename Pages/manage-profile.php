<?php
session_start();
require('db.php');

//If your session isn't valid, it returns you to the login screen for protection
if (!isset($_SESSION['id'])) {
    header("location:access-denied.php");
}
$id = $_SESSION['id'];
$content = "";
//retrive voter details from the users table
$sql = "SELECT * FROM users WHERE id = '$id'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
//or die("There are no records to display ... \n" . mysqli_error());
if (mysqli_num_rows($result) < 1) {
    $result = null;
}
$row = $result->fetch_assoc();
if ($row) {
    // get data from db
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $email = $row['email'];
    $address = $row['address'];
    $occupation = $row['occupation'];
    $dob = $row['dob'];
    $state_of_origin = $row['state_of_origin'];
}

if (isset($_POST['update'])) {
    $firstname = addslashes($_POST['firstname']); //prevents types of SQL injection
    $lastname = addslashes($_POST['lastname']); //prevents types of SQL injection
    $email = $_POST['email'];
    $password = $_POST['password'];
    $occupation = $_POST['occupation'];
    $address = $_POST['address'];
    $state_of_origin = $_POST['state_of_origin'];
    $dob = $_POST['dob'];

    //$newpass = md5($myPassword); //This will make your password encrypted into md5, a high security hash

    $sql = "UPDATE users SET firstname='$firstname', lastname='$lastname', email='$email',
    occupation = '$occupation', password='$password',address='$address' ,state_of_origin='$state_of_origin', dob='$dob' WHERE id = '$id'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    // redirect back to profile
    if ($result == true) {
        $_SESSION["message"] = 'Profile updated successfully';
        $_SESSION["message_status"] = 'success';
//    header("location:home.php");

        echo "<script>
                window.location.replace('home.php');
                </script>";
        exit();
    } else {
        $_SESSION["message"] = 'Profile update unsuccessful';
        $_SESSION["message_status"] = 'failure';
    }
    session_write_close();

}

$content = '
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->

<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->

<div class="jumbotron">
        <!-- ################################################################################################ -->
        <div class="jumbotron bg-secondary">
        <div class="card-group text-md-left">
        <div class="col-lg-1"></div>
        <div class="card borderless bg-secondary ">
        <h2 class=""> My Profile</a></h2>

            <p >First Name:'.$firstname.'</p>

                                <p >Last Name:'.$lastname.'
                                </p>

                                <p>Email:'. $email.'</p>

                                <p>Occupation:'.$occupation.'
                                </p>

                                <p>State of Origin:'.$state_of_origin.'
                                </p>

                                <p>Date of Birth:'.$dob.'
                                </p>

                                <p>Address:'.$address.'
                                </p>

                                <p>Password: Encrypted
                                </p>
                                ';
                                $date = (new DateTime())->format('Y-m-d');
                                $diff = strtotime($date) - strtotime($dob);
                                $years = floor($diff / (365 * 60 * 60 * 24));
                                if ($years >= 18) {
                                    $content.='<p>Fingerprint Capture: Yes
                                    </p>
                                    
                                    <p>Password: Yes
                                    </p>';
                                }
                           $content.= '
                                </div>
                               </div>
                               </div>

                    <div class = "card card-register mx-auto mt-5">
                                <div class="card-header">Update Profile</div>
                                    <div class="card-body" id="signup">
                        <form action="manage-profile.php ?>" method="post">
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
                            <input type="text" id="address" required autocomplete="off" name="address" class="form-control"
                                   placeholder="Address" autofocus="autofocus"/>
                            <label for="address">
                               Address
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="password" id="password" required autocomplete="off" name="password" class="form-control"
                                   placeholder="New Password" autofocus="autofocus"/>
                            <label for="password">
                                New Password
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="text" id="state_of_origin" required autocomplete="off" name="state_of_origin" class="form-control"
                                   placeholder="State of Origin" autofocus="autofocus"/>
                            <label for="state_of_origin">
                                State of Origin
                            </label>
                        </div>
                    </div>

                   <div class="form-group">
                        <div class="form-label-group">
                            <input type="date" id="dob" required autocomplete="off" name="dob" class="form-control"
                                   placeholder="Date of Birth" autofocus="autofocus"/>
                            <label for="dob">
                                Date of Birth
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="text" id="occupation" required autocomplete="off" name="occupation" class="form-control"
                                   placeholder="Occupation" autofocus="autofocus"/>
                            <label for="occupation">
                                Occupation
                            </label>
                        </div>
                    </div>

                    <button name="update" type="submit" class="btn btn-primary btn-block"/>
                    Update Profile</button>

                        </form>
                    </table>
            </li>
        </ul>
</div>';

$title = "Manage Profile Page";
require_once("layout.php");
?>





