<?php
session_start();
require('db.php');

//If your session isn't valid, it returns you to the login screen for protection
if (!isset($_SESSION['id'])) {
    header("location:access-denied.php");
}
$id = $_SESSION['id'];
echo $id;
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
        $_SESSION["message_status"] = 'Success';
//    header("location:home.php");

        echo "<script>
                window.location.replace('home.php');
                </script>";
        exit();
    }
    else{
        $_SESSION["message"] = 'Profile updated unsuccessful';
        $_SESSION["message_status"] = 'Failure';
    }

}

$content = '
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->

<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->

<div class="wrapper bgded overlay" style="">
        <!-- ################################################################################################ -->
        <h2 class="font-x3 uppercase btmspace-80 underlined"> Online <a href="#">Voting</a></h2>
        <ul class="nospace group">
            <li class="one_half first">
                <blockquote>
                    <table border="0" width="620" align="center">
                        <CAPTION><h3>MY PROFILE</h3></CAPTION>
                        <form>
                            <br>
                            <tr><td></td><td></td></tr>
                            <tr>
                                <td style="color:#000000"; >Id:</td>
                                <td style="color:#000000"; ><?php echo $id; ?></td>
                            </tr>
                            <tr>
                                <td style="color:#000000"; >First Name:</td>
                                <td style="color:#000000"; ><?php echo $firstname; ?></td>
                            </tr>
                            <tr>
                                <td style="color:#000000"; >Last Name:</td>
                                <td style="color:#000000"; ><?php echo $lastname; ?></td>
                            </tr>
                            <tr>
                                <td style="color:#000000"; >Email:</td>
                                <td style="color:#000000"; ><?php echo $email; ?></td>
                            </tr>
                            <tr>
                                <td style="color:#000000"; >Occupation:</td>
                                <td style="color:#000000"; ><?php echo $occupation; ?></td>
                            </tr>
                            <tr>
                                <td style="color:#000000"; >State of Origin:</td>
                                <td style="color:#000000"; ><?php echo $state_of_origin; ?></td>
                            </tr>
                            <tr>
                                <td style="color:#000000"; >Date of Birth:</td>
                                <td style="color:#000000"; ><?php echo $dob; ?></td>
                            </tr>
                            <tr>
                                <td style="color:#000000"; >Password:</td>
                                <td style="color:#000000"; >Encrypted</td>
                            </tr>
                    </table>
                    </form>

                </blockquote>

            </li>
            <li class="one_half">
                    <table  border="0" width="620" align="center">
                        <CAPTION><h3>UPDATE PROFILE</h3></CAPTION>
                        <form action="manage-profile.php ?>" method="post">
                            <table align="center">
                                <tr><td  style="background-color:#0000ff"  >First Name:</td><td style="background-color:#0000ff"  ><input  style="color:#000000"; type="text" " name="firstname" maxlength="15" value=""></td></tr>

                                <tr><td style="background-color:#bf00ff">Last Name:</td><td style="background-color:#bf00ff"><input style="color:#000000";  type="text" " name="lastname" maxlength="15" value=""></td></tr>

                                <tr><td style="background-color:#0000ff" >Email Address:</td><td style="background-color:#0000ff"><input style="color:#000000";  type="text"" name="email" maxlength="100" value=""></td></tr>

                                <tr><td style="background-color:#bf00ff" >Address:</td><td style="background-color:#bf00ff"><input  style="color:#000000";  type="text"  " name="address" maxlength="100" value=""></td></tr>

                                <tr><td style="background-color:#0000ff" >New Password:</td><td style="background-color:#0000ff" ><input  style="color:#000000";  type="password"" name="password" maxlength="15" value=""></td></tr>

                                <tr><td style="background-color:#bf00ff" >State of Origin:</td><td style="background-color:#bf00ff"><input  style="color:#000000";  type="text"  " name="state_of_origin" maxlength="100" value=""></td></tr>

                                <tr><td style="background-color:#bf00ff" >Date of Birth:</td><td style="background-color:#bf00ff"><input  style="color:#000000";  type="date"  " name="dob"  value=""></td></tr>

                                <tr><td style="background-color:#bf00ff" >Occupation:</td><td style="background-color:#bf00ff"><input  style="color:#000000";  type="text"  " name="occupation" maxlength="100" value=""></td></tr>

                                <tr><td style="background-color:#0000ff" >&nbsp;</td></td><td style="background-color:#0000ff" ><input style="color:#ff0000";  type="submit" name="update" value="Update Profile"></td></tr>
                            </table>
                        </form>
                    </table>
            </li>
        </ul>
</div>';

$title = "Manage Profile Page";
require_once("layout.php");
?>





