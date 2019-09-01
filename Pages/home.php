<?php
session_start();
/**
 * Created by PhpStorm.
 * User: Victor
 * Date: 30/08/2019
 * Time: 8:06 PM
 */
require_once('db.php');

echo $_SESSION['id'];
if(!isset($_SESSION['id'])){
    header("location:access-denied.php");
    exit();
}
$title = "Home Page";
$content = "<p> Content will be placed here</p>";
require_once("layout.php");
?>


