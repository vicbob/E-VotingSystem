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
if (!isset($_SESSION['id'])) {
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
if ($dob == NULL) {
    $eligibility = "<div class=\"jumbotron\">
                           <div class='card-deck col-lg-10'>
                                <div class='col-lg-1'></div>
                        <a href='manage-profile.php' class=\"card bg-primary text-white mb-3\" style=\"width: 25rem;\">
                              <div class=\"card-body\">
                                    <p class='card-text text-center'>Update your profile to vote</p>
                               </div>
                          </a>";
} else {
    $date = (new DateTime())->format('Y-m-d');
    $diff = strtotime($date) - strtotime($dob);
    $years = floor($diff / (365 * 60 * 60 * 24));
    if ($years >= 18) {
        $eligibility = " <div class=\"jumbotron align-content\">
                            <div class='card-deck'>
                                <div class='col-lg-1'></div>
                        <a href='vote.php' class=\"card bg-primary text-white mb-3\" style=\"width: 25rem;\">
                            <img class=\"card-img-top\" style=\"height:16rem;\" src=\"../images/vote.jpg\" alt=\"vote image\">
                              <div class=\"card-body\">
                                    <p class='card-text text-center'>You are eligible to vote</p>
                               </div>
                          </a>";
    } else {
        $eligibility = "<div class=\"jumbotron\">
                            <div class='card-deck col-lg-10'>
                                <div class='col-lg-1'></div>
                        <div class=\"card bg-primary text-white mb-3\" style=\"width: 25rem;\">
                              <div class=\"card-body\">
                                    <p class='card-text text-center'>Sorry,you are not eligible to vote</p>
                               </div>
                          </div>";
    }
}

$election_result = "<a class=\"card bg-primary text-white mb-3\" href='view_result.php' style=\"width: 25rem;\">
                              <img class=\"card-img-top\" style=\"height:16rem;\" src=\"../images/result.png\" alt=\"vote image\">
                              <div class=\"card-body\">
                              <p class='card-text text-center'> View election result</p>
                               </div>
                          </a>
                        <div class='col-lg-1'></div>
                          </div>
                          </div>";
$title = "Home Page";
$content = $eligibility . $election_result;
require_once("layout.php");
?>


