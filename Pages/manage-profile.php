<?php
session_start();
require('connection.php');

//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['member_id'])){
    header("location:access-denied.php");
}
//retrive voter details from the tbmembers table
$result= mysqli_query("SELECT * FROM tbMembers WHERE member_id = '$_SESSION[member_id]'");
//or die("There are no records to display ... \n" . mysqli_error());
if (mysqli_num_rows($result)<1){
    $result = null;
}
$row = mysqli_fetch_array($result);
if($row)
{
    // get data from db
    $stdId = $row['member_id'];
    $firstName = $row['first_name'];
    $lastName = $row['last_name'];
    $email = $row['email'];
    $voter_id = $row['voter_id'];
}
?>

<?php
// updating sql query
if (isset($_POST['update'])){
    $id = addslashes( $_GET[id]);
    $firstname = addslashes( $_POST['firstname'] ); //prevents types of SQL injection
    $lastname = addslashes( $_POST['lastname'] ); //prevents types of SQL injection
    $email = $_POST['email'];
    $password = $_POST['password'];
    $occupation = $_POST['occupation'];
    $state_of_origin = $_POST['state_of_origin'];
    $dob = $_POST['dob'];

    $newpass = md5($myPassword); //This will make your password encrypted into md5, a high security hash

    $sql = mysqli_query( "UPDATE users SET firstname='$firstname', last_name='$lastname', email='$email',
occupation = '$occupation', password='$password', state_of_origin='$state_of_origin', dob='$dob' WHERE id = '$id'" );
    //or die( mysqli_error() );

    // redirect back to profile
    header("Location: manage-profile.php");
}
?>




<!DOCTYPE html>

<html>
<head>
    <title>online voting</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <link href="layout/styles/layout.css" rel="stylesheet" type="text/css" media="all">
    <!-- <link href="css/user_styles.css" rel="stylesheet" type="text/css" /> -->
    <script language="JavaScript" src="js/user.js">
    </script>

</head>
<body id="top">
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<div class="wrapper row1">
    <header id="header" class="hoc clear">
        <!-- ################################################################################################ -->
        <div id="logo" class="fl_left">
            <h1><a href="home.php">ONLINE VOTING</a></h1>
        </div>
        <!-- ################################################################################################ -->
        <nav id="mainav" class="fl_right">
            <ul class="clear">
                <li class="active"><a href="home.php">Home</a></li>

                <li><a href="index.php">Logout</a></li>
            </ul>
        </nav>
        <!-- ################################################################################################ -->
    </header>
</div>
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->

<div class="wrapper bgded overlay" style="background-image:url('/images/demo/backgrounds/background1.jpg');">
    <section id="testimonials" class="hoc container clear">
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
                <blockquote>
                    <table  border="0" width="620" align="center">
                        <CAPTION><h3>UPDATE PROFILE</h3></CAPTION>
                        <form action="manage-profile.php?id=<?php echo $_SESSION['id']; ?>" method="post" onsubmit="return updateProfile(this)">
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
                </blockquote>
            </li>
        </ul>
    </section>
</div>
</body>
</html>