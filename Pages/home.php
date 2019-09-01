<?php
session_start();
/**
 * Created by PhpStorm.
 * User: Victor
 * Date: 30/08/2019
 * Time: 8:06 PM
 */
require_once('db.php');

$id = $_SESSION['id'];
if(!isset($_SESSION['id'])){
    header("location:access-denied.php");
    exit();
}
$sql = "SELECT dob FROM users WHERE id ='$id'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$dob = '';
if (mysqli_num_rows($result) > 0) {
    while ($row = $result->fetch_assoc()) {
        $dob = $row["dob"];
    }
}
$eligibility = '';
if ($dob == NULL){
    $eligibility = "<div>
    <p><a href='manage-profile.php'>Update your profile to vote</a></p>
    </div>";
}
else{
    $date = (new DateTime())->format('Y-m-d');
    $diff = strtotime($date) -  strtotime($dob);
    $years = floor($diff / (365*60*60*24));
    if ($years>=18){
        $eligibility = "<div>
        <p>You are eligible to vote</p>
        </div>";
    }
    else{
        $eligibility = "<div>
        <p>Sorry, You are not eligible to vote</p>
        </div>";
    }
}

$election_result = "<div>
<p><a href='view_result.php'> View election result</a></p>
</div>";
$title = "Home Page";
$content = $eligibility.$election_result;
require_once("layout.php");
?>


