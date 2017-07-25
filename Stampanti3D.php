<?php
/**
 * Created by PhpStorm.
 * User: AnielloMalinconico
 * Date: 03/06/16
 * Time: 19:47
 */

include ("Functions.php");
session();
$conn=dbConnect();

//Checks if there is a login cookie
if(isset($_COOKIE['username'])){ //if there is, it logs you in and directes you to the members page
    $username = $_COOKIE['username'];
    $pass = $_COOKIE['password'];

    $check = mysqli_query($conn, "SELECT * FROM UTENTI WHERE USERNAME = '$username'")or die(mysqli_error());
    while($info = mysqli_fetch_array( $check )) {
        if ($pass != $info['PASSWORD']) {
           // header("Location: Login.php");
            echo $pass;
            echo $info['PASSWORD'];
        } else {


        }

    }
}
?>