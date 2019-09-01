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

    $sql = "SELECT * FROM election_table WHERE category = '$category'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    $content .= '<div >
		<h3>AVAILABLE CANDIDATES</h3>';
    //loop through all table rows

    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            $firstname = $row["firstname"];
            $lastname = $row["lastname"];


            if (!$has_voted) {
                $content .= "<div>
                        <form action='vote.php' method='post'>
                        <p>First Name: <input type='text'  name='firstname' value=$firstname></p>
                        <p>Last Name: <input type='text'  name='lastname' value='$lastname'></p>
                        <br>
                        <input type='submit' value='Vote' name='vote'>
                        </form>
                        </div>";
            } else {
                $content .= " <div>
                        <form action = 'vote.php' method = 'post' >
                        <p > First Name: <input type = 'text'  name = 'firstname' value = $firstname ></p >
                        <p > Last Name: <input type = 'text'  name = 'lastname' value = '$lastname' ></p >
                        <br >
                            </form >
                        </div > ";
            }
        }
    } else {
        $content .= "<p > There are no candidates for this category < p>";
    }
    $content .= '</div>';

} else {
    $sql = "SELECT DISTINCT category FROM election_table";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            $category = $row["category"];
            $content .= '<form action="vote.php" method="get">
                        <input type="submit"name="category" value="' . $category . '" class="dropdown - item category">
                        </form>';
        }
    }

}

$title = "Voting page";
require_once("layout.php");

?>
