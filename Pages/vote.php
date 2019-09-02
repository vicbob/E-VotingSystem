<?php
/**
 * Created by PhpStorm.
 * User: Victor
 * Date: 01/09/2019
 * Time: 3:52 PM
 */

session_start();
require('db.php');

if (empty($_SESSION['id'])) {
    header("location:access-denied.php");
}

//for vote
if (isset($_POST['vote'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    echo count($_POST);
    $category = $_SESSION['category'];
    $user_id = $_SESSION['id'];

    $sql = "SELECT vote_count FROM election_table WHERE category = '$category' AND firstname='$firstname' AND lastname='$lastname' ";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    if (mysqli_num_rows($result) > 0) {
        $row = $result->fetch_assoc();
        $vote_count = $row['vote_count'];
        if ($vote_count == null) {
            $vote_count = 0;
        }
        $vote_count += 1;
        $sql = "UPDATE election_table SET vote_count='$vote_count' WHERE category = '$category' AND firstname='$firstname' AND lastname='$lastname'";
        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

        if ($result == true) {
            $_SESSION['message'] = "Vote successful";
            $_SESSION['message_status'] = "success";
        }

        $sql = "INSERT INTO track_table (category, user_id)
    VALUES ('$category','$user_id')";
        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));


    }
}

$content = '';
if (isset($_GET['category'])) {
    $category = $_GET['category'];
    $_SESSION['category'] = $category;
    $category = $_SESSION['category'];
    $user_id = $_SESSION['id'];
    $has_voted = false;
//    check if user has voted
    $sql = "SELECT * FROM track_table WHERE user_id = '$user_id' AND category = '$category'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    if (mysqli_num_rows($result) > 0) {
        $has_voted = true;
    }

    $sql = "SELECT dob FROM users WHERE id ='$user_id'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $dob = '';
    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            $dob = $row["dob"];
        }
    }

    $eligibility = false;
    if ($dob != NULL) {
        $date = (new DateTime())->format('Y-m-d');
        $diff = strtotime($date) - strtotime($dob);
        $years = floor($diff / (365 * 60 * 60 * 24));
        if ($years >= 18) {
            $eligibility = true;
        }
    }

    $sql = "SELECT * FROM election_table WHERE category = '$category'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    $content .= '<div class="jumbotron">
		<h3 class="text-center">AVAILABLE CANDIDATES</h3>
                   <div class="card-columns">';
    //loop through all table rows

    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            $firstname = $row["firstname"];
            $lastname = $row["lastname"];


            if (!$has_voted and $eligibility) {
                $content .= "<div class='card align-items-center mb-3  bg-primary text-white' style=\"width: 20rem;\" >
                            <img class=\"card-img-top\" style=\"height:12rem;\" src=\"../images/demo/avatar.png\" alt=\"vote image\">
                                                          <div class=\"card-body align-items-center\">
                        <form action='vote.php' method='post' class='align-items-center text-center'>
                        <p class='text-center'>First Name: <input type='text' class='borderless text-white' contenteditable='false'  name='firstname' value=$firstname></p>
                        <p class='text-center'>Last Name: <input type='text' class='borderless text-white' contenteditable='false'  name='lastname' value='$lastname'></p>
                        <br>
                        <input class='btn btn-success align-self-center' type='submit' value='Vote' name='vote'>
                        </form>
                        </div>
                        </div>";
            } else {
                $content .= " <div class='card align-items-center mb-3  bg-primary text-white' style=\"width: 20rem;\" >
                            <img class=\"card-img-top\" style=\"height:12rem;\" src=\"../images/demo/avatar.png\" alt=\"vote image\">
                                                          <div class=\"card-body align-items-center\">
                        <form action = 'vote.php' method = 'post' class='align-items-center' >
                        <p class='text-center' > First Name: <input class='borderless text-white' contenteditable='false' type = 'text'  name = 'firstname' value = $firstname ></p >
                        <p class='text-center'> Last Name: <input class='borderless text-white' contenteditable='false' type = 'text'  name = 'lastname' value = '$lastname' ></p >
                        <br >
                            </form >
                        </div >
                         </div>";
            }
        }
    } else {
        $content .= "<p > There are no candidates for this category < p>";
    }
    $content .= '</div></div>';

} else {
    $sql = "SELECT DISTINCT category FROM election_table";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $content .= '<div class="jumbotron">
                   <div class="card-columns">';
    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            $category = $row["category"];
            $content .= ' <form action="vote.php" method="get" class="text-center">
            <button type="submit" value="'.$category.'" name="category"  class=" text-white borderless card p-3 align-items-center  bg-primary text-white">
                   ' . $category . '
                          </button>
                       </form>';
        }
    }

    $content .= '</div></div>';

}

$title = "Voting page";
require_once("layout.php");

?>